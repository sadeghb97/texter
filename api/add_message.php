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

$text = $conn->real_escape_string($rawText);
$createdAt = time();
$authorPk = (int)$_SESSION['user_id'];

$conn->query("INSERT INTO messages (text, author_pk, created_at) VALUES ('$text',$authorPk, $createdAt)");
echo json_encode(["status"=>"ok"]);
?>