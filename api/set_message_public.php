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

$wantPublic = null;
if (is_array($data)) {
    if (array_key_exists('public', $data)) {
        $wantPublic = (bool)(int)$data['public'];
    } elseif (array_key_exists('is_public', $data)) {
        $wantPublic = (bool)$data['is_public'];
    }
}

if ($wantPublic === null) {
    http_response_code(400);
    echo json_encode(["error" => "public_required"]);
    exit;
}

$profilePk = (int)$auth->currentUserId();
$messagePkEsc = (int)$messagePk;

$res = $conn->query(
    "SELECT pk, public FROM messages WHERE pk = $messagePkEsc AND profile_pk = $profilePk LIMIT 1"
);
$row = $res ? $res->fetch_assoc() : null;
if (!$row) {
    http_response_code(404);
    echo json_encode(["error" => "not_found"]);
    exit;
}

$newPublic = $wantPublic ? 1 : 0;
$conn->query("UPDATE messages SET public = $newPublic WHERE pk = $messagePkEsc AND profile_pk = $profilePk LIMIT 1");

echo json_encode([
    "status" => "ok",
    "public" => $newPublic,
    "public_slug" => MessagePublic::encodeId($messagePk),
    "url" => MessagePublic::publicUrl($messagePk, true),
]);
