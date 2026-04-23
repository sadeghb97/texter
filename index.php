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
.copy-btn--copied{
    background:#198754 !important; /* close to Bootstrap success */
    border-color:#198754 !important;
    color:#fff !important;
    font-weight:700;
}
</style>
</head>
<body>

<nav class="navbar bg-white shadow-sm">
<div class="container d-flex justify-content-between align-items-center">
<span class="navbar-brand mb-0 h1"><?php echo $username; ?></span>
<a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
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
const pageLimit = 5;

function loadMessages(page = 1) {
    currentPage = page;
    fetch(`api/get_messages.php?page=${page}&limit=${pageLimit}`)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById("messages");
        container.innerHTML = "";

        if (data?.error) {
            container.innerHTML = `<div class="alert alert-warning mb-2">${data.error}</div>`;
            renderPagination(0);
            return;
        }

        data.messages.forEach(msg => {
            container.innerHTML += `
            <div class="message-box">
                <div class="d-flex justify-content-between">
                    <small>${msg.author}</small>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyText(this, '${msg.text.replace(/'/g,"\\'")}')">Copy</button>
                </div>
                <div>${msg.text}</div>
            </div>`;
        });

        renderPagination(Number(data.total_pages || 0));
    });
}

function renderPagination(total) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";
    if (!total || total <= 1) return;

    if (currentPage > total) currentPage = total;
    const windowSize = 7;
    const half = Math.floor(windowSize / 2);
    let start = Math.max(1, currentPage - half);
    let end = Math.min(total, start + windowSize - 1);
    start = Math.max(1, end - windowSize + 1);

    const addItem = (label, page, { disabled = false, active = false } = {}) => {
        const liClass = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`.trim();
        const safeOnClick = disabled ? 'return false;' : `loadMessages(${page}); return false;`;
        pagination.innerHTML += `
            <li class="${liClass}">
                <a class="page-link" href="#" onclick="${safeOnClick}">${label}</a>
            </li>`;
    };

    addItem('«', 1, { disabled: currentPage === 1 });
    addItem('‹', Math.max(1, currentPage - 1), { disabled: currentPage === 1 });

    if (start > 1) {
        addItem('1', 1, { active: currentPage === 1 });
        if (start > 2) pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
    }

    for (let i = start; i <= end; i++) {
        addItem(String(i), i, { active: i === currentPage });
    }

    if (end < total) {
        if (end < total - 1) pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
        addItem(String(total), total, { active: currentPage === total });
    }

    addItem('›', Math.min(total, currentPage + 1), { disabled: currentPage === total });
    addItem('»', total, { disabled: currentPage === total });
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

async function copyText(btn, text){
    const originalText = btn?.textContent ?? "Copy";
    const originalDisabled = btn?.disabled ?? false;
    const originalClassName = btn?.className ?? "";

    const setState = (label, disabled) => {
        if (!btn) return;
        btn.textContent = label;
        btn.disabled = disabled;
    };

    try {
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(text);
        } else {
            const ta = document.createElement("textarea");
            ta.value = text;
            ta.style.position = "fixed";
            ta.style.top = "-9999px";
            ta.style.left = "-9999px";
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            const ok = document.execCommand("copy");
            document.body.removeChild(ta);
            if (!ok) throw new Error("Copy failed");
        }

        setState("Copied!", true);
        if (btn) btn.classList.add("copy-btn--copied");
        setTimeout(() => {
            if (btn) btn.className = originalClassName;
            setState(originalText, originalDisabled);
        }, 3000);
    } catch (e) {
        setState("Failed", true);
        setTimeout(() => {
            if (btn) btn.className = originalClassName;
            setState(originalText, originalDisabled);
        }, 1500);
    }
}

loadMessages();
</script>
</body>
</html>
