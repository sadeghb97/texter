<?php
include '../lib/library.php';
$conn = new TexterConnection();

$data = json_decode(file_get_contents("php://input"),true);
$text = $conn->real_escape_string($data['text']);
$createdAt = time();
$authorPk = 1;

$conn->query("INSERT INTO messages (text, author_pk, created_at) VALUES ('$text',$authorPk, $createdAt)");
echo json_encode(["status"=>"ok"]);
?>