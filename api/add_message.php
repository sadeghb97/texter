<?php
include '../lib/library.php';
$conn = new TexterConnection();

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION[appSessionKey('user_id')])) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"),true);
$rawText = is_array($data) && array_key_exists('text', $data) ? (string)$data['text'] : '';
$rawText = trim($rawText);
if ($rawText === '') {
    http_response_code(400);
    echo json_encode(["error" => "text_required"]);
    exit;
}

$profilePkInput = null;
if (is_array($data) && array_key_exists('profile_pk', $data)) {
    $profilePkInput = $data['profile_pk'];
}
if ($profilePkInput === null) {
    http_response_code(400);
    echo json_encode(["error" => "profile_pk_required"]);
    exit;
}

$text = $conn->real_escape_string($rawText);
$createdAt = time();
$authorPk = (int)$_SESSION[appSessionKey('user_id')];

// Normalize profile_pk to a unique list of positive ints.
$profilePks = [];
if (is_array($profilePkInput)) {
    foreach ($profilePkInput as $v) {
        if (is_int($v)) $id = $v;
        else if (is_string($v) && $v !== '' && ctype_digit($v)) $id = (int)$v;
        else if (is_float($v)) $id = (int)$v;
        else continue;
        if ($id > 0) $profilePks[] = $id;
    }
} else {
    if (is_int($profilePkInput)) $id = $profilePkInput;
    else if (is_string($profilePkInput) && $profilePkInput !== '' && ctype_digit($profilePkInput)) $id = (int)$profilePkInput;
    else if (is_float($profilePkInput)) $id = (int)$profilePkInput;
    else $id = 0;
    if ($id > 0) $profilePks[] = $id;
}

$profilePks = array_values(array_unique($profilePks));
if (count($profilePks) === 0) {
    http_response_code(400);
    echo json_encode(["error" => "profile_pk_invalid"]);
    exit;
}

$inserted = 0;
foreach ($profilePks as $profilePk) {
    $profilePk = (int)$profilePk;
    if ($profilePk <= 0) continue;
    $conn->query("INSERT INTO messages (text, author_pk, profile_pk, created_at) VALUES ('$text',$authorPk, $profilePk, $createdAt)");
    if ($conn->affected_rows > 0) $inserted++;
}

echo json_encode(["status" => "ok", "inserted" => $inserted]);
?>