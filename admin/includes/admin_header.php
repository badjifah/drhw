<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Session.php';
require_once __DIR__ . '/../../includes/functions.php';

Session::requireLogin();

$admin_name = Session::get('admin_name', Session::get('admin_username'));
$admin_role = Session::get('admin_role');
$pageTitle = $pageTitle ?? 'Tableau de bord';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($pageTitle) ?> | DRH Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- Top Navigation Bar -->
<header class="topbar" id="topbar">
    <a href="<?= BASE_URL ?>/admin/index.php" class="topbar-brand">
        <div class="topbar-brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <span>DRH Admin</span>
    </a>

    <nav class="topbar-nav" id="topbar-nav">
        <a href="<?= BASE_URL ?>/admin/index.php" class="topbar-nav-link <?= activePage('admin/index.php') ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/admin/actualites.php" class="topbar-nav-link <?= activePage('admin/actualites.php') ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Actualités
        </a>
        <a href="<?= BASE_URL ?>/admin/documents.php" class="topbar-nav-link <?= activePage('admin/documents.php') ? 'active' : '' ?>">
            <i class="fas fa-file-lines"></i> Documents
        </a>
        <a href="<?= BASE_URL ?>/admin/services.php" class="topbar-nav-link <?= activePage('admin/services.php') ? 'active' : '' ?>">
            <i class="fas fa-building"></i> Services
        </a>
        <a href="<?= BASE_URL ?>/admin/messages.php" class="topbar-nav-link <?= activePage('admin/messages.php') ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Messages
        </a>
        <a href="<?= BASE_URL ?>/admin/admins.php" class="topbar-nav-link <?= activePage('admin/admins.php') ? 'active' : '' ?>">
            <i class="fas fa-users-gear"></i> Admins
        </a>
        <a href="<?= BASE_URL ?>/admin/activity_logs.php" class="topbar-nav-link <?= activePage('admin/activity_logs.php') ? 'active' : '' ?>">
            <i class="fas fa-clock-rotate-left"></i> Logs
        </a>
        <a href="<?= BASE_URL ?>/admin/settings.php" class="topbar-nav-link <?= activePage('admin/settings.php') ? 'active' : '' ?>">
            <i class="fas fa-cog"></i> Paramètres
        </a>
    </nav>

    <div class="topbar-right">
        <div class="admin-user-badge" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 100px; background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 600; margin-right: 8px;">
            <i class="fas fa-user" style="font-size: 11px;"></i>
            <?= sanitize($admin_name) ?>
            <?php if ($admin_role === 'superadmin'): ?><span style="color: var(--color-accent-amber);">★</span><?php endif; ?>
        </div>
        <a href="<?= BASE_URL ?>/index.php" target="_blank" class="topbar-btn" title="Voir le site">
            <i class="fas fa-external-link-alt"></i>
        </a>
        <a href="?page=search" class="topbar-btn" title="Recherche">
            <i class="fas fa-search"></i>
        </a>
        <a href="?page=profile" class="topbar-btn" title="Profil">
            <i class="fas fa-user-shield"></i>
        </a>
        <form method="POST" action="<?= BASE_URL ?>/admin/logout.php" style="display:inline;">
            <?= csrfField() ?>
            <button type="submit" class="topbar-btn" title="Déconnexion" style="color: #F87171; background: none; border: none; cursor: pointer; padding: 0;">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
        <button class="topbar-btn topbar-burger" id="mobile-menu-toggle" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <h1 class="page-title"><?= sanitize($pageTitle) ?></h1>
    </div>

    <?php flashDisplay(); ?>
