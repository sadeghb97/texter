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

try {
    $conn = new TexterConnection();

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'username_not_found']);
        exit;
    }

    if (!password_verify($password, (string)($user['password'] ?? ''))) {
        http_response_code(400);
        echo json_encode(['error' => 'wrong_password']);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION[appSessionKey('user_id')] = (int)$user['id'];
    $_SESSION[appSessionKey('username')] = $username;

    // Create remember_me token (stored hashed in DB)
    issueRememberMeToken($conn, (int)$user['id']);

    // Persist session immediately to avoid race with next navigation request.
    session_write_close();

    echo json_encode(['ok' => true, 'user' => ['id' => (int)$user['id'], 'username' => $username]]);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server_error']);
    exit;
}

