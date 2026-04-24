<?php
session_set_cookie_params([
    'lifetime' => 60 * 60 * 24 * 30, // 30 days
    'path' => '/',
    'secure' => false, // set true if using HTTPS
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
