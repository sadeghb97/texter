<?php
include '../lib/library.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit;
}

$conn = new TexterConnection();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
if ($limit < 1) $limit = 5;
if ($limit > 50) $limit = 50;

$offset = ($page - 1) * $limit;
$authorPk = (int)$_SESSION['user_id'];

$totalRow = $conn
    ->query("SELECT COUNT(*) as cnt FROM messages WHERE author_pk = $authorPk")
    ->fetch_assoc();
$total = (int)($totalRow['cnt'] ?? 0);
$total_pages = (int)ceil($total / $limit);

$res = $conn->query(
    "SELECT pk, text, author_pk, created_at
     FROM messages
     WHERE author_pk = $authorPk
     ORDER BY pk DESC
     LIMIT $limit OFFSET $offset"
);

$messages=[];
while($row=$res->fetch_assoc()){
    $messages[]=[
        "text" => $row['text'],
        "author" => "User",
        "pk" => (int)$row['pk'],
        "created_at" => isset($row['created_at']) ? (int)$row['created_at'] : null
    ];
}

echo json_encode([
    "messages" => $messages,
    "total_pages" => $total_pages,
    "page" => $page,
    "limit" => $limit
]);
?>