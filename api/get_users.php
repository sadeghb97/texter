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

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if ($limit < 1) $limit = 10;
if ($limit > 30) $limit = 30;

$currentUserId = (int)$auth->currentUserId();

// If query is empty, return empty list to avoid dumping users.
if ($q === '') {
    echo json_encode(["users" => []]);
    exit;
}

$qEsc = $conn->real_escape_string($q);
$like = '%' . $qEsc . '%';
$likeEsc = $conn->real_escape_string($like);

$idFilter = '';
if (ctype_digit($q)) {
    $id = (int)$q;
    if ($id > 0) $idFilter = " OR u.id = $id ";
}

$res = $conn->query(
    "SELECT u.id, u.username
     FROM users u
     WHERE u.id <> $currentUserId
       AND (u.username LIKE '$likeEsc' $idFilter)
     ORDER BY u.username ASC
     LIMIT $limit"
);

$users = [];
while ($row = $res->fetch_assoc()) {
    $users[] = [
        "id" => (int)($row['id'] ?? 0),
        "username" => $row['username'] ?? null,
    ];
}

echo json_encode(["users" => $users]);
?>
