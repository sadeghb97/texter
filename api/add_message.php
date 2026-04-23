<?php
include '../lib/library.php';
$conn = new TexterConnection();

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
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

$profilePk = null;
if (is_array($data) && array_key_exists('profile_pk', $data)) {
    $profilePk = (int)$data['profile_pk'];
}
if (empty($profilePk)) {
    http_response_code(400);
    echo json_encode(["error" => "profile_pk_required"]);
    exit;
}

$text = $conn->real_escape_string($rawText);
$createdAt = time();
$authorPk = (int)$_SESSION['user_id'];

if ($profilePk !== $authorPk) {
    http_response_code(403);
    echo json_encode(["error" => "forbidden_profile_pk"]);
    exit;
}

$conn->query("INSERT INTO messages (text, author_pk, profile_pk, created_at) VALUES ('$text',$authorPk, $profilePk, $createdAt)");
echo json_encode(["status"=>"ok"]);
?>