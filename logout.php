<?php
require 'lib/library.php';

// Remove remember_me token from DB (best-effort)
try {
    if (!empty($_COOKIE['remember_me'])) {
        $conn = new TexterConnection();
        revokeRememberMeToken($conn, (string)$_COOKIE['remember_me']);
    }
} catch (Throwable $_) {}

$_SESSION = [];
session_destroy();

setcookie(session_name(), '', time() - 3600, '/');
clearRememberMeCookie();

header("Location: login.php");
exit;
?>