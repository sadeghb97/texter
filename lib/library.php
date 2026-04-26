<?php
require_once "AppConfigs.php";
require_once "init_avt.php";
require_once "env_configs.php";
require_once "TexterConnection.php";
require_once "TexterAuth.php";
require_once "AuthApiController.php";
require_once "TexterAuth.php";

// Ensure consistent app timezone (GMT+3:30).
date_default_timezone_set('Asia/Tehran');

$texterAuth = new TexterAuth();
$texterAuth->startSession();
