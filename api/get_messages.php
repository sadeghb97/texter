<?php
include '../lib/library.php';
$conn = new TexterConnection();

$page = 1;
$limit = 5;
$offset = ($page-1) * $limit;

$total = $conn->query("SELECT COUNT(*) as cnt FROM messages")->fetch_assoc()['cnt'];
$total_pages = ceil($total / $limit);

$res = $conn->query("SELECT * FROM messages ORDER BY pk DESC LIMIT $limit OFFSET $offset");

$messages=[];
while($row=$res->fetch_assoc()){
    $messages[]=[
        "text" => $row['text'],
        "author" => "User"
    ];
}

echo json_encode(["messages"=>$messages,"total_pages"=>$total_pages]);
?>