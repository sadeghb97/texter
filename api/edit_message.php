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
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "invalid_json"]);
    exit;
}

$messagePkRaw = array_key_exists('message_pk', $data) ? $data['message_pk'] : null;

$messagePk = 0;
if (is_int($messagePkRaw)) {
    $messagePk = $messagePkRaw;
} elseif (is_string($messagePkRaw) && $messagePkRaw !== '' && ctype_digit($messagePkRaw)) {
    $messagePk = (int)$messagePkRaw;
} elseif (is_float($messagePkRaw)) {
    $messagePk = (int)$messagePkRaw;
}

if ($messagePk <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "message_pk_required"]);
    exit;
}

$rawText = array_key_exists('text', $data) ? (string)$data['text'] : '';
$rawText = trim($rawText);
if ($rawText === '') {
    http_response_code(400);
    echo json_encode(["error" => "text_required"]);
    exit;
}

$userPk = (int)$auth->currentUserId();
$messagePkEsc = (int)$messagePk;
$textEsc = $conn->real_escape_string($rawText);

$res = $conn->query(
    "SELECT pk FROM messages
     WHERE pk = $messagePkEsc AND profile_pk = $userPk AND author_pk = $userPk
     LIMIT 1"
);
$row = $res ? $res->fetch_assoc() : null;
if (!$row) {
    http_response_code(404);
    echo json_encode(["error" => "not_found"]);
    exit;
}

$conn->query(
    "UPDATE messages SET text = '$textEsc'
     WHERE pk = $messagePkEsc AND profile_pk = $userPk AND author_pk = $userPk
     LIMIT 1"
);

if ($conn->affected_rows < 0) {
    http_response_code(500);
    echo json_encode(["error" => "update_failed"]);
    exit;
}

echo json_encode([
    "status" => "ok",
    "text" => $rawText,
]);
