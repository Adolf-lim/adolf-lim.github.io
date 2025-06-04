<?php
//
// domain-check.php
// Responds to GET parameter “domain=example.com” with a JSON payload:
//   { "result":"success", "available":true }
// or
//   { "result":"success", "available":false }
// or on error:
//   { "result":"error",   "message":"…some error text…" }
//

// 1) Bootstrap WHMCS
// Adjust the path below to point at your actual WHMCS “init.php”
require_once __DIR__ . '/whmcs/init.php';

// Tell WHMCS we are in client-area mode (so Captcha, token, localAPI, etc. are available)
define('CLIENTAREA', true);

// Initialize the ClientArea object (this will also start the session and load config)
$ca = new WHMCS_ClientArea();
$ca->initPage();

// 2) Prepare JSON response
header('Content-Type: application/json; charset=utf-8');

// 3) Grab the “domain” query parameter, sanitize it
if (!isset($_GET['domain']) || trim($_GET['domain']) === '') {
    echo json_encode([
        'result'  => 'error',
        'message' => 'No domain parameter provided'
    ]);
    exit;
}

// Remove any characters except letters, numbers, dots, and hyphens
$requestedDomain = preg_replace('/[^a-zA-Z0-9\.\-]/', '', $_GET['domain']);

// Quick check: must look like “something.something”
if (strpos($requestedDomain, '.') === false) {
    echo json_encode([
        'result'  => 'error',
        'message' => 'Domain must include a TLD (e.g. “example.com”)'
    ]);
    exit;
}

// 4) Call WHMCS’s “DomainWhois” localAPI to check availability
//    You need to supply a valid admin username here. You can create a dedicated WHMCS staff/admin user
//    called e.g. “domainLookupUser” and give it minimal permissions (just “View Domains”).
//    Replace “YOUR_ADMIN_USERNAME” below with that actual admin username.
$adminUsername = 'Nemwang';

try {
    $apiResult = localAPI(
        'DomainWhois',
        [ 'domainname' => $requestedDomain ],
        $adminUsername
    );
} catch (Exception $e) {
    // Something went wrong at the PHP-level (e.g. init.php path wrong, etc.)
    echo json_encode([
        'result'  => 'error',
        'message' => 'WHMCS API error: ' . $e->getMessage()
    ]);
    exit;
}

// 5) Check what “DomainWhois” gave us
if (!isset($apiResult['result']) || $apiResult['result'] !== 'success') {
    // WHMCS returned an error (for example, invalid credentials, etc.)
    echo json_encode([
        'result'  => 'error',
        'message' => $apiResult['message'] ?? 'Unknown error from WHMCS'
    ]);
    exit;
}

// DomainWhois will return “status” = “available” or “status” = “unavailable” (or “taken”),
// depending on your registrar module’s response.
$status    = strtolower($apiResult['status'] ?? '');
$available = ($status === 'available');

echo json_encode([
    'result'    => 'success',
    'available' => $available
]);
exit;
