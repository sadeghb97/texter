<?php
require_once __DIR__ . '/../lib/library.php';

$conn = new TexterConnection();
$auth = new TexterAuth();
$auth->requireLogin($conn);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$currentPassword = (string)($payload['current_password'] ?? '');
$newPassword = (string)($payload['new_password'] ?? '');

$currentPassword = trim($currentPassword);
$newPassword = trim($newPassword);

if ($currentPassword === '' || $newPassword === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

if (strlen($newPassword) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 6 characters']);
    exit;
}

$userId = $auth->currentUserId();
if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $res = $auth->changePassword($conn, $userId, $currentPassword, $newPassword);
    if (!($res['ok'] ?? false)) {
        $err = (string)($res['error'] ?? 'server_error');
        if ($err === 'wrong_password') {
            http_response_code(400);
            echo json_encode(['error' => 'Current password is incorrect']);
            exit;
        }
        if ($err === 'user_not_found') {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            exit;
        }
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
        exit;
    }
    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
$conn->close();

