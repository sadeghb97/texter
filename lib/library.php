<?php
require_once "AppConfigs.php";
require_once "utils.php";

$isHttps = isHttpsRequest();

// Avoid collisions with pre-existing Secure cookies when serving over HTTP.
// Browsers may reject setting a non-Secure cookie if a Secure cookie with the
// same name already exists for this site.
if (!$isHttps) {
    session_name('TEXTERSESSID_HTTP');
}

session_set_cookie_params([
    'lifetime' => 60 * 60 * 24 * AppConfigs::TOKEN_LIFETIME_DAYS,
    'path' => '/',
    // IMPORTANT: must match the scheme consistently, otherwise browsers may reject
    // overwriting an existing Secure PHPSESSID cookie (causing login loops).
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Ensure consistent app timezone (GMT+3:30).
date_default_timezone_set('Asia/Tehran');

require_once "init_avt.php";
require_once "env_configs.php";
require_once "TexterConnection.php";
require_once "auth.php";

// Auto-login using remember_me cookie (if present).
authBootstrap();
