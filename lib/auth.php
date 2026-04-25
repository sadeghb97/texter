<?php
function rememberMeCookieName(): string {
    // Use a different cookie name on HTTP to avoid collisions with an existing
    // Secure remember_me cookie set earlier over HTTPS (which cannot be overwritten on HTTP).
    return function_exists('isHttpsRequest') && isHttpsRequest() ? 'remember_me' : 'remember_me_http';
}

function authCookieBaseParams(): array {
    // Keep consistent with lib/library.php session_set_cookie_params()
    // by inheriting common flags from the active session cookie params.
    $sessionParams = session_get_cookie_params();
    return [
        'path' => (string)($sessionParams['path'] ?? '/'),
        'httponly' => (bool)($sessionParams['httponly'] ?? true),
        'secure' => (bool)($sessionParams['secure'] ?? false),
        'samesite' => (string)($sessionParams['samesite'] ?? 'Lax'),
    ];
}

function authCookieParams(): array {
    return [
        'expires' => time() + (60 * 60 * 24 * AppConfigs::TOKEN_LIFETIME_DAYS),
        ...authCookieBaseParams(),
    ];
}

function setRememberMeCookie(string $token): void {
    $params = authCookieParams();
    setcookie(rememberMeCookieName(), $token, $params);
}

function clearRememberMeCookie(): void {
    // Expire cookie (clear both Secure and non-Secure variants to avoid scheme flips).
    $base = authCookieBaseParams();
    $names = array_values(array_unique([rememberMeCookieName(), 'remember_me', 'remember_me_http']));
    foreach ($names as $name) {
        setcookie($name, '', ['expires' => time() - 3600, ...$base]);
        setcookie($name, '', [
        'expires' => time() - 3600,
        ...$base,
        'secure' => true,
        ]);
        setcookie($name, '', [
        'expires' => time() - 3600,
        ...$base,
        'secure' => false,
        ]);
    }
}

function issueRememberMeToken(TexterConnection $conn, int $userId): ?string {
    if ($userId <= 0) return null;

    try {
        $token = bin2hex(random_bytes(32));
    } catch (Throwable $_) {
        return null;
    }
    $tokenHash = hash('sha256', $token);

    $days = (int)AppConfigs::TOKEN_LIFETIME_DAYS;
    if ($days <= 0) $days = 30;
    $expiresAt = date('Y-m-d H:i:s', time() + ($days * 86400));

    $stmt = $conn->prepare("
        INSERT INTO user_tokens (user_id, token_hash, expires_at)
        VALUES (?, ?, ?)
    ");
    if (!$stmt) return null;
    $stmt->bind_param("iss", $userId, $tokenHash, $expiresAt);
    if (!$stmt->execute()) return null;

    setRememberMeCookie($token);
    return $token;
}

function revokeRememberMeToken(TexterConnection $conn, string $rawToken): void {
    $rawToken = trim($rawToken);
    if ($rawToken === '') return;
    $tokenHash = hash('sha256', $rawToken);

    $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token_hash = ?");
    if (!$stmt) return;
    $stmt->bind_param("s", $tokenHash);
    $stmt->execute();
}

function authBootstrap(): void {
    if (!empty($_SESSION[appSessionKey('user_id')])) return;
    $cookieName = rememberMeCookieName();
    if (empty($_COOKIE[$cookieName])) return;

    $token = (string)$_COOKIE[$cookieName];
    $token = trim($token);
    if ($token === '') {
        clearRememberMeCookie();
        return;
    }
    $tokenHash = hash('sha256', $token);

    try {
        $conn = new TexterConnection();

        $stmt = $conn->prepare("
            SELECT ut.user_id, u.username
            FROM user_tokens ut
            INNER JOIN users u ON u.id = ut.user_id
            WHERE ut.token_hash = ?
              AND ut.expires_at > NOW()
            LIMIT 1
        ");
        if (!$stmt) {
            clearRememberMeCookie();
            return;
        }
        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;

        if (!$row || empty($row['user_id'])) {
            clearRememberMeCookie();
            return;
        }

        // Rotate token: revoke old one and issue a new token
        revokeRememberMeToken($conn, $token);

        session_regenerate_id(true);
        $_SESSION[appSessionKey('user_id')] = (int)$row['user_id'];
        $_SESSION[appSessionKey('username')] = (string)($row['username'] ?? '');

        issueRememberMeToken($conn, (int)$row['user_id']);
    } catch (Throwable $_) {
        // Silent fail; do not block app
        clearRememberMeCookie();
        return;
    }
}

function requireLogin() {
    authBootstrap();
    if (empty($_SESSION[appSessionKey('user_id')])) {
        header("Location: login.php");
        exit;
    }
}
?>