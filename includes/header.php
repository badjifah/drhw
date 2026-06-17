<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($pageTitle ?? SITE_NAME) ?> | DRH</title>
    <meta name="description" content="<?= sanitize($pageDescription ?? SITE_DESCRIPTION) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <?php renderMetaTags($pageTitle ?? SITE_NAME, $pageDescription ?? SITE_DESCRIPTION); ?>
</head>
<body>

<!-- Top Navigation Bar -->
<header class="topbar" id="topbar">
    <a href="<?= BASE_URL ?>/index.php" class="topbar-brand">
        <div class="topbar-brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <span>DRH</span>
    </a>

    <nav class="topbar-nav" id="topbar-nav">
        <a href="<?= BASE_URL ?>/index.php" class="topbar-nav-link <?= activePage('index.php') ? 'active' : '' ?>">
            <i class="fas fa-house"></i> Accueil
        </a>
        <a href="<?= BASE_URL ?>/about.php" class="topbar-nav-link <?= activePage('about.php') ? 'active' : '' ?>">
            <i class="fas fa-circle-info"></i> À propos
        </a>
        <a href="<?= BASE_URL ?>/services.php" class="topbar-nav-link <?= activePage('services.php') ? 'active' : '' ?>">
            <i class="fas fa-building"></i> Services
        </a>
        <a href="<?= BASE_URL ?>/actualites.php" class="topbar-nav-link <?= activePage('actualites.php') ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Actualités
        </a>
        <a href="<?= BASE_URL ?>/documents.php" class="topbar-nav-link <?= activePage('documents.php') ? 'active' : '' ?>">
            <i class="fas fa-file-lines"></i> Documents
        </a>
        <a href="<?= BASE_URL ?>/?page=search" class="topbar-nav-link <?= isActive('search') ?>">
            <i class="fas fa-search"></i> Recherche
        </a>
        <a href="<?= BASE_URL ?>/contact.php" class="topbar-nav-link <?= activePage('contact.php') ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Contact
        </a>
    </nav>

    <div class="topbar-right">
        <button class="topbar-btn topbar-burger" id="mobile-menu-toggle" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
