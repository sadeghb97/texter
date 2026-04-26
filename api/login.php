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

$conn = new TexterConnection();
try {
    $auth = new TexterAuth();
    $res = $auth->login($conn, $username, $password);
    if (!($res['ok'] ?? false)) {
        $err = (string)($res['error'] ?? 'login_failed');
        if ($err === 'username_not_found') http_response_code(404);
        else http_response_code(400);
        echo json_encode(['error' => $err]);
        exit;
    }
    echo json_encode($res);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server_error']);
}
$conn->close();

