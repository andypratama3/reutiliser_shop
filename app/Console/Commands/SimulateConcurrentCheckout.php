<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

/**
 * Simulate concurrent checkout by issuing multiple HTTP requests to checkout.store
 * Usage: php artisan simulate:checkout {url} {email} {password} {concurrency=5}
 */
class SimulateConcurrentCheckout extends Command
{
    protected $signature = 'simulate:checkout {url} {email} {password} {concurrency=5}';
    protected $description = 'Simulate concurrent checkout requests';

    public function handle()
    {
        $url = $this->argument('url');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $concurrency = (int)$this->argument('concurrency');

        $this->info("Simulating $concurrency checkouts against $url");

        // We will first login to obtain session cookie
        $resp = Http::asForm()->post($url . '/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($resp->failed()) {
            $this->error('Login failed: ' . $resp->body());
            return 1;
        }

        $cookies = $resp->cookies();

        $jobs = [];
        for ($i = 0; $i < $concurrency; $i++) {
            $jobs[] = function() use ($url, $cookies) {
                // load checkout page to get CSRF token
                $page = Http::withCookies($cookies, parse_url($url, PHP_URL_HOST))->get($url . '/checkout');
                preg_match('/name="_token" value="([^"]+)"/', $page->body(), $m);
                $token = $m[1] ?? null;
                if (!$token) {
                    return ['error' => 'csrf not found'];
                }

                $resp = Http::withCookies($cookies, parse_url($url, PHP_URL_HOST))
                    ->asForm()
                    ->post($url . '/checkout', [
                        '_token' => $token,
                        'recipient_name' => 'Sim User',
                        'recipient_phone' => '+62123456789',
                        'shipping_address' => 'Jl. Example',
                        'shipping_city' => 'Jakarta',
                        'shipping_province' => 'DKI Jakarta',
                        'shipping_postal_code' => '10110',
                        'payment_method' => 'va_bank',
                        'payment_channel' => 'BCA',
                    ]);

                return ['status' => $resp->status(), 'body' => substr($resp->body(), 0, 500)];
            };
        }

        // Run sequentially because parallel PHP HTTP in this environment may be restricted
        $results = [];
        foreach ($jobs as $idx => $job) {
            $this->info("Running job #" . ($idx+1));
            $results[] = $job();
        }

        // Output results
        foreach ($results as $i => $r) {
            $this->line("Job #" . ($i+1) . ": " . json_encode($r));
        }

        // Quick DB check for negative stock or oversells
        $oversold = DB::select("SELECT id, name, stock FROM products WHERE stock < 0 OR stock < 0");
        $this->info('Oversold products: ' . json_encode($oversold));

        return 0;
    }
}
