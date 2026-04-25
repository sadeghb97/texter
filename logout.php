<?php
require 'lib/library.php';

// Remove remember_me token from DB (best-effort)
try {
    // Try both cookie names (HTTP/HTTPS variants).
    $rm = $_COOKIE['remember_me'] ?? ($_COOKIE['remember_me_http'] ?? null);
    if (!empty($rm)) {
        $conn = new TexterConnection();
        revokeRememberMeToken($conn, (string)$rm);
    }
} catch (Throwable $_) {}

$_SESSION = [];
session_destroy();

// Clear session cookie in both Secure/non-Secure forms to prevent browser conflicts.
$cookieName = session_name();
// Also try clearing the default HTTPS name in case the scheme flipped.
$cookieNames = array_values(array_unique([$cookieName, 'PHPSESSID', 'TEXTERSESSID_HTTP']));
foreach ($cookieNames as $n) {
    setcookie($n, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => true,
    ]);
    setcookie($n, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => false,
    ]);
}
clearRememberMeCookie();

header("Location: login.php");
exit;
?>