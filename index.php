<?php
require 'lib/library.php';

use Avetify\Themes\Main\ThemesManager;

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
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
<?php
ThemesManager::importBootstrapCSS();
?>
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
/* --- Icon buttons (unified styling) --- */
.icon-btn{
    --icon-btn-bg: rgba(15, 23, 42, 0.32);
    --icon-btn-bg-hover: rgba(59, 130, 246, 0.14);
    --icon-btn-border: rgba(148, 163, 184, 0.22);
    --icon-btn-border-hover: rgba(96, 165, 250, 0.55);
    --icon-btn-icon: #dbc2c2;

    appearance: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .35rem;
    padding: .5rem .7rem;
    border-radius: .6rem;
    background: var(--icon-btn-bg);
    border: 1px solid var(--icon-btn-border);
    color: var(--text);
    text-decoration: none;
    line-height: 1;
    transition: background .15s ease, border-color .15s ease, transform .05s ease;
}
.icon-btn:hover{
    background: var(--icon-btn-bg-hover);
    border-color: var(--icon-btn-border-hover);
    color: var(--text);
    text-decoration: none;
}
.icon-btn:active{ transform: translateY(1px); }
.icon-btn:focus-visible{
    outline: none;
    box-shadow: 0 0 0 .2rem rgba(59, 130, 246, 0.25);
}
.icon-btn img{
    width: 18px;
    height: 18px;
    display: block;
    /* make SVGs with stroke/fill colors consistent */
    filter: brightness(0) saturate(100%) invert(86%) sepia(6%) saturate(891%) hue-rotate(309deg) brightness(90%) contrast(88%);
}

/* Header icons (top navbar) */
.icon-btn--header{
    padding: .55rem .75rem;
    border-radius: .65rem;
}
.icon-btn--header img{
    width: 20px;
    height: 20px;
}

/* Message box icons (per-message actions) */
.icon-btn--msg{
    padding: .5rem .8rem;
    border-radius: .55rem;
    min-width: 44px; /* comfortable touch target on mobile */
}

/* Copy "copied" state: different background + icon color */
.copy-btn--copied{
    background: rgba(25, 135, 84, 0.22) !important;
    border-color: rgba(25, 135, 84, 0.55) !important;
}
.copy-btn--copied:hover{
    /* no hover effect while copied */
    background: rgba(25, 135, 84, 0.22) !important;
    border-color: rgba(25, 135, 84, 0.55) !important;
}
.copy-btn--copied img{
    /* green-ish */
    filter: brightness(0) saturate(100%) invert(64%) sepia(54%) saturate(463%) hue-rotate(89deg) brightness(92%) contrast(92%);
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

.message-actions{
    gap: .65rem !important;
}
.message-actions .btn:not(.icon-btn){
    min-width: 78px;
}

/* Settings modal: compact & mobile-friendly */
#settingsModal .modal-dialog{
    max-width: 420px;
}
#settingsModal .list-group-item{
    background: rgba(15, 23, 42, 0.55);
    border: 0 !important;
    color: var(--text);
}
#settingsModal .list-group-item:hover{
    background: rgba(15, 23, 42, 0.72);
}
#settingsModal .list-group-item:focus{
    outline: none;
    box-shadow: none;
}
#settingsModal .settings-view-title{
    font-size: 1.02rem;
    margin: 0;
}
#settingsModal .settings-subtitle{
    color: rgba(226, 232, 240, 0.65);
    font-size: .92rem;
}
</style>
</head>
<body>

<div class="app-shell">
    <nav class="navbar bg-white shadow-sm app-header">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1"><?php echo $username; ?></span>
            <div class="d-flex align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-sm icon-btn icon-btn--header"
                    data-bs-toggle="modal"
                    data-bs-target="#settingsModal"
                    aria-label="Settings"
                    title="Settings"
                >
                    <img src="assets/img/icons/setting.svg" alt="" aria-hidden="true">
                </button>
                <a class="btn btn-sm icon-btn icon-btn--header" href="logout.php" aria-label="Logout" title="Logout">
                    <img src="assets/img/icons/logout.svg" alt="" aria-hidden="true">
                </a>
            </div>
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

