<?php
require_once __DIR__ . "/../vendor/autoload.php";
//require_once __DIR__ . "/../../avetify/avetify.php";

use Avetify\AvetifyManager;
AvetifyManager::init(dirname(__DIR__), dirname(__DIR__), AVT_PUBLIC_URL, AVT_ASSETS_URL);
