Checkout Concurrency Testing - Notes and Artifacts

Files added:
- app/Console/Commands/SimulateConcurrentCheckout.php : Artisan-style command (may require Console Kernel registration to run via artisan).
- scripts/simulate_concurrent_checkout.php : Standalone PHP script using cURL (supports curl_multi if available).
- scripts/compare_checkout_methods.php : Sequential benchmark comparator between locked and optimistic endpoints.
- tests/e2e/checkout.spec.ts : Playwright e2e test (requires Playwright setup and browsers).

Controller changes:
- app/Http/Controllers/CheckoutController.php: added storeOptimistic(Request $request) implementing optimistic UPDATE ... WHERE stock >= :qty pattern.

View changes:
- resources/views/landing/checkout.blade.php: added display for $errors->first('stock') so stock validation messages show to users.

How to run locally:
1. Start the Laravel app: php artisan serve
2. Use the standalone script to simulate concurrent requests:
   php scripts/simulate_concurrent_checkout.php http://127.0.0.1:8000 user@example.com password 5

3. To benchmark optimistic vs locked:
   php scripts/compare_checkout_methods.php http://127.0.0.1:8000 user@example.com password 5

Notes and limitations:
- The environment used to perform these changes may not have an active webserver, reachable network, or running database. The scripts are provided as artifacts to run locally where the app is available.
- The Artisan command class is added but may not be registered if app/Console/Kernel.php isn't present or doesn't autoload commands. The standalone scripts are the recommended runnable artifacts.
- Playwright test is provided at tests/e2e/checkout.spec.ts but running it requires Playwright installed locally and browsers available (may download browsers on first run).