<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="settingsModalTitle">Settings</h5>
    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <!-- Menu view -->
    <div id="settingsMenuView">
        <div class="settings-subtitle mb-2">Choose an option</div>
        <div class="list-group" aria-label="Settings menu">
            <button type="button" class="list-group-item list-group-item-action" data-settings-target="settings-change-password-view">
                Change Password
            </button>
            <button type="button" class="list-group-item list-group-item-action" data-settings-target="settings-notifications-view">
                Notifications
            </button>
            <button type="button" class="list-group-item list-group-item-action" data-settings-target="settings-privacy-view">
                Privacy
            </button>
        </div>
    </div>

    <!-- Change Password view -->
    <div id="settings-change-password-view" class="d-none">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <h6 class="settings-view-title">Change Password</h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-settings-back>Back</button>
        </div>

        <form id="changePasswordForm" action="#" method="post" novalidate>
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current password</label>
                <input type="password" class="form-control" id="currentPassword" autocomplete="current-password" required>
            </div>

            <div class="mb-2">
                <label for="newPassword" class="form-label">New password</label>
                <input type="password" class="form-control" id="newPassword" autocomplete="new-password" minlength="6" required>
                <div class="form-text" style="color: rgba(226, 232, 240, 0.65);">Minimum length: 6</div>
            </div>

            <div class="mb-2">
                <label for="confirmNewPassword" class="form-label">Confirm new password</label>
                <input type="password" class="form-control" id="confirmNewPassword" autocomplete="new-password" minlength="6" required>
            </div>

            <div id="changePasswordHint" class="small mt-2" style="min-height: 1.25rem;"></div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button id="changePasswordSubmit" type="submit" class="btn btn-primary" disabled>Submit</button>
            </div>
        </form>
    </div>

    <!-- Placeholder views -->
    <div id="settings-notifications-view" class="d-none">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <h6 class="settings-view-title">Notifications</h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-settings-back>Back</button>
        </div>
        <div class="settings-subtitle">Coming soon.</div>
    </div>

    <div id="settings-privacy-view" class="d-none">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
            <h6 class="settings-view-title">Privacy</h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-settings-back>Back</button>
        </div>
        <div class="settings-subtitle">Coming soon.</div>
    </div>
</div>
</div>
</div>
</div>

<?php
ThemesManager::importBootstrapJS();
?>
<script>
let currentPage = 1;
const pageLimit = 10;
const CURRENT_USER_ID = <?php echo (int)$userId; ?>;

