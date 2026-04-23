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
    min-width: 85px;
    white-space: nowrap;
    flex: 0 0 auto;
}

@media (max-width: 420px){
    .bottom-bar{
        padding: 10px 8px;
    }
    .bottom-bar__send{
        min-width: 108px;
        padding: .45rem .65rem;
        font-size: .95rem;
    }
    .app-footer .page-link{
        padding: .45rem .65rem;
        font-size: .95rem;
    }
    .app-footer .page-item + .page-item{
        margin-left: .25rem;
    }
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

/* Purple action button for "Message" */
.btn-purple{
    background: #7c3aed;
    border-color: #7c3aed;
    color: #fff;
}
.btn-purple:hover{
    background: #6d28d9;
    border-color: #6d28d9;
    color: #fff;
}

/* Recipient autocomplete */
.recipient-autocomplete{
    position: relative;
}
.recipient-header-row{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
}
.send-self-switch{
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .25rem .5rem;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.45);
    border: 1px solid var(--border);
    color: rgba(226, 232, 240, 0.9);
    user-select: none;
    white-space: nowrap;
}
.send-self-switch .form-check-input{
    margin: 0;
}
.send-self-switch .form-check-input:focus{
    box-shadow: 0 0 0 .2rem rgba(59, 130, 246, 0.25);
}
.send-self-switch .form-check-label{
    margin: 0;
    font-size: .9rem;
    color: rgba(226, 232, 240, 0.85);
}
.recipient-suggestions{
    position: absolute;
    left: 0;
    right: 0;
    margin-top: .1rem;
    background: rgba(15, 23, 42, 0.98);
    border: 1px solid var(--border);
    border-radius: .75rem;
    max-height: 220px;
    overflow: auto;
    z-index: 1080; /* above modal body */
}
.recipient-suggestions .list-group-item{
    background: transparent;
    border-color: rgba(148, 163, 184, 0.12);
    color: var(--text);
    cursor: pointer;
}
.recipient-suggestions .list-group-item:hover{
    background: rgba(59, 130, 246, 0.15);
}
.recipient-suggestions .list-group-item.active,
.recipient-suggestions .list-group-item:focus{
    background: rgba(59, 130, 246, 0.25);
    outline: none;
}
.recipient-tags{
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    margin: 0;
}
.recipient-tags-wrap{
    margin-top: .5rem;
    padding: .6rem .65rem;
    border-radius: .75rem;
    background: rgba(15, 23, 42, 0.45);
    border: 1px solid var(--border);
    min-height: 44px;
}
.recipient-tags:empty::before{
    content: "No recipients selected";
    color: rgba(226, 232, 240, 0.55);
    font-size: .9rem;
}
.recipient-tag{
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .55rem;
    border-radius: 999px;
    background: rgba(124, 58, 237, 0.18);
    border: 1px solid rgba(124, 58, 237, 0.35);
    color: #e9d5ff;
    font-size: .9rem;
}
.recipient-tag button{
    all: unset;
    cursor: pointer;
    color: #e9d5ff;
    opacity: .9;
    padding: 0 .2rem;
    line-height: 1;
}
.recipient-tag button:hover{ opacity: 1; }
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
            <div class="d-flex align-items-center gap-2 flex-wrap bottom-bar__inner">
                <nav class="bottom-bar__pagination me-auto" aria-label="Pagination">
                    <ul class="pagination justify-content-start" id="pagination"></ul>
                </nav>

                <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                    <button
                        type="button"
                        class="btn btn-primary bottom-bar__send"
                        data-bs-toggle="modal"
                        data-bs-target="#messageModal"
                    >
                        Create
                    </button>

                    <button
                        type="button"
                        class="btn btn-purple bottom-bar__send"
                        data-bs-toggle="modal"
                        data-bs-target="#sendMessageModal"
                    >
                        Message
                    </button>
                </div>
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

