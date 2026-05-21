<?php
require 'lib/library.php';

use Avetify\Themes\Main\ThemesManager;

$conn = new TexterConnection();
$auth = new TexterAuth();
$auth->requireLogin($conn);

$page_pk = 1;
$username = $auth->currentUsername();
$userId = $auth->currentUserId();
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
    text-decoration: none;
}
.app-header .navbar-brand:hover{
    color: #ffffff;
    text-decoration: none;
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
    /* Real mobile devices may report large safe-area insets (gesture/home bar). Clamp it. */
    padding-bottom: min(env(safe-area-inset-bottom, 0px), 16px);
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

/* Message delete icon: red hover exception */
.icon-btn--danger:hover{
    background: rgba(239, 68, 68, 0.18);
    border-color: rgba(239, 68, 68, 0.65);
}
.icon-btn--danger:focus-visible{
    box-shadow: 0 0 0 .2rem rgba(239, 68, 68, 0.25);
}
.icon-btn--danger img{
    filter: brightness(0) saturate(100%) invert(74%) sepia(21%) saturate(2557%) hue-rotate(314deg) brightness(100%) contrast(94%);
}

/* Footer (bottom bar) icons: larger + bolder */
.icon-btn--footer{
    /* stronger look than header/msg */
    --icon-btn-bg: rgba(15, 23, 42, 0.55);
    --icon-btn-bg-hover: rgba(59, 130, 246, 0.22);
    --icon-btn-border: rgba(148, 163, 184, 0.35);
    --icon-btn-border-hover: rgba(96, 165, 250, 0.75);

    padding: .7rem .95rem;
    border-radius: .85rem;
    min-width: 44px;
    min-height: 44px; /* 48px+ tap target */
}
.icon-btn--footer img{
    width: 24px;
    height: 24px;
}
.icon-btn--footer.icon-btn--footer-create{
    --icon-btn-bg: rgba(59, 130, 246, 0.22);
    --icon-btn-bg-hover: rgba(59, 130, 246, 0.32);
    --icon-btn-border: rgba(59, 130, 246, 0.55);
    --icon-btn-border-hover: rgba(59, 130, 246, 0.8);
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

/* Public message icon — active when message is public */
.icon-btn--public-active{
    --icon-btn-bg: rgba(56, 189, 248, 0.2);
    --icon-btn-bg-hover: rgba(56, 189, 248, 0.28);
    --icon-btn-border: rgba(56, 189, 248, 0.5);
    --icon-btn-border-hover: rgba(56, 189, 248, 0.72);
}
.icon-btn--public-active img{
    filter: brightness(0) saturate(100%) invert(78%) sepia(42%) saturate(900%) hue-rotate(166deg) brightness(102%) contrast(96%);
}

/* Sharing modal */
#publicMessageModal .modal-content{
    background: #1e293b;
    border: 1px solid rgba(148, 163, 184, 0.2);
    color: var(--text);
}
#publicMessageModal .modal-header,
#publicMessageModal .modal-footer{
    border-color: rgba(148, 163, 184, 0.16);
}
#publicMessageModal .btn-close{
    filter: invert(1) grayscale(100%) brightness(200%);
}
.share-modal-section{
    padding: .85rem 0;
    border-bottom: 1px solid rgba(148, 163, 184, 0.12);
}
.share-modal-section:last-child{
    border-bottom: none;
    padding-bottom: 0;
}
.share-modal-section:first-child{
    padding-top: 0;
}
.share-row{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.share-row__label{
    font-size: .92rem;
    font-weight: 600;
    margin: 0;
}
.share-row__hint{
    font-size: .78rem;
    color: var(--muted);
    margin: .15rem 0 0;
}
.share-toggle{
    position: relative;
    width: 46px;
    height: 26px;
    flex-shrink: 0;
}
.share-toggle input{
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.share-toggle__track{
    position: absolute;
    inset: 0;
    background: rgba(148, 163, 184, 0.25);
    border-radius: 999px;
    cursor: pointer;
    transition: background .2s ease;
}
.share-toggle__track::after{
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    left: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
}
.share-toggle input:checked + .share-toggle__track{
    background: #0ea5e9;
}
.share-toggle input:checked + .share-toggle__track::after{
    transform: translateX(20px);
}
.share-toggle input:focus-visible + .share-toggle__track{
    outline: 2px solid rgba(56, 189, 248, 0.55);
    outline-offset: 2px;
}
.share-field-label{
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: .4rem;
}
.share-slug-row{
    display: flex;
    gap: .45rem;
    align-items: stretch;
}
.share-slug-row .form-control{
    font-size: .9rem;
    border-radius: .55rem;
    background: rgba(15, 23, 42, 0.45);
    border-color: var(--border);
    color: var(--text);
}
.share-slug-row .form-control:focus{
    background: rgba(15, 23, 42, 0.65);
    border-color: rgba(56, 189, 248, 0.45);
    color: var(--text);
    box-shadow: 0 0 0 .15rem rgba(56, 189, 248, 0.12);
}
.share-slug-row .share-icon-btn,
.share-url-preview .share-icon-btn{
    flex: 0 0 auto;
    padding: .5rem .65rem;
    min-width: 44px;
    min-height: 44px;
}
.share-url-preview{
    display: flex;
    gap: .45rem;
    align-items: stretch;
    margin-top: .55rem;
}
.share-url-preview .form-control{
    font-size: .82rem;
    background: rgba(15, 23, 42, 0.35);
    border-color: rgba(148, 163, 184, 0.14);
    color: rgba(226, 232, 240, 0.85);
    border-radius: .5rem;
}
.share-slug-row .form-control.share-field--invalid,
.share-url-preview .form-control.share-field--invalid{
    background: rgba(239, 68, 68, 0.1) !important;
    border-color: rgba(248, 113, 113, 0.42) !important;
    color: rgba(254, 226, 226, 0.92);
}
.share-slug-row .form-control.share-field--invalid:focus,
.share-url-preview .form-control.share-field--invalid:focus{
    background: rgba(239, 68, 68, 0.14) !important;
    border-color: rgba(248, 113, 113, 0.55) !important;
    box-shadow: 0 0 0 .15rem rgba(239, 68, 68, 0.12);
}
.share-slug-row .form-control.share-field--valid,
.share-url-preview .form-control.share-field--valid{
    background: rgba(34, 197, 94, 0.1) !important;
    border-color: rgba(74, 222, 128, 0.42) !important;
    color: rgba(220, 252, 231, 0.95);
}
.share-slug-row .form-control.share-field--valid:focus,
.share-url-preview .form-control.share-field--valid:focus{
    background: rgba(34, 197, 94, 0.14) !important;
    border-color: rgba(74, 222, 128, 0.55) !important;
    box-shadow: 0 0 0 .15rem rgba(34, 197, 94, 0.12);
}
.share-badge{
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 600;
    border: 1px solid var(--border);
}
.share-badge--on{
    background: rgba(34, 197, 94, 0.12);
    border-color: rgba(34, 197, 94, 0.35);
    color: #86efac;
}
.share-badge--off{
    background: rgba(148, 163, 184, 0.1);
    color: rgba(226, 232, 240, 0.75);
}
.share-password-panel{
    margin-top: .65rem;
    padding: .75rem;
    border-radius: .6rem;
    background: rgba(15, 23, 42, 0.4);
    border: 1px solid rgba(148, 163, 184, 0.14);
}
.share-password-panel.d-none{ display: none !important; }
.share-password-panel .form-control{
    background: rgba(15, 23, 42, 0.55);
    border-color: var(--border);
    color: var(--text);
    border-radius: .5rem;
}
.share-form-error{
    font-size: .82rem;
    color: #fca5a5;
    min-height: 1.2rem;
    margin-top: .5rem;
}
.share-form-error:empty{ display: none; }
#publicMessageSaveBtn{
    min-width: 8rem;
    font-weight: 600;
    border-radius: .55rem;
}

/* Message modal: advanced-mode toggle (header, near close) */
.modal-header__actions{
    display: flex;
    align-items: center;
    gap: .35rem;
    margin-left: auto;
}
.message-advanced-toggle{
    --icon-btn-bg: transparent;
    --icon-btn-bg-hover: rgba(59, 130, 246, 0.14);
    --icon-btn-border: transparent;
    --icon-btn-border-hover: rgba(96, 165, 250, 0.45);
    padding: .35rem .45rem;
    border-radius: .5rem;
    min-width: 0;
    min-height: 0;
}
.message-advanced-toggle img,
.message-advanced-toggle svg{
    width: 18px;
    height: 18px;
    display: block;
}
.message-advanced-toggle.is-active{
    --icon-btn-bg: rgba(124, 58, 237, 0.22);
    --icon-btn-bg-hover: rgba(124, 58, 237, 0.32);
    --icon-btn-border: rgba(124, 58, 237, 0.45);
    --icon-btn-border-hover: rgba(124, 58, 237, 0.65);
}
.message-advanced-toggle.is-active img{
    filter: brightness(0) saturate(100%) invert(78%) sepia(35%) saturate(1200%) hue-rotate(228deg) brightness(102%) contrast(98%);
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
    position: relative;
    flex-shrink: 0;
    gap: .65rem !important;
}
.message-actions .btn:not(.icon-btn){
    min-width: 78px;
}
.message-actions-more{
    display: none;
}
.message-actions-menu{
    display: inline-flex;
    gap: .65rem !important;
}
@media (max-width: 575.98px){
    .message-actions-more{
        display: inline-flex;
    }
    .message-actions-menu{
        display: none !important;
        position: absolute;
        top: 50%;
        right: calc(100% + 6px);
        transform: translateY(-50%);
        z-index: 20;
        flex-direction: row;
        align-items: center;
        flex-wrap: nowrap;
        gap: .35rem !important;
        padding: .35rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: .55rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }
    .message-actions-menu.is-open{
        display: inline-flex !important;
    }
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

/* --- Messages loading overlay --- */
.messages-wrap{
    position: relative;
    min-height: 160px; /* prevents "jump" on empty state */
}
.messages-loading{
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 14px;
    z-index: 1090; /* above content, below bootstrap modals (1055+) but we want above page */
    opacity: 0;
    pointer-events: none;
    transition: opacity .16s ease;
}
.messages-loading.is-visible{
    opacity: 1;
    pointer-events: auto; /* block clicks while loading */
}
.messages-loading__backdrop{
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.62);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.messages-loading__card{
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    padding: .85rem 1rem;
    border-radius: 999px;
    background: rgba(30, 41, 59, 0.72);
    border: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 16px 40px rgba(0,0,0,0.35);
}
.messages-loading__label{
    color: rgba(226, 232, 240, 0.9);
    font-size: .98rem;
    letter-spacing: .2px;
    user-select: none;
}
.messages-loading .spinner-border{
    width: 1.15rem;
    height: 1.15rem;
    border-width: .17rem;
    color: #60a5fa;
}
@media (prefers-reduced-motion: reduce){
    .messages-loading{ transition: none; }
}
</style>
<link rel="stylesheet" href="assets/css/toast.css">
</head>
<body>

<div class="app-shell">
    <?php
    $topbarBrandText = $username;
    $topbarLoggedIn = true;
    $topbarOnTextPage = false;
    require __DIR__ . '/partials/topbar.php';
    ?>

    <main class="app-content" aria-label="Messages">
        <div class="container">
            <div class="messages-wrap" aria-busy="false" aria-live="polite">
                <div id="messages"></div>
            </div>
        </div>
    </main>

    <footer class="app-footer" aria-label="Message actions and pagination">
        <div class="container bottom-bar">
            <div class="d-flex align-items-center gap-2 flex-wrap bottom-bar__inner">
                <nav class="bottom-bar__pagination me-auto" aria-label="Pagination">
                    <ul class="pagination justify-content-start" id="pagination"></ul>
                </nav>

                <div class="d-flex align-items-center gap-3 ms-auto flex-wrap">
                    <button
                        type="button"
                        class="btn icon-btn icon-btn--footer icon-btn--footer-create"
                        data-bs-toggle="modal"
                        data-bs-target="#messageModal"
                        aria-label="Create"
                        title="Create"
                    >
                        <img src="assets/img/icons/create.svg" alt="" aria-hidden="true">
                        <span class="visually-hidden">Create</span>
                    </button>
                </div>
            </div>
        </div>
    </footer>
</div>

<div id="messagesLoading" class="messages-loading" role="status" aria-label="Loading messages" aria-hidden="true">
    <div class="messages-loading__backdrop" aria-hidden="true"></div>
    <div class="messages-loading__card">
        <span class="spinner-border" aria-hidden="true"></span>
        <span class="messages-loading__label">Loading messages…</span>
    </div>
</div>

<div class="modal fade" id="messageModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">New Message</h5>
<div class="modal-header__actions">
    <button
        type="button"
        id="messageAdvancedToggle"
        class="btn icon-btn message-advanced-toggle"
        aria-label="Advanced mode"
        aria-pressed="false"
        title="Recipients"
    >
        <img src="assets/img/icons/message.svg" alt="" aria-hidden="true">
    </button>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
</div>
<div class="modal-body">
    <div id="messageAdvancedSection" class="d-none mb-3 recipient-autocomplete">
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


<div class="modal fade" id="editMessageModal" tabindex="-1" aria-labelledby="editMessageModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="editMessageModalTitle">Edit message</h5>
    <button id="editMessageCloseX" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <textarea id="editMessageInput" class="form-control" rows="6" placeholder="Message text…"></textarea>
    <div id="editMessageError" class="small mt-2" style="min-height: 1.25rem; color: rgba(248, 113, 113, 0.95);"></div>
</div>
<div class="modal-footer">
    <button id="editMessageCancelBtn" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    <button id="editMessageSaveBtn" type="button" class="btn btn-primary">Save</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="deleteMessageModal" tabindex="-1" aria-labelledby="deleteMessageModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="deleteMessageModalTitle">Delete message</h5>
    <button id="deleteMessageCloseX" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    Are you sure?
</div>
<div class="modal-footer">
    <button id="deleteMessageCancelBtn" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    <button id="deleteMessageConfirmBtn" type="button" class="btn btn-danger">Delete</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="publicMessageModal" tabindex="-1" aria-labelledby="publicMessageModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title" id="publicMessageModalTitle">Share message</h5>
    <button id="publicMessageCloseX" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" id="publicMessageModalBody"></div>
<div class="modal-footer border-0 pt-0">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="button" id="publicMessageSaveBtn" class="btn btn-primary">Save changes</button>
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
<script src="assets/js/toast.js"></script>
<script>
let currentPage = 1;
const pageLimit = 10;
const CURRENT_USER_ID = <?php echo (int)$userId; ?>;
const APP_BASE_PATH = <?php echo json_encode(MessagePublic::appBasePath(), JSON_UNESCAPED_SLASHES); ?>;
const APP_ORIGIN = <?php echo json_encode(MessagePublic::requestOrigin(), JSON_UNESCAPED_SLASHES); ?>;

const messagesLoadingState = { count: 0 };
function setMessagesLoading(isLoading) {
    const overlay = document.getElementById("messagesLoading");
    const wrap = document.querySelector(".messages-wrap");
    if (!overlay) return;

    if (isLoading) messagesLoadingState.count += 1;
    else messagesLoadingState.count = Math.max(0, messagesLoadingState.count - 1);

    const visible = messagesLoadingState.count > 0;
    overlay.classList.toggle("is-visible", visible);
    overlay.setAttribute("aria-hidden", visible ? "false" : "true");
    if (wrap) wrap.setAttribute("aria-busy", visible ? "true" : "false");
}

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

function isMessageAdvancedMode() {
    const section = document.getElementById("messageAdvancedSection");
    return !!section && !section.classList.contains("d-none");
}

function setMessageAdvancedMode(enabled, { focusRecipient = false } = {}) {
    const section = document.getElementById("messageAdvancedSection");
    const toggle = document.getElementById("messageAdvancedToggle");
    if (!section || !toggle) return;

    const on = !!enabled;
    section.classList.toggle("d-none", !on);
    toggle.classList.toggle("is-active", on);
    toggle.setAttribute("aria-pressed", on ? "true" : "false");
    toggle.setAttribute("aria-label", on ? "Simple mode" : "Advanced mode");
    toggle.setAttribute("title", on ? "Simple mode" : "Recipients");

    if (!on) {
        selectedRecipients.clear();
        updateRecipientTags();
        renderSuggestions([]);
        const ri = document.getElementById("recipientInput");
        if (ri) ri.value = "";
        const selfToggle = document.getElementById("sendToSelfToggle");
        if (selfToggle) selfToggle.checked = false;
    } else if (focusRecipient) {
        document.getElementById("recipientInput")?.focus();
    }

    updateSendButtonState();
}

function updateSendButtonState() {
    const btn = document.getElementById("sendMessageBtn");
    if (!btn) return;
    const hasText = getMessageText().trim().length > 0;
    if (!isMessageAdvancedMode()) {
        btn.disabled = !hasText;
        return;
    }
    const hasRecipients = selectedRecipients.size > 0;
    const sendToSelf = !!document.getElementById("sendToSelfToggle")?.checked;
    btn.disabled = !(hasText && (hasRecipients || sendToSelf));
}

async function loadMessages(page = 1) {
    currentPage = page;
    setMessagesLoading(true);
    try {
        const res = await fetch(`api/get_messages.php?page=${page}&limit=${pageLimit}`);
        const data = await res.json();

        const container = document.getElementById("messages");
        if (!container) return;
        container.innerHTML = "";

        if (!res.ok) {
            const msg = (data?.error || "Failed to load messages");
            container.innerHTML = `<div class="alert alert-warning mb-2">${String(msg)}</div>`;
            renderPagination(0);
            return;
        }

        if (data?.error) {
            container.innerHTML = `<div class="alert alert-warning mb-2">${String(data.error)}</div>`;
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

        const messages = Array.isArray(data?.messages) ? data.messages : [];
        if (!messages.length) {
            container.innerHTML = `<div class="alert alert-secondary mb-2" style="background: rgba(30,41,59,.55); border-color: rgba(148,163,184,.18); color: rgba(226,232,240,.9);">No messages yet.</div>`;
            renderPagination(Number(data?.total_pages || 0));
            return;
        }

        container.innerHTML = messages.map((msg) => {
            const authorSafe = escapeHtml(msg?.author);
            const textSafe = escapeHtml(msg?.text);
            const dt = formatTehranDateTime(msg?.created_at);
            const dtSafe = escapeHtml(dt);
            const msgPk = Number(msg?.pk || 0);
            const authorPk = Number(msg?.author_pk || 0);
            const isOwnMessage = authorPk === CURRENT_USER_ID;
            const isPublic = Number(msg?.public || 0) === 1;
            const publicActiveClass = isPublic ? " icon-btn--public-active" : "";
            const msgSlug = escapeHtml(String(msg?.slug || "").toLowerCase());
            const profileSlug = escapeHtml(String(msg?.profile_slug || "").toLowerCase());
            const hasPassword = msg?.has_password ? "1" : "0";
            const shareUrl = escapeHtml(String(msg?.url || ""));
            const editBtnHtml = isOwnMessage
                ? `<button type="button" class="btn btn-sm edit-btn icon-btn icon-btn--msg" aria-label="Edit" title="Edit" data-message-pk="${msgPk}">
                            <img src="assets/img/icons/edit.svg" alt="" aria-hidden="true">
                        </button>`
                : "";
            return `
            <div class="message-box" data-message-pk="${msgPk}" data-is-public="${isPublic ? "1" : "0"}" data-slug="${msgSlug}" data-profile-slug="${profileSlug}" data-has-password="${hasPassword}" data-share-url="${shareUrl}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <small>
                        ${authorSafe}
                        ${dtSafe ? `<span class="ms-2" style="color: rgba(226, 232, 240, 0.55);">(${dtSafe})</span>` : ``}
                    </small>
                    <div class="d-inline-flex message-actions">
                        <button type="button" class="btn btn-sm message-actions-more icon-btn icon-btn--msg" aria-label="More actions" aria-expanded="false" aria-haspopup="true" title="More">
                            <img src="assets/img/icons/more.svg" alt="" aria-hidden="true">
                        </button>
                        <div class="message-actions-menu" role="menu">
                        <button type="button" class="btn btn-sm delete-btn icon-btn icon-btn--msg icon-btn--danger" aria-label="Delete" title="Delete" data-message-pk="${msgPk}">
                            <img src="assets/img/icons/delete.svg" alt="" aria-hidden="true">
                        </button>
                        <button type="button" class="btn btn-sm retext-btn icon-btn icon-btn--msg" aria-label="Retext" title="Retext">
                            <img src="assets/img/icons/resend.svg" alt="" aria-hidden="true">
                        </button>
                        <button type="button" class="btn btn-sm public-btn icon-btn icon-btn--msg${publicActiveClass}" aria-label="Public" title="Public" data-message-pk="${msgPk}" data-is-public="${isPublic ? "1" : "0"}">
                            <img src="assets/img/icons/public.svg" alt="" aria-hidden="true">
                        </button>
                        ${editBtnHtml}
                        <button type="button" class="btn btn-sm copy-btn icon-btn icon-btn--msg" aria-label="Copy" title="Copy">
                            <img src="assets/img/icons/clipboard-copy.svg" alt="" aria-hidden="true">
                        </button>
                        </div>
                    </div>
                </div>
                <div class="message-text" style="white-space: pre-wrap;">${textSafe}</div>
            </div>`;
        }).join("");

        renderPagination(Number(data?.total_pages || 0));
    } catch (_) {
        const container = document.getElementById("messages");
        if (container) {
            container.innerHTML = `<div class="alert alert-warning mb-2">Network error while loading messages.</div>`;
        }
        renderPagination(0);
    } finally {
        setMessagesLoading(false);
    }
}

function refreshMessagesToFirstPage() {
    loadMessages(1);
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
    const advanced = isMessageAdvancedMode();
    const sendToSelf = !!document.getElementById("sendToSelfToggle")?.checked;

    let profilePk = CURRENT_USER_ID;
    let includesSelf = true;
    let advancedRecipientCount = 0;

    if (advanced) {
        const recipients = Array.from(selectedRecipients.keys());
        if (sendToSelf) recipients.push(CURRENT_USER_ID);
        const uniqueRecipients = Array.from(new Set(recipients)).filter((id) => Number(id) > 0);
        includesSelf = uniqueRecipients.includes(CURRENT_USER_ID);
        if (!text || uniqueRecipients.length === 0) {
            updateSendButtonState();
            return;
        }
        profilePk = uniqueRecipients;
        advancedRecipientCount = uniqueRecipients.length;
    } else if (!text) {
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
        body: JSON.stringify({ text, profile_pk: profilePk })
    })
    .then(async (res) => {
        let data = null;
        try { data = await res.clone().json(); } catch (_) {}
        if (!res.ok) throw new Error(data?.error || "Send failed");
        if (data?.error) throw new Error(data.error);
    })
    .then(() => {
        setMessageText("", { focus: false });
        if (!advanced || includesSelf) loadMessages(currentPage);
        setMessageAdvancedMode(false);
        const modalEl = document.getElementById("messageModal");
        const modal = getMessageModalInstance();
        if (advanced && advancedRecipientCount > 0 && modalEl) {
            const onHidden = () => {
                modalEl.removeEventListener("hidden.bs.modal", onHidden);
                if (typeof AppToast !== "undefined") {
                    const n = advancedRecipientCount;
                    const label = n === 1 ? "person" : "people";
                    AppToast.success(`Message sent successfully to ${n} ${label}.`);
                }
            };
            modalEl.addEventListener("hidden.bs.modal", onHidden);
        }
        modal?.hide();
    })
    .catch(() => {
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
    updateSendButtonState();
}

function removeRecipient(id) {
    selectedRecipients.delete(Number(id));
    updateRecipientTags();
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

// --- Delete message modal helpers ---
function getEditModalInstance() {
    const el = document.getElementById("editMessageModal");
    if (!el) return null;
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

const editState = {
    messagePk: null,
    isSubmitting: false,
};

function setEditFormError(msg) {
    const el = document.getElementById("editMessageError");
    if (el) el.textContent = msg || "";
}

function setEditUiSubmitting(isSubmitting) {
    editState.isSubmitting = !!isSubmitting;
    const saveBtn = document.getElementById("editMessageSaveBtn");
    const cancelBtn = document.getElementById("editMessageCancelBtn");
    const closeX = document.getElementById("editMessageCloseX");
    const input = document.getElementById("editMessageInput");
    if (!saveBtn || !cancelBtn) return;

    if (isSubmitting) {
        cancelBtn.disabled = true;
        if (closeX) closeX.disabled = true;
        if (input) input.disabled = true;
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving…`;
    } else {
        cancelBtn.disabled = false;
        if (closeX) closeX.disabled = false;
        if (input) input.disabled = false;
        saveBtn.disabled = !(input?.value?.trim().length > 0);
        saveBtn.textContent = "Save";
    }
}

function updateEditSaveButtonState() {
    if (editState.isSubmitting) return;
    const saveBtn = document.getElementById("editMessageSaveBtn");
    const input = document.getElementById("editMessageInput");
    if (!saveBtn || !input) return;
    saveBtn.disabled = input.value.trim().length === 0;
}

function openEditModal(messagePk, text) {
    const pk = Number(messagePk || 0);
    if (!pk || pk <= 0) return;
    editState.messagePk = pk;
    setEditFormError("");
    setEditUiSubmitting(false);
    const input = document.getElementById("editMessageInput");
    if (input) {
        input.value = String(text ?? "");
        updateEditSaveButtonState();
    }
    getEditModalInstance()?.show();
    input?.focus();
}

async function confirmEditMessage() {
    if (editState.isSubmitting) return;
    const pk = Number(editState.messagePk || 0);
    if (!pk || pk <= 0) return;

    const input = document.getElementById("editMessageInput");
    const text = input?.value?.trim() ?? "";
    if (!text) {
        setEditFormError("Message text cannot be empty.");
        updateEditSaveButtonState();
        return;
    }

    setEditUiSubmitting(true);
    setEditFormError("");
    try {
        const res = await fetch("api/edit_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message_pk: pk, text }),
        });
        let data = null;
        try { data = await res.clone().json(); } catch (_) {}
        if (!res.ok) throw new Error(data?.error || "Update failed");
        if (data?.error) throw new Error(data.error);

        const box = document.querySelector(`.message-box[data-message-pk="${pk}"]`);
        const textEl = box?.querySelector(".message-text");
        if (textEl) textEl.textContent = data?.text ?? text;

        getEditModalInstance()?.hide();
    } catch (err) {
        setEditFormError(String(err?.message || "Could not save changes."));
    } finally {
        setEditUiSubmitting(false);
    }
}

function getDeleteModalInstance() {
    const el = document.getElementById("deleteMessageModal");
    if (!el) return null;
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

const deleteState = {
    messagePk: null,
    isSubmitting: false,
};

function setDeleteUiSubmitting(isSubmitting) {
    deleteState.isSubmitting = !!isSubmitting;
    const confirmBtn = document.getElementById("deleteMessageConfirmBtn");
    const cancelBtn = document.getElementById("deleteMessageCancelBtn");
    const closeX = document.getElementById("deleteMessageCloseX");
    if (!confirmBtn || !cancelBtn) return;

    if (isSubmitting) {
        cancelBtn.disabled = true;
        if (closeX) closeX.disabled = true;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Deleting...`;
    } else {
        cancelBtn.disabled = false;
        if (closeX) closeX.disabled = false;
        confirmBtn.disabled = false;
        confirmBtn.textContent = "Delete";
    }
}

function openDeleteModal(messagePk) {
    const pk = Number(messagePk || 0);
    if (!pk || pk <= 0) return;
    deleteState.messagePk = pk;
    setDeleteUiSubmitting(false);
    getDeleteModalInstance()?.show();
}

async function confirmDeleteMessage() {
    if (deleteState.isSubmitting) return;
    const pk = Number(deleteState.messagePk || 0);
    if (!pk || pk <= 0) return;

    setDeleteUiSubmitting(true);
    try {
        const res = await fetch("api/delete_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message_pk: pk }),
        });
        let data = null;
        try { data = await res.clone().json(); } catch (_) {}
        if (!res.ok) throw new Error(data?.error || "Delete failed");
        if (data?.error) throw new Error(data.error);

        getDeleteModalInstance()?.hide();
        loadMessages(currentPage);
    } catch (_) {
        // Keep modal open so user can retry.
    } finally {
        setDeleteUiSubmitting(false);
    }
}

function encodeProfileSlug(profilePk) {
    return Number(profilePk || CURRENT_USER_ID || 0).toString(36);
}

function generateDefaultSlug(messagePk) {
    const base = Number(messagePk).toString(36);
    const suffix = String(Math.floor(Math.random() * 10000)).padStart(4, "0");
    return `${base}-${suffix}`;
}

function messageShareUrl(profileSlug, slug) {
    const ppk = String(profileSlug || "").toLowerCase();
    const mid = String(slug || "").trim().toLowerCase();
    if (!ppk || !mid) return "";
    const base = APP_BASE_PATH || "";
    const origin = APP_ORIGIN || window.location.origin;
    return `${origin}${base}/${ppk}/${encodeURIComponent(mid)}`;
}

function getPublicModalInstance() {
    const el = document.getElementById("publicMessageModal");
    if (!el) return null;
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

const publicState = {
    messagePk: null,
    isPublic: false,
    slug: "",
    profileSlug: encodeProfileSlug(CURRENT_USER_ID),
    hasPassword: false,
    shareUrl: "",
    isSubmitting: false,
    showPasswordForm: false,
    clearPasswordOnSave: false,
    slugRejected: false,
};

function syncPublicButtonInList(messagePk, isPublic, extra = {}) {
    const box = document.querySelector(`.message-box[data-message-pk="${messagePk}"]`);
    if (!box) return;
    box.setAttribute("data-is-public", isPublic ? "1" : "0");
    if (extra.slug != null) box.setAttribute("data-slug", String(extra.slug).toLowerCase());
    if (extra.hasPassword != null) box.setAttribute("data-has-password", extra.hasPassword ? "1" : "0");
    if (extra.url != null) box.setAttribute("data-share-url", extra.url);
    const btn = box.querySelector("button.public-btn");
    if (!btn) return;
    btn.setAttribute("data-is-public", isPublic ? "1" : "0");
    btn.classList.toggle("icon-btn--public-active", !!isPublic);
}

function isValidShareSlug(slug) {
    const s = String(slug || "").trim().toLowerCase();
    if (!s) return false;
    return /^[0-9a-z][0-9a-z\-]*$/i.test(s);
}

function updateShareFieldStates() {
    const slugInput = document.getElementById("shareSlugInput");
    const urlInput = document.getElementById("shareUrlPreview");
    if (!slugInput || !urlInput) return;

    const slug = slugInput.value.trim().toLowerCase();
    publicState.slug = slug;
    const formatValid = isValidShareSlug(slug);
    const valid = formatValid && !publicState.slugRejected;

    slugInput.classList.remove("share-field--valid", "share-field--invalid");
    urlInput.classList.remove("share-field--valid", "share-field--invalid");

    if (valid) {
        slugInput.classList.add("share-field--valid");
        urlInput.classList.add("share-field--valid");
        publicState.shareUrl = messageShareUrl(publicState.profileSlug, slug);
        urlInput.value = publicState.shareUrl;
    } else {
        slugInput.classList.add("share-field--invalid");
        urlInput.classList.add("share-field--invalid");
        if (formatValid) {
            publicState.shareUrl = messageShareUrl(publicState.profileSlug, slug);
            urlInput.value = publicState.shareUrl;
        } else {
            publicState.shareUrl = "";
            urlInput.value = "";
        }
    }
}

function setShareFormError(msg) {
    const el = document.getElementById("shareFormError");
    if (el) el.textContent = msg || "";
}

function bindShareModalEvents() {
    const publicToggle = document.getElementById("sharePublicToggle");
    publicToggle?.addEventListener("change", () => {
        publicState.isPublic = !!publicToggle.checked;
    });

    const slugInput = document.getElementById("shareSlugInput");
    slugInput?.addEventListener("input", () => {
        publicState.slugRejected = false;
        setShareFormError("");
        updateShareFieldStates();
    });

    document.getElementById("shareGenerateSlugBtn")?.addEventListener("click", () => {
        const pk = Number(publicState.messagePk || 0);
        if (!pk) return;
        const generated = generateDefaultSlug(pk);
        if (slugInput) slugInput.value = generated;
        publicState.slugRejected = false;
        setShareFormError("");
        updateShareFieldStates();
    });

    document.getElementById("shareCopyUrlBtn")?.addEventListener("click", async () => {
        const link = document.getElementById("shareUrlPreview")?.value || publicState.shareUrl;
        const btn = document.getElementById("shareCopyUrlBtn");
        if (!link || !btn) return;
        await copyText(btn, link);
    });

    document.getElementById("shareSetPasswordBtn")?.addEventListener("click", () => {
        publicState.showPasswordForm = !publicState.showPasswordForm;
        publicState.clearPasswordOnSave = false;
        const panel = document.getElementById("sharePasswordPanel");
        panel?.classList.toggle("d-none", !publicState.showPasswordForm);
        if (publicState.showPasswordForm) {
            document.getElementById("sharePasswordInput")?.focus();
        }
    });

    document.getElementById("shareClearPasswordBtn")?.addEventListener("click", () => {
        publicState.clearPasswordOnSave = true;
        publicState.showPasswordForm = false;
        publicState.hasPassword = false;
        document.getElementById("sharePasswordPanel")?.classList.add("d-none");
        const badge = document.getElementById("sharePasswordBadge");
        if (badge) {
            badge.className = "share-badge share-badge--off";
            badge.textContent = "No password";
        }
        const pwdInput = document.getElementById("sharePasswordInput");
        if (pwdInput) pwdInput.value = "";
    });
}

function renderPublicModalContent() {
    const body = document.getElementById("publicMessageModalBody");
    if (!body) return;

    const escapeHtml = (value) => String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");

    const isPublic = !!publicState.isPublic;
    const pwdBadgeClass = publicState.hasPassword ? "share-badge share-badge--on" : "share-badge share-badge--off";
    const pwdBadgeLabel = publicState.hasPassword ? "Password set" : "No password";
    const passwordPanelClass = publicState.showPasswordForm ? "" : "d-none";
    const clearPwdBtn = publicState.hasPassword
        ? `<button type="button" id="shareClearPasswordBtn" class="btn btn-outline-danger btn-sm">Remove</button>`
        : "";

    publicState.shareUrl = isValidShareSlug(publicState.slug)
        ? messageShareUrl(publicState.profileSlug, publicState.slug)
        : "";

    body.innerHTML = `
        <div class="share-modal-section">
            <div class="share-row">
                <div>
                    <p class="share-row__label">Public access</p>
                    <p class="share-row__hint">${isPublic ? "Anyone with the link can open this message." : "Only you can view this message from your profile."}</p>
                </div>
                <label class="share-toggle" title="Toggle public access">
                    <input type="checkbox" id="sharePublicToggle" ${isPublic ? "checked" : ""} ${publicState.isSubmitting ? "disabled" : ""}>
                    <span class="share-toggle__track"></span>
                </label>
            </div>
        </div>

        <div class="share-modal-section">
            <div class="share-field-label">Link slug</div>
            <div class="share-slug-row">
                <input type="text" id="shareSlugInput" class="form-control" value="${escapeHtml(publicState.slug)}" placeholder="e.g. 367 or abc-1234" autocomplete="off" spellcheck="false" ${publicState.isSubmitting ? "disabled" : ""}>
                <button type="button" id="shareGenerateSlugBtn" class="btn icon-btn icon-btn--msg share-icon-btn" aria-label="Generate random slug" title="Generate random slug" ${publicState.isSubmitting ? "disabled" : ""}>
                    <img src="assets/img/icons/random.svg" alt="" aria-hidden="true">
                </button>
            </div>
            <div class="share-url-preview">
                <input type="text" id="shareUrlPreview" class="form-control" readonly value="${escapeHtml(publicState.shareUrl)}" aria-label="Message URL">
                <button type="button" id="shareCopyUrlBtn" class="btn icon-btn icon-btn--msg share-icon-btn copy-btn" aria-label="Copy link" title="Copy link">
                    <img src="assets/img/icons/clipboard-copy.svg" alt="" aria-hidden="true">
                </button>
            </div>
        </div>

        <div class="share-modal-section">
            <div class="share-row">
                <span id="sharePasswordBadge" class="${pwdBadgeClass}">${pwdBadgeLabel}</span>
                <div class="d-flex gap-2">
                    ${clearPwdBtn}
                    <button type="button" id="shareSetPasswordBtn" class="btn btn-outline-secondary btn-sm" ${publicState.isSubmitting ? "disabled" : ""}>
                        ${publicState.hasPassword ? "Change" : "Set password"}
                    </button>
                </div>
            </div>
            <div id="sharePasswordPanel" class="share-password-panel ${passwordPanelClass}">
                <label for="sharePasswordInput" class="form-label small mb-1">New password</label>
                <input type="password" id="sharePasswordInput" class="form-control" placeholder="At least 4 characters" autocomplete="new-password" minlength="4">
            </div>
        </div>

        <div id="shareFormError" class="share-form-error" role="alert"></div>
    `;

    bindShareModalEvents();
    updateShareFieldStates();
}

function openPublicModal(messagePk, isPublic, extra = {}) {
    const pk = Number(messagePk || 0);
    if (!pk || pk <= 0) return;
    publicState.messagePk = pk;
    publicState.isPublic = !!isPublic;
    publicState.slug = String(extra.slug || "").trim().toLowerCase();
    publicState.profileSlug = String(extra.profileSlug || encodeProfileSlug(CURRENT_USER_ID)).toLowerCase();
    publicState.hasPassword = !!extra.hasPassword;
    publicState.shareUrl = extra.url || (isValidShareSlug(publicState.slug)
        ? messageShareUrl(publicState.profileSlug, publicState.slug)
        : "");
    publicState.isSubmitting = false;
    publicState.showPasswordForm = false;
    publicState.clearPasswordOnSave = false;
    publicState.slugRejected = false;
    setShareFormError("");
    renderPublicModalContent();
    getPublicModalInstance()?.show();
}

async function saveMessageSharing() {
    if (publicState.isSubmitting) return;
    const pk = Number(publicState.messagePk || 0);
    if (!pk || pk <= 0) return;

    const slug = document.getElementById("shareSlugInput")?.value?.trim().toLowerCase() || "";
    const isPublic = !!document.getElementById("sharePublicToggle")?.checked;
    const newPassword = document.getElementById("sharePasswordInput")?.value?.trim() || "";

    if (!isValidShareSlug(slug)) {
        setShareFormError("Please enter a valid slug for the link.");
        updateShareFieldStates();
        return;
    }

    const payload = {
        message_pk: pk,
        public: isPublic ? 1 : 0,
        slug,
    };

    if (publicState.clearPasswordOnSave) {
        payload.clear_password = true;
        payload.password = "";
    } else if (publicState.showPasswordForm && newPassword !== "") {
        payload.password = newPassword;
    }

    publicState.isSubmitting = true;
    setShareFormError("");
    const saveBtn = document.getElementById("publicMessageSaveBtn");
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving…`;
    }

    try {
        const res = await fetch("api/set_message_public.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });
        let data = null;
        try { data = await res.clone().json(); } catch (_) {}

        if (!res.ok) {
            if (data?.error === "slug_taken") {
                publicState.slugRejected = true;
                setShareFormError(data?.message || "This slug is already in use.");
                updateShareFieldStates();
                return;
            }
            if (data?.error === "slug_invalid") {
                publicState.slugRejected = true;
                setShareFormError("Invalid slug format.");
                updateShareFieldStates();
                return;
            }
            if (data?.error === "password_too_short") {
                setShareFormError("Password must be at least 4 characters.");
                return;
            }
            throw new Error(data?.error || "Update failed");
        }
        if (data?.error) throw new Error(data.error);

        publicState.isPublic = Number(data?.public || 0) === 1;
        publicState.slug = String(data?.slug || slug).toLowerCase();
        publicState.shareUrl = data?.url || messageShareUrl(publicState.profileSlug, publicState.slug);
        publicState.hasPassword = !!data?.has_password;
        publicState.showPasswordForm = false;
        publicState.clearPasswordOnSave = false;
        syncPublicButtonInList(pk, publicState.isPublic, {
            slug: publicState.slug,
            hasPassword: publicState.hasPassword,
            url: publicState.shareUrl,
        });
        getPublicModalInstance()?.hide();
    } catch (err) {
        setShareFormError(String(err?.message || "Could not save changes."));
    } finally {
        publicState.isSubmitting = false;
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = "Save changes";
        }
    }
}

function closeMessageActionMenus() {
    document.querySelectorAll(".message-actions-menu.is-open").forEach((menu) => {
        menu.classList.remove("is-open");
        menu.closest(".message-actions")?.querySelector(".message-actions-more")?.setAttribute("aria-expanded", "false");
    });
}

async function copyText(btn, text){
    if (!btn) return;
    const originalDisabled = btn.disabled;
    const originalAriaLabel = btn.getAttribute("aria-label") ?? "Copy";
    const originalTitle = btn.getAttribute("title") ?? "Copy";

    const restoreButton = () => {
        btn.classList.remove("copy-btn--copied");
        btn.setAttribute("aria-label", originalAriaLabel);
        btn.setAttribute("title", originalTitle);
        btn.disabled = originalDisabled;
        if (btn.closest?.(".message-actions-menu")) closeMessageActionMenus();
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

        btn.classList.add("copy-btn--copied");
        btn.setAttribute("aria-label", "Copied");
        btn.setAttribute("title", "Copied");
        btn.disabled = true;
        setTimeout(restoreButton, 3000);
    } catch (_) {
        btn.setAttribute("aria-label", "Copy failed");
        btn.setAttribute("title", "Copy failed");
        setTimeout(() => {
            btn.setAttribute("aria-label", originalAriaLabel);
            btn.setAttribute("title", originalTitle);
        }, 1500);
    }
}

loadMessages();

// Wire up modal input behavior
(() => {
    document.addEventListener("click", (e) => {
        if (!e.target?.closest?.(".message-actions")) closeMessageActionMenus();
    }, true);

    document.getElementById("messages")?.addEventListener("click", (e) => {
        const moreBtn = e.target?.closest?.("button.message-actions-more");
        if (moreBtn) {
            e.stopPropagation();
            const menu = moreBtn.closest(".message-actions")?.querySelector(".message-actions-menu");
            if (!menu) return;
            const willOpen = !menu.classList.contains("is-open");
            closeMessageActionMenus();
            if (willOpen) {
                menu.classList.add("is-open");
                moreBtn.setAttribute("aria-expanded", "true");
            }
            return;
        }
        const menuBtn = e.target?.closest?.(".message-actions-menu button");
        if (menuBtn && !menuBtn.classList.contains("copy-btn")) closeMessageActionMenus();
    });

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
        setMessageAdvancedMode(false);
        setMessageText(text);
        getMessageModalInstance()?.show();
    });

    // Edit button handler (event delegation).
    document.getElementById("messages")?.addEventListener("click", (e) => {
        const btn = e.target?.closest?.("button.edit-btn");
        if (!btn) return;
        const box = btn.closest(".message-box");
        const pk = Number(btn.getAttribute("data-message-pk") || 0);
        const text = box?.querySelector?.(".message-text")?.innerText ?? "";
        openEditModal(pk, text);
    });

    // Delete button handler (event delegation).
    document.getElementById("messages")?.addEventListener("click", (e) => {
        const btn = e.target?.closest?.("button.delete-btn");
        if (!btn) return;
        const pk = Number(btn.getAttribute("data-message-pk") || 0);
        openDeleteModal(pk);
    });

    // Public button handler (event delegation).
    document.getElementById("messages")?.addEventListener("click", (e) => {
        const btn = e.target?.closest?.("button.public-btn");
        if (!btn) return;
        const box = btn.closest(".message-box");
        const pk = Number(btn.getAttribute("data-message-pk") || 0);
        const isPublic = String(btn.getAttribute("data-is-public") || "0") === "1";
        openPublicModal(pk, isPublic, {
            slug: box?.getAttribute("data-slug") || "",
            hasPassword: String(box?.getAttribute("data-has-password") || "0") === "1",
            profileSlug: box?.getAttribute("data-profile-slug") || encodeProfileSlug(CURRENT_USER_ID),
            url: box?.getAttribute("data-share-url") || "",
        });
    });

    document.getElementById("publicMessageSaveBtn")?.addEventListener("click", saveMessageSharing);

    document.getElementById("editMessageSaveBtn")?.addEventListener("click", confirmEditMessage);
    document.getElementById("editMessageInput")?.addEventListener("input", () => {
        setEditFormError("");
        updateEditSaveButtonState();
    });
    document.getElementById("editMessageModal")?.addEventListener("shown.bs.modal", () => {
        document.getElementById("editMessageInput")?.focus();
    });
    document.getElementById("editMessageModal")?.addEventListener("hidden.bs.modal", () => {
        editState.messagePk = null;
        setEditFormError("");
        setEditUiSubmitting(false);
        const input = document.getElementById("editMessageInput");
        if (input) input.value = "";
    });

    document.getElementById("publicMessageModal")?.addEventListener("hidden.bs.modal", () => {
        publicState.messagePk = null;
        publicState.isPublic = false;
        publicState.slug = "";
        publicState.shareUrl = "";
        publicState.hasPassword = false;
        publicState.isSubmitting = false;
        publicState.showPasswordForm = false;
        publicState.clearPasswordOnSave = false;
        publicState.slugRejected = false;
        setShareFormError("");
    });

    // Delete modal handlers.
    document.getElementById("deleteMessageConfirmBtn")?.addEventListener("click", confirmDeleteMessage);
    document.getElementById("deleteMessageModal")?.addEventListener("hidden.bs.modal", () => {
        deleteState.messagePk = null;
        setDeleteUiSubmitting(false);
    });

    const input = document.getElementById("messageInput");
    const modalEl = document.getElementById("messageModal");
    const advancedToggle = document.getElementById("messageAdvancedToggle");
    const recipientInput = document.getElementById("recipientInput");
    const suggestionBox = document.getElementById("recipientSuggestions");
    const sendToSelfToggle = document.getElementById("sendToSelfToggle");

    if (input) {
        input.addEventListener("input", updateSendButtonState);
        input.addEventListener("keydown", (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === "Enter") sendMessage();
        });
    }

    advancedToggle?.addEventListener("click", () => {
        setMessageAdvancedMode(!isMessageAdvancedMode(), { focusRecipient: true });
    });

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

    if (sendToSelfToggle) {
        sendToSelfToggle.addEventListener("change", updateSendButtonState);
    }

    document.addEventListener("click", (e) => {
        const box = document.getElementById("recipientSuggestions");
        const wrap = document.getElementById("messageAdvancedSection");
        if (!box || !wrap || wrap.classList.contains("d-none")) return;
        if (wrap.contains(e.target)) return;
        renderSuggestions([]);
    });

    if (modalEl) {
        modalEl.addEventListener("shown.bs.modal", () => {
            setMessageAdvancedMode(false);
            updateSendButtonState();
            document.getElementById("messageInput")?.focus();
        });
        modalEl.addEventListener("hidden.bs.modal", () => {
            setMessageAdvancedMode(false);
            setMessageText("", { focus: false });
        });
    }

    updateSendButtonState();
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
