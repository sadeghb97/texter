<?php

class MessagePublic
{
    public static function encodeId(int $pk): string
    {
        if ($pk <= 0) {
            return '';
        }
        return strtolower(base_convert((string)$pk, 10, 36));
    }

    public static function decodeId(string $slug): ?int
    {
        $slug = strtolower(trim($slug));
        if ($slug === '' || !preg_match('/^[0-9a-z]+$/', $slug)) {
            return null;
        }

        $pk = (int)base_convert($slug, 36, 10);
        return $pk > 0 ? $pk : null;
    }

    /**
     * Web path to the app root (e.g. "/texter"), not the current script directory.
     */
    public static function appBasePath(): string
    {
        static $base = null;
        if ($base !== null) {
            return $base;
        }

        $appRoot = str_replace('\\', '/', dirname(__DIR__));
        $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $docRootReal = $docRoot !== '' ? realpath($docRoot) : false;
        $appRootReal = realpath($appRoot);

        if ($docRootReal !== false && $appRootReal !== false) {
            $docRootReal = str_replace('\\', '/', $docRootReal);
            $appRootReal = str_replace('\\', '/', $appRootReal);
            if (str_starts_with($appRootReal, $docRootReal)) {
                $relative = substr($appRootReal, strlen($docRootReal));
                $base = rtrim($relative, '/');
                return $base;
            }
        }

        // Fallback when document root mapping is unavailable.
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
        $dir = dirname($script);
        if (preg_match('#/api$#', $dir)) {
            $dir = dirname($dir);
        }
        if ($dir === '/' || $dir === '.') {
            $base = '';
        } else {
            $base = rtrim($dir, '/');
        }

        return $base;
    }

    public static function requestOrigin(): string
    {
        $scheme = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $forwarded = strtolower(trim(explode(',', (string)$_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
            if ($forwarded === 'https') {
                $scheme = 'https';
            }
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }

    public static function publicPath(int $pk): string
    {
        $slug = self::encodeId($pk);
        $base = self::appBasePath();
        return ($base !== '' ? $base : '') . '/' . $slug;
    }

    public static function publicUrl(int $pk, bool $absolute = true): string
    {
        $path = self::publicPath($pk);
        if (!$absolute) {
            return $path;
        }

        return self::requestOrigin() . $path;
    }
}