<div class="modal fade" id="sendMessageModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Send Message</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="mb-3 recipient-autocomplete" id="recipientAutocomplete">
        <div class="recipient-header-row mb-1">
            <label class="form-label mb-0" for="recipientInput">Recipients</label>
            <div class="form-check form-switch send-self-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch" id="sendToSelfToggle" />
                <label class="form-check-label" for="sendToSelfToggle">Send to me</label>
            </div>
        </div>
        <input id="recipientInput" class="form-control" autocomplete="off" placeholder="Search by username or id..." />
        <div id="recipientSuggestions" class="recipient-suggestions list-group d-none" role="listbox" aria-label="Recipient suggestions"></div>
        <div class="form-text" style="color: rgba(226, 232, 240, 0.65);">
            Select one or more recipients.
        </div>
        <div class="recipient-tags-wrap" aria-label="Selected recipients">
            <div id="recipientTags" class="recipient-tags"></div>
        </div>
    </div>
    <textarea id="sendMessageInput" class="form-control" rows="4" placeholder="Type your message..."></textarea>
</div>
<div class="modal-footer">
<button id="pasteFromClipboardSendBtn" type="button" class="btn btn-outline-secondary" onclick="pasteFromClipboardSend()">
    Paste from clipboard
</button>
<button id="sendToUsersBtn" type="button" class="btn btn-purple" onclick="sendToUsers()" disabled>Send</button>
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

// --- Send-to-users modal helpers ---
function getSendModalInstance() {
    const el = document.getElementById("sendMessageModal");
    if (!el) return null;
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

function getSendMessageText() {
    const el = document.getElementById("sendMessageInput");
    return (el?.value ?? "");
}

function setSendMessageText(nextText, { focus = true } = {}) {
    const el = document.getElementById("sendMessageInput");
    if (!el) return;
    el.value = nextText;
    if (focus) el.focus();
    updateSendToUsersButtonState();
}

const selectedRecipients = new Map(); // id -> {id, username}

function updateRecipientTags() {
    const tagsEl = document.getElementById("recipientTags");
    if (!tagsEl) return;
    const items = Array.from(selectedRecipients.values());
    tagsEl.innerHTML = items.map(u => {
        const safeName = String(u.username ?? u.id).replaceAll("&", "&amp;").replaceAll("<", "&lt;").replaceAll(">", "&gt;");
        return `
            <span class="recipient-tag" data-id="${u.id}">
                <span>${safeName}</span>
                <button type="button" aria-label="Remove recipient" onclick="removeRecipient(${u.id})">×</button>
            </span>
        `;
    }).join("");
    updateSendToUsersButtonState();
}

function removeRecipient(id) {
    selectedRecipients.delete(Number(id));
    updateRecipientTags();
}

function updateSendToUsersButtonState() {
    const btn = document.getElementById("sendToUsersBtn");
    if (!btn) return;
    const hasText = getSendMessageText().trim().length > 0;
    const hasRecipients = selectedRecipients.size > 0;
    const sendToSelf = !!document.getElementById("sendToSelfToggle")?.checked;
    btn.disabled = !(hasText && (hasRecipients || sendToSelf));
}

let usersFetchTimer = null;
async function fetchUserSuggestions(query) {
    try {
        const res = await fetch(`api/get_users.php?q=${encodeURIComponent(query)}&limit=12`);
        const data = await res.json();
        if (data?.error) return [];
        return Array.isArray(data?.users) ? data.users : [];
    } catch (_) {
        return [];
    }
}

const recipientSuggestState = {
    items: [],
    activeIndex: -1,
};

function isSuggestionsOpen() {
    const box = document.getElementById("recipientSuggestions");
    return !!box && !box.classList.contains("d-none");
}

function setActiveSuggestion(index) {
    const box = document.getElementById("recipientSuggestions");
    if (!box) return;
    const buttons = Array.from(box.querySelectorAll("button[data-recipient-id]"));
    if (!buttons.length) {
        recipientSuggestState.activeIndex = -1;
        return;
    }
    const next = Math.max(0, Math.min(index, buttons.length - 1));
    recipientSuggestState.activeIndex = next;
    buttons.forEach((b, i) => b.classList.toggle("active", i === next));
    buttons[next]?.scrollIntoView({ block: "nearest" });
}

function renderSuggestions(users) {
    const box = document.getElementById("recipientSuggestions");
    if (!box) return;
    if (!users.length) {
        box.classList.add("d-none");
        box.innerHTML = "";
        recipientSuggestState.items = [];
        recipientSuggestState.activeIndex = -1;
        return;
    }

    const filtered = users
        .filter(u => u && typeof u.id === "number" && u.id > 0 && !selectedRecipients.has(u.id))
        .slice(0, 12)
        .map(u => ({ id: u.id, username: u.username ?? "" }));

    recipientSuggestState.items = filtered;
    recipientSuggestState.activeIndex = -1;

    const escapeHtml = (value) => String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");

    const rows = filtered.map((u, idx) => {
        const safeName = escapeHtml(u.username || u.id);
        return `<button
            type="button"
            class="list-group-item list-group-item-action"
            role="option"
            id="recipient-option-${idx}"
            data-recipient-id="${u.id}"
            data-recipient-username="${escapeHtml(u.username)}"
        >${safeName} <small style="color: rgba(226,232,240,.55)">#${u.id}</small></button>`;
    });

    box.innerHTML = rows.join("");
    if (box.innerHTML.trim() === "") {
        box.classList.add("d-none");
        recipientSuggestState.items = [];
        recipientSuggestState.activeIndex = -1;
        return;
    }
    box.classList.remove("d-none");
    setActiveSuggestion(0);
}

function selectRecipient(id, username) {
    const rid = Number(id);
    if (!rid || rid <= 0) return;
    selectedRecipients.set(rid, { id: rid, username: String(username || rid) });
    const input = document.getElementById("recipientInput");
    if (input) input.value = "";
    renderSuggestions([]);
    updateRecipientTags();
    document.getElementById("recipientInput")?.focus();
}

async function pasteFromClipboardSend() {
    const btn = document.getElementById("pasteFromClipboardSendBtn");
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
        const current = getSendMessageText();
        const combined = (current && clip) ? (current + "\n" + clip) : (current + clip);
        setSendMessageText(combined);
    } catch (e) {
        try {
            const manual = window.prompt("Paste your clipboard text here:");
            if (manual != null) {
                const current = getSendMessageText();
                const combined = (current && manual) ? (current + "\n" + manual) : (current + manual);
                setSendMessageText(combined);
            }
        } catch (_) {}
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = originalLabel;
        }
        updateSendToUsersButtonState();
    }
}

