<?php
require_once __DIR__ . '/../lib/library.php';
requireLogin();

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

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $conn = new TexterConnection();
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;

    if (!$row || empty($row['password'])) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    if (!password_verify($currentPassword, $row['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Current password is incorrect']);
        exit;
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ? LIMIT 1");
    $upd->bind_param("si", $hash, $userId);

    if (!$upd->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update password']);
        exit;
    }

    echo json_encode(['ok' => true]);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
}

