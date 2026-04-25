<?php
require_once __DIR__ . '/../lib/library.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_json']);
    exit;
}

$username = trim((string)($payload['username'] ?? ''));
$password = (string)($payload['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'missing_fields']);
    exit;
}

if (strlen($username) < 3) {
    http_response_code(400);
    echo json_encode(['error' => 'username_too_short']);
    exit;
}

if (strlen($password) < 4) {
    http_response_code(400);
    echo json_encode(['error' => 'password_too_short']);
    exit;
}

try {
    $conn = new TexterConnection();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);

    if (!$stmt->execute()) {
        // Most likely duplicate username (unique constraint), but avoid leaking DB details.
        http_response_code(409);
        echo json_encode(['error' => 'username_exists']);
        exit;
    }

    $userId = (int)$stmt->insert_id;
    if ($userId <= 0) {
        http_response_code(500);
        echo json_encode(['error' => 'server_error']);
        exit;
    }

    // Auto-login on successful registration
    session_regenerate_id(true);
    $_SESSION[appSessionKey('user_id')] = $userId;
    $_SESSION[appSessionKey('username')] = $username;

    // Create remember_me token (stored hashed in DB)
    issueRememberMeToken($conn, $userId);

    // Persist session immediately to avoid race with next navigation request.
    session_write_close();

    echo json_encode(['ok' => true, 'user' => ['id' => $userId, 'username' => $username]]);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server_error']);
    exit;
}

