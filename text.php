<?php
require_once 'lib/MessagePublic.php';
require 'lib/library.php';

use Avetify\Themes\Main\ThemesManager;

$conn = new TexterConnection();
$auth = new TexterAuth();

$slug = isset($_GET['text']) ? (string)$_GET['text'] : '';
$messagePk = MessagePublic::decodeId($slug);

$message = null;
$notFound = false;

if ($messagePk) {
    $messagePkEsc = (int)$messagePk;
    $res = $conn->query(
        "SELECT m.pk, m.text, m.profile_pk, m.public, m.created_at, u.username AS author_username
         FROM messages m
         INNER JOIN users u ON u.id = m.author_pk
         WHERE m.pk = $messagePkEsc
         LIMIT 1"
    );
    $row = $res ? $res->fetch_assoc() : null;

    if ($row) {
        $isPublic = (int)($row['public'] ?? 0) === 1;
        $isOwner = $auth->isLoggedIn() && (int)$auth->currentUserId() === (int)$row['profile_pk'];
        if ($isPublic || $isOwner) {
            $message = $row;
        } else {
            $notFound = true;
        }
    } else {
        $notFound = true;
    }
} else {
    $notFound = true;
}

if ($notFound) {
    http_response_code(404);
}

$topbarBrandText = $auth->isLoggedIn() ? (string)$auth->currentUsername() : 'Texter';
$topbarLoggedIn = $auth->isLoggedIn();
$topbarOnTextPage = true;

