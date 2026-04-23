<?php
require 'lib/library.php';
requireLogin();

$page_pk = 1;
$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
.message-box {
    background:white;
    border-radius:12px;
    padding:12px;
    margin-bottom:10px;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}
.fab {
    position:fixed;
    bottom:20px;
    right:20px;
    width:60px;
    height:60px;
    border-radius:50%;
    font-size:28px;
}
</style>
</head>
<body>

<nav class="navbar bg-white shadow-sm">
<div class="container">
<span class="navbar-brand mb-0 h1"><?php echo $username; ?></span>
</div>
</nav>

<div class="container mt-3">
<div id="messages"></div>
<ul class="pagination justify-content-center" id="pagination"></ul>
</div>

<button class="btn btn-primary fab" data-bs-toggle="modal" data-bs-target="#messageModal">+</button>

<div class="modal fade" id="messageModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">New Message</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<textarea id="messageInput" class="form-control"></textarea>
</div>
<div class="modal-footer">
<button class="btn btn-primary" onclick="sendMessage()">Send</button>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentPage = 1;

function loadMessages(page = 1) {
    currentPage = page;
    fetch(`api/get_messages.php?page=${page}`)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById("messages");
        container.innerHTML = "";

        data.messages.forEach(msg => {
            container.innerHTML += `
            <div class="message-box">
                <div class="d-flex justify-content-between">
                    <small>${msg.author}</small>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyText('${msg.text.replace(/'/g,"\\'")}')">Copy</button>
                </div>
                <div>${msg.text}</div>
            </div>`;
        });

        renderPagination(data.total_pages);
    });
}

function renderPagination(total) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";
    for (let i=1;i<=total;i++) {
        pagination.innerHTML += `<li class="page-item ${i===currentPage?'active':''}">
        <a class="page-link" href="#" onclick="loadMessages(${i})">${i}</a></li>`;
    }
}

function sendMessage() {
    const text = document.getElementById("messageInput").value;
    fetch('api/add_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({text})
    }).then(()=> {
        document.getElementById("messageInput").value="";
        loadMessages(currentPage);
    });
}

function copyText(text){
    navigator.clipboard.writeText(text);
}

loadMessages();
</script>

</body>
</html>
