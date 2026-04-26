<?php
include '../lib/library.php';

header('Content-Type: application/json; charset=utf-8');

$conn = new TexterConnection();
$auth = new TexterAuth();

if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$messagePkRaw = is_array($data) && array_key_exists('message_pk', $data) ? $data['message_pk'] : null;

$messagePk = 0;
if (is_int($messagePkRaw)) $messagePk = $messagePkRaw;
else if (is_string($messagePkRaw) && $messagePkRaw !== '' && ctype_digit($messagePkRaw)) $messagePk = (int)$messagePkRaw;
else if (is_float($messagePkRaw)) $messagePk = (int)$messagePkRaw;

if ($messagePk <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "message_pk_required"]);
    exit;
}

$profilePk = (int)$auth->currentUserId();

// User can only delete messages that belong to their own page (profile_pk).
$conn->query("DELETE FROM messages WHERE pk = $messagePk AND profile_pk = $profilePk LIMIT 1");
if ($conn->affected_rows <= 0) {
    // Not found (or not owned by this user).
    http_response_code(404);
    echo json_encode(["error" => "not_found"]);
    exit;
}

echo json_encode(["status" => "ok"]);
?>