function format_tehran_datetime(int $unixSeconds): string
{
    if ($unixSeconds <= 0) {
        return '';
    }
    $dt = new DateTime('@' . $unixSeconds);
    $dt->setTimezone(new DateTimeZone('Asia/Tehran'));
    return $dt->format('Y/m/d H:i');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $message ? 'Message' : 'Not found'; ?> · Texter</title>
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
<?php ThemesManager::importBootstrapCSS(); ?>
<style>
html, body { height: 100%; }
body {
    background: #0f172a;
    color: #e2e8f0;
    overflow: hidden;
}
:root{
    --app-bg: #0f172a;
    --surface: #1e293b;
    --border: rgba(148, 163, 184, 0.18);
    --text: #e2e8f0;
    --muted: #94a3b8;
}
.app-shell{
    height: 100dvh;
    display: flex;
    flex-direction: column;
}
.app-header{
    position: sticky;
    top: 0;
    z-index: 1030;
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
}
.app-content{
    flex: 1 1 auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 24px 0;
    display: flex;
    align-items: center;
}
.text-page-inner{
    width: 100%;
    max-width: 720px;
}
.message-box {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 14px 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    color: var(--text);
}
.message-box small{ color: var(--muted); }
.not-found-card{
    background: rgba(30, 41, 59, 0.72);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.25rem 1.35rem;
    text-align: center;
    color: rgba(226, 232, 240, 0.88);
}
.icon-btn--msg{
    padding: .5rem .8rem;
    border-radius: .55rem;
    min-width: 44px;
}
.icon-btn{
    --icon-btn-bg: rgba(15, 23, 42, 0.32);
    --icon-btn-bg-hover: rgba(59, 130, 246, 0.14);
    --icon-btn-border: rgba(148, 163, 184, 0.22);
    --icon-btn-border-hover: rgba(96, 165, 250, 0.55);
    appearance: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .5rem .8rem;
    border-radius: .55rem;
    min-width: 44px;
    background: var(--icon-btn-bg);
    border: 1px solid var(--icon-btn-border);
    color: var(--text);
    line-height: 1;
    transition: background .15s ease, border-color .15s ease, transform .05s ease;
}
.icon-btn:hover{
    background: var(--icon-btn-bg-hover);
    border-color: var(--icon-btn-border-hover);
    color: var(--text);
}
.icon-btn:active{ transform: translateY(1px); }
.icon-btn img{
    width: 18px;
    height: 18px;
    display: block;
    filter: brightness(0) saturate(100%) invert(86%) sepia(6%) saturate(891%) hue-rotate(309deg) brightness(90%) contrast(88%);
}
.icon-btn--header{
    padding: .55rem .75rem;
    border-radius: .65rem;
}
.icon-btn--header img{
    width: 20px;
    height: 20px;
}
.copy-btn--copied{
    background: rgba(25, 135, 84, 0.22) !important;
    border-color: rgba(25, 135, 84, 0.55) !important;
}
.copy-btn--copied img{
    filter: brightness(0) saturate(100%) invert(64%) sepia(54%) saturate(463%) hue-rotate(89deg) brightness(92%) contrast(92%);
}
.icon-btn--msg.copy-btn--copied{
    font-size: .82rem;
    font-weight: 600;
    letter-spacing: .01em;
}
</style>
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/partials/topbar.php'; ?>

    <main class="app-content" aria-label="Public message">
        <div class="container text-page-inner">
            <?php if ($message): ?>
                <?php
                $author = htmlspecialchars((string)($message['author_username'] ?? ''), ENT_QUOTES, 'UTF-8');
                $text = htmlspecialchars((string)($message['text'] ?? ''), ENT_QUOTES, 'UTF-8');
                $createdAt = (int)($message['created_at'] ?? 0);
                $dt = htmlspecialchars(format_tehran_datetime($createdAt), ENT_QUOTES, 'UTF-8');
                ?>
                <article class="message-box">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <small>
                            <?php echo $author; ?>
                            <?php if ($dt !== ''): ?>
                                <span class="ms-2" style="color: rgba(226, 232, 240, 0.55);">(<?php echo $dt; ?>)</span>
                            <?php endif; ?>
                        </small>
                        <div class="d-inline-flex message-actions">
                            <button
                                type="button"
                                id="copyMessageBtn"
                                class="btn btn-sm icon-btn icon-btn--msg copy-btn"
                                aria-label="Copy"
                                title="Copy"
                            >
                                <img src="assets/img/icons/clipboard-copy.svg" alt="" aria-hidden="true">
                            </button>
                        </div>
                    </div>
                    <div id="messageText" class="message-text" style="white-space: pre-wrap;"><?php echo $text; ?></div>
                </article>
            <?php else: ?>
                <div class="not-found-card" role="alert">
                    <h1 class="h5 mb-2">Message not found</h1>
                    <p class="mb-0" style="color: rgba(226, 232, 240, 0.7);">This message does not exist or is not available.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php if ($message): ?>
<?php ThemesManager::importBootstrapJS(); ?>
<script>
async function copyText(btn, text) {
    const originalDisabled = btn?.disabled ?? false;
    const originalClassName = btn?.className ?? "";
    const originalInnerHTML = btn?.innerHTML ?? "";
    const originalAriaLabel = btn?.getAttribute?.("aria-label") ?? "Copy";
    const originalTitle = btn?.getAttribute?.("title") ?? "Copy";

    const setState = (label, disabled) => {
        if (!btn) return;
        btn.setAttribute("aria-label", label);
        btn.setAttribute("title", label);
        btn.disabled = disabled;
    };

    const restoreButton = () => {
        if (!btn) return;
        btn.className = originalClassName;
        btn.innerHTML = originalInnerHTML;
        btn.setAttribute("aria-label", originalAriaLabel);
        btn.setAttribute("title", originalTitle);
        btn.disabled = originalDisabled;
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

        setState("Copied", true);
        if (btn) {
            btn.classList.add("copy-btn--copied");
            btn.textContent = "Copied";
        }
        setTimeout(restoreButton, 3000);
    } catch (_) {
        setState("Failed", true);
        if (btn) btn.textContent = "Failed";
        setTimeout(restoreButton, 1500);
    }
}

document.getElementById("copyMessageBtn")?.addEventListener("click", (e) => {
    const btn = e.currentTarget;
    const text = document.getElementById("messageText")?.innerText ?? "";
    copyText(btn, text);
});
</script>
<?php endif; ?>
</body>
</html>
