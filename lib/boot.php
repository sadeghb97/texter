<?php
require_once __DIR__ . "/../vendor/autoload.php";
//require_once __DIR__ . "/../../avetify/avetify.php";

use Avetify\AvetifyManager;
AvetifyManager::init(dirname(__DIR__), dirname(__DIR__),"/texter", "/avetify/assets");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();