function formatTehranDateTime(unixSeconds) {
    const ts = Number(unixSeconds);
    if (!Number.isFinite(ts) || ts <= 0) return "";
    const d = new Date(ts * 1000);
    try {
        return new Intl.DateTimeFormat("fa-IR-u-nu-latn", {
            timeZone: "Asia/Tehran",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
        }).format(d);
    } catch (_) {
        // Fallback: local timezone formatting if Intl/timeZone not available.
        return d.toLocaleString();
    }
}

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
            const dt = formatTehranDateTime(msg.created_at);
            const dtSafe = escapeHtml(dt);
            container.innerHTML += `
            <div class="message-box">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <small>
                        ${authorSafe}
                        ${dtSafe ? `<span class="ms-2" style="color: rgba(226, 232, 240, 0.55);">(${dtSafe})</span>` : ``}
                    </small>
                    <div class="d-inline-flex message-actions">
                        <button type="button" class="btn btn-sm retext-btn icon-btn icon-btn--msg" aria-label="Retext" title="Retext">
                            <img src="assets/img/icons/resend.svg" alt="" aria-hidden="true">
                        </button>
                        <button type="button" class="btn btn-sm copy-btn icon-btn icon-btn--msg" aria-label="Copy" title="Copy">
                            <img src="assets/img/icons/clipboard-copy.svg" alt="" aria-hidden="true">
                        </button>
                    </div>
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
    if (sendToSelf) recipients.push(CURRENT_USER_ID);
    const uniqueRecipients = Array.from(new Set(recipients)).filter((id) => Number(id) > 0);
    const includesSelf = uniqueRecipients.includes(CURRENT_USER_ID);

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
        if (includesSelf) loadMessages(currentPage);
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
    const originalDisabled = btn?.disabled ?? false;
    const originalClassName = btn?.className ?? "";
    const originalAriaLabel = btn?.getAttribute?.("aria-label") ?? "Copy";
    const originalTitle = btn?.getAttribute?.("title") ?? "Copy";
    const iconEl = btn?.querySelector?.("img") || null;
    const originalIconSrc = iconEl?.getAttribute?.("src") || "";

    const setState = (label, disabled) => {
        if (!btn) return;
        btn.setAttribute("aria-label", label);
        btn.setAttribute("title", label);
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
        if (iconEl) iconEl.setAttribute("src", "assets/img/icons/check.svg");
        setTimeout(() => {
            if (btn) btn.className = originalClassName;
            if (iconEl && originalIconSrc) iconEl.setAttribute("src", originalIconSrc);
            if (btn) {
                btn.setAttribute("aria-label", originalAriaLabel);
                btn.setAttribute("title", originalTitle);
                btn.disabled = originalDisabled;
            }
        }, 3000);
    } catch (e) {
        setState("Failed", true);
        setTimeout(() => {
            if (btn) btn.className = originalClassName;
            if (iconEl && originalIconSrc) iconEl.setAttribute("src", originalIconSrc);
            if (btn) {
                btn.setAttribute("aria-label", originalAriaLabel);
                btn.setAttribute("title", originalTitle);
                btn.disabled = originalDisabled;
            }
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

    // Retext button handler (event delegation).
    document.getElementById("messages")?.addEventListener("click", (e) => {
        const btn = e.target?.closest?.("button.retext-btn");
        if (!btn) return;
        const box = btn.closest(".message-box");
        const text = box?.querySelector?.(".message-text")?.innerText ?? "";
        setMessageText(text);
        const modal = getMessageModalInstance();
        modal?.show();
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

// --- Settings: Change Password validation (UI only) ---
(() => {
    const modalEl = document.getElementById("settingsModal");
    const form = document.getElementById("changePasswordForm");
    const currentEl = document.getElementById("currentPassword");
    const newEl = document.getElementById("newPassword");
    const confirmEl = document.getElementById("confirmNewPassword");
    const hintEl = document.getElementById("changePasswordHint");
    const submitBtn = document.getElementById("changePasswordSubmit");
    const menuView = document.getElementById("settingsMenuView");

    if (!modalEl || !menuView) return;
    if (!form || !newEl || !confirmEl || !hintEl || !submitBtn) return;

    const MIN_LEN = 6;
    let didAttemptSubmit = false;
    let isSubmitting = false;

    const hideAllViews = () => {
        menuView.classList.add("d-none");
        modalEl.querySelectorAll("[id^='settings-'][id$='-view']").forEach((el) => {
            el.classList.add("d-none");
        });
    };

    const showMenu = () => {
        hideAllViews();
        menuView.classList.remove("d-none");
        document.querySelector("#settingsMenuView button[data-settings-target]")?.focus();
    };

    const showView = (viewId) => {
        const view = document.getElementById(viewId);
        if (!view) return;
        hideAllViews();
        view.classList.remove("d-none");
        view.querySelector("input,button,textarea,select")?.focus();
    };

    const validate = ({ showErrors } = {}) => {
        const newVal = (newEl.value ?? "").trim();
        const confirmVal = (confirmEl.value ?? "").trim();

        let msg = "";
        let ok = true;

        if (newVal.length < MIN_LEN || confirmVal.length < MIN_LEN) {
            ok = false;
            msg = `Password must be at least ${MIN_LEN} characters.`;
        } else if (newVal !== confirmVal) {
            ok = false;
            msg = "New passwords do not match.";
        }

        submitBtn.disabled = !ok;
        if (showErrors) {
            hintEl.style.color = ok ? "rgba(34, 197, 94, 0.95)" : "rgba(248, 113, 113, 0.95)";
            hintEl.textContent = ok ? "" : msg;
        } else {
            hintEl.textContent = "";
        }
        return { ok, msg };
    };

    const reset = () => {
        didAttemptSubmit = false;
        isSubmitting = false;
        if (currentEl) currentEl.value = "";
        newEl.value = "";
        confirmEl.value = "";
        hintEl.textContent = "";
        submitBtn.disabled = true;
    };

    const onInput = () => {
        if (isSubmitting) return;
        const { ok, msg } = validate({ showErrors: didAttemptSubmit });
        if (didAttemptSubmit && !ok) {
            hintEl.style.color = "rgba(248, 113, 113, 0.95)";
            hintEl.textContent = msg;
        }
    };

    newEl.addEventListener("input", onInput);
    confirmEl.addEventListener("input", onInput);
    currentEl?.addEventListener("input", onInput);

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (isSubmitting) return;

        didAttemptSubmit = true;
        const { ok, msg } = validate({ showErrors: true });
        if (!ok) {
            hintEl.style.color = "rgba(248, 113, 113, 0.95)";
            hintEl.textContent = msg;
            return;
        }

        const currentVal = (currentEl?.value ?? "").trim();
        if (!currentVal) {
            hintEl.style.color = "rgba(248, 113, 113, 0.95)";
            hintEl.textContent = "Current password is required.";
            submitBtn.disabled = true;
            return;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        const originalLabel = submitBtn.textContent || "Submit";
        submitBtn.textContent = "Saving...";
        hintEl.textContent = "";

        fetch("api/change_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                current_password: currentVal,
                new_password: (newEl.value ?? "").trim(),
            }),
        })
        .then(async (res) => {
            let data = null;
            try { data = await res.clone().json(); } catch (_) {}
            if (!res.ok) throw new Error(data?.error || "Failed to change password");
            if (data?.error) throw new Error(data.error);
            return data;
        })
        .then(() => {
            hintEl.style.color = "rgba(34, 197, 94, 0.95)";
            hintEl.textContent = "Password changed successfully.";
            reset();
            // keep success message visible after reset()
            hintEl.style.color = "rgba(34, 197, 94, 0.95)";
            hintEl.textContent = "Password changed successfully.";
        })
        .catch((err) => {
            hintEl.style.color = "rgba(248, 113, 113, 0.95)";
            hintEl.textContent = String(err?.message || "Failed to change password");
        })
        .finally(() => {
            isSubmitting = false;
            submitBtn.textContent = originalLabel;
            if (didAttemptSubmit) {
                // re-evaluate disabled state based on inputs
                validate({ showErrors: false });
            }
        });
    });

    modalEl.addEventListener("click", (e) => {
        const targetBtn = e.target?.closest?.("button[data-settings-target]");
        if (targetBtn) {
            const viewId = targetBtn.getAttribute("data-settings-target");
            if (viewId) {
                reset();
                showView(viewId);
            }
            return;
        }

        const backBtn = e.target?.closest?.("button[data-settings-back]");
        if (backBtn) {
            showMenu();
        }
    });

    modalEl.addEventListener("shown.bs.modal", () => {
        reset();
        showMenu();
    });
    modalEl.addEventListener("hidden.bs.modal", () => {
        reset();
        showMenu();
    });
})();
</script>
</body>
</html>
