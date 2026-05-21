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

$wantPublic = null;
if (array_key_exists('public', $data)) {
    $wantPublic = (bool)(int)$data['public'];
} elseif (array_key_exists('is_public', $data)) {
    $wantPublic = (bool)$data['is_public'];
}

if ($wantPublic === null) {
    http_response_code(400);
    echo json_encode(["error" => "public_required"]);
    exit;
}

$profilePk = (int)$auth->currentUserId();
$messagePkEsc = (int)$messagePk;

$res = $conn->query(
    "SELECT pk, public, slug, password FROM messages WHERE pk = $messagePkEsc AND profile_pk = $profilePk LIMIT 1"
);
$row = $res ? $res->fetch_assoc() : null;
if (!$row) {
    http_response_code(404);
    echo json_encode(["error" => "not_found"]);
    exit;
}

$slugProvided = array_key_exists('slug', $data);
$newSlug = $slugProvided ? MessagePublic::normalizeSlug((string)$data['slug']) : (string)($row['slug'] ?? '');

if ($slugProvided) {
    if ($newSlug === '' || !MessagePublic::isValidSlug($newSlug)) {
        http_response_code(400);
        echo json_encode(["error" => "slug_invalid"]);
        exit;
    }

    $slugEsc = $conn->real_escape_string($newSlug);
    $dupRes = $conn->query(
        "SELECT pk FROM messages
         WHERE profile_pk = $profilePk AND slug = '$slugEsc' AND pk != $messagePkEsc
         LIMIT 1"
    );
    if ($dupRes && $dupRes->fetch_assoc()) {
        http_response_code(409);
        echo json_encode(["error" => "slug_taken", "message" => "This slug is already in use."]);
        exit;
    }
}

$newPublic = $wantPublic ? 1 : 0;
$slugSql = 'NULL';
if ($newSlug !== '') {
    $slugSql = "'" . $conn->real_escape_string($newSlug) . "'";
}

$passwordSql = null;
if (array_key_exists('clear_password', $data) && $data['clear_password']) {
    $passwordSql = 'NULL';
} elseif (array_key_exists('password', $data)) {
    $plain = trim((string)$data['password']);
    if ($plain === '') {
        $passwordSql = 'NULL';
    } else {
        if (strlen($plain) < 4) {
            http_response_code(400);
            echo json_encode(["error" => "password_too_short"]);
            exit;
        }
        $passwordSql = "'" . $conn->real_escape_string(password_hash($plain, PASSWORD_DEFAULT)) . "'";
    }
}

$setParts = ["public = $newPublic", "slug = $slugSql"];
if ($passwordSql !== null) {
    $setParts[] = "password = $passwordSql";
}
$setClause = implode(', ', $setParts);

$conn->query("UPDATE messages SET $setClause WHERE pk = $messagePkEsc AND profile_pk = $profilePk LIMIT 1");

$hasPassword = false;
if ($passwordSql === 'NULL') {
    $hasPassword = false;
} elseif ($passwordSql !== null) {
    $hasPassword = true;
} else {
    $hasPassword = MessagePublic::messageHasPassword($row['password'] ?? null);
}

$finalSlug = $newSlug;
echo json_encode([
    "status" => "ok",
    "public" => $newPublic,
    "slug" => $finalSlug,
    "profile_slug" => MessagePublic::encodeId($profilePk),
    "has_password" => $hasPassword,
    "url" => $finalSlug !== '' ? MessagePublic::messageUrl($profilePk, $finalSlug, true) : '',
]);
