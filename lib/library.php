<?php
require_once "boot.php";
require_once "AppConfigs.php";
require_once "TexterConnection.php";
require_once "TexterAuth.php";
require_once "TexterAuthApiController.php";
require_once "MessagePublic.php";

// Ensure consistent app timezone (GMT+3:30).
date_default_timezone_set('Asia/Tehran');

$texterAuth = new TexterAuth();
$texterAuth->startSession();
