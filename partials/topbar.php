<?php
/**
 * Shared app header.
 *
 * Expected variables:
 * - $topbarBrandText (string)
 * - $topbarLoggedIn (bool)
 * - $topbarOnTextPage (bool) — when true, refresh reloads page and settings links to index.php
 */
$topbarBrandText = $topbarBrandText ?? 'Texter';
$topbarLoggedIn = !empty($topbarLoggedIn);
$topbarOnTextPage = !empty($topbarOnTextPage);
?>
<nav class="navbar bg-white shadow-sm app-header">
    <div class="container d-flex justify-content-between align-items-center">
        <?php if ($topbarLoggedIn): ?>
            <a href="index.php" class="navbar-brand mb-0 h1"><?php echo htmlspecialchars($topbarBrandText, ENT_QUOTES, 'UTF-8'); ?></a>
        <?php else: ?>
            <span class="navbar-brand mb-0 h1"><?php echo htmlspecialchars($topbarBrandText, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-2">
            <?php if ($topbarLoggedIn): ?>
                <button
                    type="button"
                    class="btn btn-sm icon-btn icon-btn--header"
                    <?php if ($topbarOnTextPage): ?>
                    onclick="window.location.reload()"
                    <?php else: ?>
                    onclick="refreshMessagesToFirstPage()"
                    <?php endif; ?>
                    aria-label="Refresh messages"
                    title="Refresh"
                >
                    <img src="assets/img/icons/refresh.svg" alt="" aria-hidden="true">
                </button>
                <?php if ($topbarOnTextPage): ?>
                <a
                    class="btn btn-sm icon-btn icon-btn--header"
                    href="index.php"
                    aria-label="Settings"
                    title="Settings"
                >
                    <img src="assets/img/icons/setting.svg" alt="" aria-hidden="true">
                </a>
                <?php else: ?>
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
                <?php endif; ?>
                <a class="btn btn-sm icon-btn icon-btn--header" href="logout.php" aria-label="Logout" title="Logout">
                    <img src="assets/img/icons/logout.svg" alt="" aria-hidden="true">
                </a>
            <?php else: ?>
                <a class="btn btn-sm icon-btn icon-btn--header" href="login.php" aria-label="Login" title="Login">
                    <img src="assets/img/icons/login.svg" alt="" aria-hidden="true">
                </a>
                <a class="btn btn-sm icon-btn icon-btn--header" href="register.php" aria-label="Register" title="Register">
                    <img src="assets/img/icons/register.svg" alt="" aria-hidden="true">
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