function sendToUsers() {
    const text = getSendMessageText().trim();
    const sendToSelf = !!document.getElementById("sendToSelfToggle")?.checked;
    const recipients = Array.from(selectedRecipients.keys());
    if (sendToSelf) recipients.push(<?php echo (int)$userId; ?>);
    const uniqueRecipients = Array.from(new Set(recipients)).filter((id) => Number(id) > 0);

    if (!text || uniqueRecipients.length === 0) {
        updateSendToUsersButtonState();
        return;
    }

    const sendBtn = document.getElementById("sendToUsersBtn");
    const originalSendLabel = sendBtn?.textContent ?? "Send";
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.textContent = "Sending...";
    }

    fetch('api/add_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ text, profile_pk: uniqueRecipients })
    })
    .then(async (res) => {
        let data = null;
        try { data = await res.clone().json(); } catch (_) {}
        if (!res.ok) throw new Error(data?.error || "Send failed");
        if (data?.error) throw new Error(data.error);
    })
    .then(() => {
        setSendMessageText("", { focus: false });
        selectedRecipients.clear();
        updateRecipientTags();
        const selfToggle = document.getElementById("sendToSelfToggle");
        if (selfToggle) selfToggle.checked = false;
        const modal = getSendModalInstance();
        modal?.hide();
    })
    .catch(() => {
        if (sendBtn) sendBtn.disabled = false;
    })
    .finally(() => {
        if (sendBtn) sendBtn.textContent = originalSendLabel;
        updateSendToUsersButtonState();
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

// Wire up send-to-users modal behavior
(() => {
    const modalEl = document.getElementById("sendMessageModal");
    const recipientInput = document.getElementById("recipientInput");
    const messageInput = document.getElementById("sendMessageInput");
    const suggestionBox = document.getElementById("recipientSuggestions");
    const sendToSelfToggle = document.getElementById("sendToSelfToggle");

    if (recipientInput) {
        recipientInput.addEventListener("input", () => {
            const q = recipientInput.value.trim();
            if (usersFetchTimer) clearTimeout(usersFetchTimer);
            if (q.length < 1) {
                renderSuggestions([]);
                return;
            }
            usersFetchTimer = setTimeout(async () => {
                const users = await fetchUserSuggestions(q);
                renderSuggestions(users);
            }, 200);
        });
        recipientInput.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                renderSuggestions([]);
                return;
            }

            if (e.key === "ArrowDown") {
                if (!isSuggestionsOpen()) {
                    const q = recipientInput.value.trim();
                    if (q.length >= 1) {
                        e.preventDefault();
                        fetchUserSuggestions(q).then(renderSuggestions);
                    }
                    return;
                }
                e.preventDefault();
                setActiveSuggestion((recipientSuggestState.activeIndex < 0 ? 0 : recipientSuggestState.activeIndex + 1));
                return;
            }

            if (e.key === "ArrowUp") {
                if (!isSuggestionsOpen()) return;
                e.preventDefault();
                setActiveSuggestion((recipientSuggestState.activeIndex < 0 ? 0 : recipientSuggestState.activeIndex - 1));
                return;
            }

            if (e.key === "Enter") {
                if (!isSuggestionsOpen()) return;
                const idx = recipientSuggestState.activeIndex;
                const item = recipientSuggestState.items?.[idx];
                if (!item) return;
                e.preventDefault();
                selectRecipient(item.id, item.username);
                return;
            }
        });
    }

    if (suggestionBox) {
        suggestionBox.addEventListener("click", (e) => {
            const btn = e.target?.closest?.("button[data-recipient-id]");
            if (!btn) return;
            const id = Number(btn.getAttribute("data-recipient-id"));
            const username = btn.getAttribute("data-recipient-username") || "";
            selectRecipient(id, username);
        });
        suggestionBox.addEventListener("mousemove", (e) => {
            const btn = e.target?.closest?.("button[data-recipient-id]");
            if (!btn) return;
            const buttons = Array.from(suggestionBox.querySelectorAll("button[data-recipient-id]"));
            const idx = buttons.indexOf(btn);
            if (idx >= 0) setActiveSuggestion(idx);
        });
    }

    if (messageInput) {
        messageInput.addEventListener("input", updateSendToUsersButtonState);
        messageInput.addEventListener("keydown", (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === "Enter") sendToUsers();
        });
    }

    if (sendToSelfToggle) {
        sendToSelfToggle.addEventListener("change", updateSendToUsersButtonState);
    }

    // Close suggestions on outside click within modal
    document.addEventListener("click", (e) => {
        const box = document.getElementById("recipientSuggestions");
        const wrap = document.getElementById("recipientAutocomplete");
        if (!box || !wrap) return;
        if (wrap.contains(e.target)) return;
        renderSuggestions([]);
    });

    if (modalEl) {
        modalEl.addEventListener("shown.bs.modal", () => {
            updateRecipientTags();
            updateSendToUsersButtonState();
            document.getElementById("recipientInput")?.focus();
        });
        modalEl.addEventListener("hidden.bs.modal", () => {
            // reset UI
            selectedRecipients.clear();
            updateRecipientTags();
            renderSuggestions([]);
            const ri = document.getElementById("recipientInput");
            if (ri) ri.value = "";
            const selfToggle = document.getElementById("sendToSelfToggle");
            if (selfToggle) selfToggle.checked = false;
            setSendMessageText("", { focus: false });
        });
    }

    updateSendToUsersButtonState();
})();
</script>
</body>
</html>
