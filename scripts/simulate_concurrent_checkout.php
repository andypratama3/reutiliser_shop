<?php
// Standalone script to simulate concurrent checkouts against local app
// Usage: php scripts/simulate_concurrent_checkout.php http://localhost:8000 email password 5

if ($argc < 4) {
    echo "Usage: php scripts/simulate_concurrent_checkout.php {base_url} {email} {password} [concurrency]\n";
    exit(1);
}

$base = rtrim($argv[1], '/');
$email = $argv[2];
$password = $argv[3];
$concurrency = isset($argv[4]) ? (int)$argv[4] : 5;

echo "Login as $email to $base\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['email'=>$email,'password'=>$password]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$resp = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($resp, 0, $header_size);
$body = substr($resp, $header_size);

preg_match_all('/Set-Cookie: (.*);/iU', $headers, $matches);
$cookies = [];
foreach ($matches[1] as $c) {
    $cookies[] = $c;
}
curl_close($ch);

$cookieHeader = implode('; ', $cookies);
echo "Got cookies: " . $cookieHeader . "\n";

// fetch checkout page to extract csrf
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookieHeader"]);
$page = curl_exec($ch);
curl_close($ch);
preg_match('/name="_token" value="([^"]+)"/', $page, $m);
$token = $m[1] ?? null;
if (!$token) { echo "CSRF token not found\n"; exit(1); }
echo "CSRF token: $token\n";

$payload = http_build_query([
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

// Try to use curl_multi to run parallel requests
if (function_exists('curl_multi_init')) {
    echo "Running $concurrency parallel requests using curl_multi\n";
    $mh = curl_multi_init();
    $chs = [];
    for ($i=0;$i<$concurrency;$i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base . '/checkout');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookieHeader"]);
        curl_multi_add_handle($mh, $ch);
        $chs[] = $ch;
    }

    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    foreach ($chs as $ch) {
        $resp = curl_multi_getcontent($ch);
        $info = curl_getinfo($ch);
        echo "Status: " . $info['http_code'] . " Body snippet: " . substr($resp,0,200) . "\n";
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);
} else {
    echo "curl_multi not available; running sequentially\n";
    for ($i=0;$i<$concurrency;$i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base . '/checkout');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookieHeader"]);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        echo "Job #".($i+1)." Status: " . $info['http_code'] . "\n";
        curl_close($ch);
    }
}

echo "Done\n";
