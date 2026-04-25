<?php
require_once "AppConfigs.php";

function isHttpsRequest(): bool {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') return true;
    if (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) return true;
    // Common reverse-proxy header (if present)
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') return true;
    return false;
}

function appSessionKey(string $name): string {
    // Namespace session keys per app to avoid collisions on shared hosts.
    return AppConfigs::APP_ID . '_' . $name;
}
