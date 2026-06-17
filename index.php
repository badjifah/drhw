<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();

$actualites = $db->fetchAll(
    "SELECT id, title, slug, excerpt, image, category, created_at
     FROM actualites WHERE status = 'publie' ORDER BY featured DESC, created_at DESC LIMIT 3"
);

$message_bienvenue = getSetting('directeur_message', 'Bienvenue sur le portail officiel.');
$directeur_prenom = getSetting('directeur_prenom', 'Le Directeur');
$directeur_nom = getSetting('directeur_name', 'des Ressources Humaines');
$directeur_grade = getSetting('directeur_grade', 'Directeur des Ressources Humaines');
$directeur_titre = getSetting('directeur_title', 'Direction des Ressources Humaines');
$directeur_photo = getSetting('directeur_photo', '');

$pageTitle = 'Accueil';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-particles">
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
        <div class="hero-particle"></div>
    </div>
    <h1>Direction des <strong>Ressources Humaines</strong></h1>
    <p>
        Site officiel de la Direction des Ressources Humaines du Ministère de la Sécurité et de la Protection Civile.
        <?= sanitize($message_bienvenue) ?>
    </p>
    <div class="hero-actions">
        <a href="<?= BASE_URL ?>/about.php" class="btn btn-primary">
            <i class="fas fa-circle-info"></i> En savoir plus
        </a>
        <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline">
            <i class="fas fa-envelope"></i> Nous contacter
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid stats-4 mb-lg">
    <div class="stat-card reveal delay-1">
        <p class="stat-card-label">Agents</p>
        <p class="stat-card-value">5 000+</p>
    </div>
    <div class="stat-card reveal delay-2">
        <p class="stat-card-label">Divisions</p>
        <p class="stat-card-value">4</p>
    </div>
    <div class="stat-card reveal delay-3">
        <p class="stat-card-label">Documents</p>
        <p class="stat-card-value">200+</p>
    </div>
    <div class="stat-card reveal delay-4">
        <p class="stat-card-label">Années d'expertise</p>
        <p class="stat-card-value">15+</p>
    </div>
</div>

<!-- Mot du Directeur -->
<div class="director-section mb-lg reveal">
    <div class="director-layout">

        <!-- Colonne portrait -->
        <div class="director-photo-col reveal-scale delay-1">
            <div class="director-badge" style="align-self:flex-start;">
                <div class="director-badge-dot"></div>
                <span>Mot du Directeur</span>
            </div>
            <div class="director-portrait">
                <div class="director-portrait-frame">
                    <?php if (!empty($directeur_photo)): ?>
                        <img src="<?= BASE_URL ?>/assets/uploads/directeur/<?= sanitize($directeur_photo) ?>"
                             alt="Portrait de <?= sanitize($directeur_prenom . ' ' . $directeur_nom) ?>"
                             loading="lazy">
                    <?php else: ?>
                        <div class="director-portrait-placeholder">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="director-portrait-badge" aria-hidden="true">
                    <i class="fas fa-shield-halved"></i>
                </div>
            </div>
        </div>

        <!-- Colonne texte -->
        <div class="director-text-col">

            <!-- Citation -->
            <div class="director-quote-wrap reveal fade-up delay-2">
                <span class="director-quote-mark" aria-hidden="true">&#8220;</span>
                <p class="director-quote-text"><?= sanitize($message_bienvenue) ?></p>
            </div>

            <!-- Séparateur -->
            <div class="director-divider reveal fade-up delay-3">
                <div class="director-divider-line"></div>
                <div class="director-divider-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>

            <!-- Identité -->
            <div class="director-identity reveal fade-up delay-4">
                <div class="director-avatar-ring">
                    <div class="director-avatar-inner">
                        <?= mb_strtoupper(mb_substr($directeur_prenom, 0, 1)) ?>
                    </div>
                </div>
                <div class="director-name-block">
                    <p class="director-name"><?= sanitize($directeur_prenom) ?> <?= sanitize($directeur_nom) ?></p>
                    <p class="director-grade"><?= sanitize($directeur_grade) ?></p>
                    <p class="director-title-sub"><?= sanitize($directeur_titre) ?></p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Actualités récentes -->
<div class="mb-lg">
    <div class="flex items-center justify-between mb-md">
        <h2 class="card-title" style="margin: 0;">Dernières actualités</h2>
        <a href="<?= BASE_URL ?>/actualites.php" class="btn btn-ghost">
            Voir tout <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <?php if (empty($actualites)): ?>
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
        <h3 class="empty-state-title">Aucune actualité</h3>
        <p class="empty-state-text">Les dernières actualités apparaîtront ici.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-3">
        <?php foreach ($actualites as $i => $article): ?>
        <a href="<?= BASE_URL ?>/actualite.php?slug=<?= sanitize($article['slug']) ?>" class="article-card reveal fade-up delay-<?= $i + 1 ?>">
            <div class="article-card-image">
                <?php if ($article['image']): ?>
                <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>">
                <?php else: ?>
                <i class="fas fa-newspaper"></i>
                <?php endif; ?>
            </div>
            <div class="article-card-body">
                <div class="article-card-meta">
                    <span class="badge badge-<?= $article['category'] === 'communique' ? 'danger' : ($article['category'] === 'evenement' ? 'warning' : 'info') ?>">
                        <span class="badge-dot"></span>
                        <?php if ($article['category'] === 'communique'): ?>Communiqué
                        <?php elseif ($article['category'] === 'evenement'): ?>Événement
                        <?php else: ?>Actualité<?php endif; ?>
                    </span>
                    <span class="text-small text-secondary"><?= formatDate($article['created_at']) ?></span>
                </div>
                <h3 class="article-card-title"><?= sanitize($article['title']) ?></h3>
                <p class="article-card-excerpt"><?= sanitize($article['excerpt'] ?? '') ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Services -->
<div>
    <div class="flex items-center justify-between mb-md">
        <h2 class="card-title" style="margin: 0;">Nos divisions</h2>
        <a href="<?= BASE_URL ?>/services.php" class="btn btn-ghost">
            Voir tout <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <?php
    $services_list = $db->fetchAll("SELECT id, title, subtitle, description, icon FROM services WHERE status = 'actif' ORDER BY order_num LIMIT 4");
    if (!empty($services_list)):
    ?>
    <div class="grid grid-4">
        <?php foreach ($services_list as $j => $svc): ?>
        <div class="service-card reveal fade-up delay-<?= $j + 1 ?>">
            <div class="service-card-icon">
                <i class="fas fa-<?= sanitize($svc['icon']) ?>"></i>
            </div>
            <h3 class="service-card-title"><?= sanitize($svc['title']) ?></h3>
            <p class="service-card-text"><?= sanitize($svc['description']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
