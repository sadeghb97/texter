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
html, body { height: 100%; }
body { background:#0f172a; color: #e2e8f0; overflow: hidden; }
a { color: #60a5fa; }
a:hover { color: #93c5fd; }

/* Dark theme tokens aligned with login.php */
:root{
    --app-bg: #0f172a;
    --surface: #1e293b;
    --surface-2: #172554;
    --border: rgba(148, 163, 184, 0.18);
    --text: #e2e8f0;
    --muted: #94a3b8;
}
.app-shell{
    height: 100dvh; /* better on mobile than 100vh */
    display: flex;
    flex-direction: column;
}
.app-header{
    position: sticky;
    top: 0;
    z-index: 1030; /* above content */
}
.app-header.navbar{
    background: rgba(23, 37, 84, 0.92) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border);
}
.app-header .navbar-brand{
    color: var(--text);
}
.app-header .navbar-brand:hover{
    color: #ffffff;
}
.app-header .btn-outline-danger{
    border-color: rgba(248, 113, 113, 0.55);
    color: #fecaca;
}
.app-header .btn-outline-danger:hover{
    background: rgba(248, 113, 113, 0.15);
    border-color: rgba(248, 113, 113, 0.7);
    color: #fff;
}
.app-content{
    flex: 1 1 auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 12px 0;
}
.app-footer{
    position: sticky;
    bottom: 0;
    z-index: 1030;
    background: rgba(23, 37, 84, 0.92);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid var(--border);
    box-shadow: 0 -10px 24px rgba(0,0,0,0.35);
    padding-bottom: env(safe-area-inset-bottom);
}
.bottom-bar{
    padding: 10px 10px;
}
.bottom-bar__inner{
    padding-left: .25rem;
    padding-right: .25rem;
}
.bottom-bar__pagination{
    overflow-x: auto;
    scrollbar-width: none;
    padding-left: .25rem;
    padding-right: .25rem;
}
.bottom-bar__pagination::-webkit-scrollbar{ display:none; }
.bottom-bar__pagination .pagination{
    flex-wrap: nowrap;
    margin-bottom: 0;
}
.app-footer .page-link{
    padding: .5rem .85rem; /* larger tap targets */
    font-size: 1rem;
    border-radius: .6rem;
}
.app-footer .page-item + .page-item{
    margin-left: .35rem; /* more breathing room */
}
.bottom-bar__send{
    min-width: 140px;
}
.message-box {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius:12px;
    padding:12px;
    margin-bottom:10px;
    box-shadow:0 8px 20px rgba(0,0,0,0.25);
    color: var(--text);
}
.message-box small{
    color: var(--muted);
}
.app-footer .page-link{
    background: rgba(30, 41, 59, 0.75);
    border: 1px solid var(--border);
    color: var(--text);
}
.app-footer .page-link:hover{
    background: rgba(30, 41, 59, 0.95);
    color: #ffffff;
}
.app-footer .page-item.disabled .page-link{
    background: rgba(30, 41, 59, 0.35);
    color: rgba(226, 232, 240, 0.5);
    border-color: rgba(148, 163, 184, 0.12);
}
.app-footer .page-item.active .page-link{
    background: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff;
}

/* Modal dark styling */
.modal-content{
    background: var(--surface);
    color: var(--text);
    border: 1px solid var(--border);
}
.modal-header,
.modal-footer{
    border-color: var(--border);
}
.btn-close{
    filter: invert(1) grayscale(100%);
    opacity: .85;
}
.form-control{
    background: rgba(15, 23, 42, 0.65);
    border: 1px solid var(--border);
    color: var(--text);
}
.form-control:focus{
    background: rgba(15, 23, 42, 0.75);
    border-color: rgba(96, 165, 250, 0.65);
    box-shadow: 0 0 0 .25rem rgba(59, 130, 246, 0.25);
    color: var(--text);
}
.form-control::placeholder{
    color: rgba(226, 232, 240, 0.55);
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

<div class="app-shell">
    <nav class="navbar bg-white shadow-sm app-header">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1"><?php echo $username; ?></span>
            <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
        </div>
    </nav>

    <main class="app-content" aria-label="Messages">
        <div class="container">
            <div id="messages"></div>
        </div>
    </main>

    <footer class="app-footer" aria-label="Message actions and pagination">
        <div class="container bottom-bar">
            <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap bottom-bar__inner">
                <button
                    type="button"
                    class="btn btn-primary bottom-bar__send"
                    data-bs-toggle="modal"
                    data-bs-target="#messageModal"
                >
                    Create
                </button>

                <nav class="bottom-bar__pagination ms-auto" aria-label="Pagination">
                    <ul class="pagination justify-content-end" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </footer>
</div>

<div class="modal fade" id="messageModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">New Message</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<textarea id="messageInput" class="form-control" rows="4" placeholder="Type your message..."></textarea>
</div>
<div class="modal-footer">
<button id="pasteFromClipboardBtn" type="button" class="btn btn-outline-secondary" onclick="pasteFromClipboard()">
    Paste from clipboard
</button>
<button id="sendMessageBtn" type="button" class="btn btn-primary" onclick="sendMessage()" disabled>Send</button>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentPage = 1;
const pageLimit = 10;

function getMessageModalInstance() {
    const el = document.getElementById("messageModal");
    if (!el) return null;
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

function getMessageText() {
    const el = document.getElementById("messageInput");
    return (el?.value ?? "");
}

function setMessageText(nextText, { focus = true } = {}) {
    const el = document.getElementById("messageInput");
    if (!el) return;
    el.value = nextText;
    if (focus) el.focus();
    updateSendButtonState();
}

function updateSendButtonState() {
    const btn = document.getElementById("sendMessageBtn");
    if (!btn) return;
    btn.disabled = getMessageText().trim().length === 0;
}

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

        const escapeHtml = (value) => {
            return String(value ?? "")
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        };

        data.messages.forEach(msg => {
            const authorSafe = escapeHtml(msg.author);
            const textSafe = escapeHtml(msg.text);
            container.innerHTML += `
            <div class="message-box">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <small>${authorSafe}</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary copy-btn">Copy</button>
                </div>
                <div class="message-text" style="white-space: pre-wrap;">${textSafe}</div>
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
    const text = getMessageText().trim();
    if (!text) {
        updateSendButtonState();
        return;
    }

    const sendBtn = document.getElementById("sendMessageBtn");
    const originalSendLabel = sendBtn?.textContent ?? "Send";
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.textContent = "Sending...";
    }

    fetch('api/add_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ text, profile_pk: <?php echo (int)$userId; ?> })
    })
    .then(async (res) => {
        // If API returns JSON, surface error, otherwise treat as ok on 2xx.
        try {
            const data = await res.clone().json();
            if (data?.error) throw new Error(data.error);
        } catch (_) {}
        if (!res.ok) throw new Error("Send failed");
    })
    .then(() => {
        setMessageText("", { focus: false });
        loadMessages(currentPage);
        const modal = getMessageModalInstance();
        modal?.hide();
    })
    .catch(() => {
        // keep text so user can retry
        if (sendBtn) sendBtn.disabled = false;
    })
    .finally(() => {
        if (sendBtn) sendBtn.textContent = originalSendLabel;
        updateSendButtonState();
    });
}

async function pasteFromClipboard() {
    const btn = document.getElementById("pasteFromClipboardBtn");
    const originalLabel = btn?.textContent ?? "Paste from clipboard";
    if (btn) {
        btn.disabled = true;
        btn.textContent = "Pasting...";
    }

    try {
        let clip = "";
        if (navigator.clipboard?.readText) {
            clip = await navigator.clipboard.readText();
        } else {
            throw new Error("Clipboard read not supported");
        }

        const current = getMessageText();
        const combined = (current && clip) ? (current + "\n" + clip) : (current + clip);
        setMessageText(combined);
    } catch (e) {
        // Fallback: ask user to paste manually
        try {
            const manual = window.prompt("Paste your clipboard text here:");
            if (manual != null) {
                const current = getMessageText();
                const combined = (current && manual) ? (current + "\n" + manual) : (current + manual);
                setMessageText(combined);
            }
        } catch (_) {}
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = originalLabel;
        }
        updateSendButtonState();
    }
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

// Wire up modal input behavior
(() => {
    // Copy button handler (event delegation).
    document.getElementById("messages")?.addEventListener("click", (e) => {
        const btn = e.target?.closest?.("button.copy-btn");
        if (!btn) return;
        const box = btn.closest(".message-box");
        const text = box?.querySelector?.(".message-text")?.innerText ?? "";
        copyText(btn, text);
    });

    const input = document.getElementById("messageInput");
    const modalEl = document.getElementById("messageModal");

    if (input) {
        input.addEventListener("input", updateSendButtonState);
        input.addEventListener("keydown", (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === "Enter") sendMessage();
        });
    }

    if (modalEl) {
        modalEl.addEventListener("shown.bs.modal", () => {
            updateSendButtonState();
            document.getElementById("messageInput")?.focus();
        });
        modalEl.addEventListener("hidden.bs.modal", () => {
            setMessageText("", { focus: false });
        });
    }

    updateSendButtonState();
})();
</script>
</body>
</html>
