<?php
// Compare calling normal checkout vs optimistic endpoint sequentially
// Usage: php scripts/compare_checkout_methods.php http://localhost:8000 email password iterations

if ($argc < 4) {
    echo "Usage: php scripts/compare_checkout_methods.php {base_url} {email} {password} [iterations]\n";
    exit(1);
}

$base = rtrim($argv[1], '/');
$email = $argv[2];
$password = $argv[3];
$iters = isset($argv[4]) ? (int)$argv[4] : 5;

// Login and get cookies
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['email'=>$email,'password'=>$password]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$resp = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($resp, 0, $header_size);
preg_match_all('/Set-Cookie: (.*);/iU', $headers, $matches);
$cookies = $matches[1] ?? [];
$cookieHeader = implode('; ', $cookies);
curl_close($ch);

// Grab CSRF
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base . '/checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookieHeader"]);
$page = curl_exec($ch);
curl_close($ch);
preg_match('/name="_token" value="([^"]+)"/', $page, $m);
$token = $m[1] ?? null;
if (!$token) { echo "CSRF token not found\n"; exit(1); }

$payload = http_build_query([
    '_token' => $token,
    'recipient_name' => 'Benchmark User',
    'recipient_phone' => '+62123456789',
    'shipping_address' => 'Jl. Example',
    'shipping_city' => 'Jakarta',
    'shipping_province' => 'DKI Jakarta',
    'shipping_postal_code' => '10110',
    'payment_method' => 'va_bank',
    'payment_channel' => 'BCA',
]);

function run_once($url, $cookieHeader, $payload) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookieHeader"]);
    $start = microtime(true);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    $dur = microtime(true) - $start;
    curl_close($ch);
    return ['status'=>$info['http_code'],'time'=>$dur,'body'=>substr($resp,0,200)];
}

echo "Running $iters iterations for locked store endpoint...\n";
$lockedTimes = [];
for ($i=0;$i<$iters;$i++) {
    $res = run_once($base . '/checkout', $cookieHeader, $payload);
    echo "Locked run #".($i+1)." status={$res['status']} time={$res['time']}\n";
    $lockedTimes[] = $res['time'];
}

echo "Running $iters iterations for optimistic store endpoint...\n";
$optTimes = [];
for ($i=0;$i<$iters;$i++) {
    $res = run_once($base . '/checkout/optimistic', $cookieHeader, $payload);
    echo "Optimistic run #".($i+1)." status={$res['status']} time={$res['time']}\n";
    $optTimes[] = $res['time'];
}

echo "Summary:\n";
echo "Locked avg: " . (array_sum($lockedTimes)/count($lockedTimes)) . "s\n";
echo "Optimistic avg: " . (array_sum($optTimes)/count($optTimes)) . "s\n";
