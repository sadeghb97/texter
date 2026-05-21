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

    public static function isValidSlug(string $slug): bool
    {
        $slug = trim($slug);
        if ($slug === '' || strlen($slug) > 64) {
            return false;
        }
        return (bool)preg_match('/^[0-9a-z][0-9a-z\-]*$/i', $slug);
    }

    public static function normalizeSlug(string $slug): string
    {
        return strtolower(trim($slug));
    }

    public static function generateDefaultSlug(int $messagePk): string
    {
        $base = self::encodeId($messagePk);
        $suffix = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return $base . '-' . $suffix;
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

    /**
     * Root-relative URL for static assets and app scripts (works with pretty URLs).
     */
    public static function assetUrl(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $base = self::appBasePath();
        if ($base === '') {
            return '/' . $path;
        }
        return $base . '/' . $path;
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

    public static function messagePath(int $profilePk, string $slug): string
    {
        $ppk = self::encodeId($profilePk);
        $slug = self::normalizeSlug($slug);
        if ($ppk === '' || $slug === '') {
            return '';
        }
        $base = self::appBasePath();
        return ($base !== '' ? $base : '') . '/' . $ppk . '/' . rawurlencode($slug);
    }

    public static function messageUrl(int $profilePk, string $slug, bool $absolute = true): string
    {
        $path = self::messagePath($profilePk, $slug);
        if ($path === '') {
            return '';
        }
        if (!$absolute) {
            return $path;
        }
        return self::requestOrigin() . $path;
    }

    /** @deprecated Use messagePath() with profile_pk and slug */
    public static function publicPath(int $pk): string
    {
        return self::messagePath($pk, self::encodeId($pk));
    }

    /** @deprecated Use messageUrl() with profile_pk and slug */
    public static function publicUrl(int $pk, bool $absolute = true): string
    {
        return self::messageUrl($pk, self::encodeId($pk), $absolute);
    }

    public static function messageHasPassword(?string $passwordHash): bool
    {
        return $passwordHash !== null && trim($passwordHash) !== '';
    }
}
