<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$slug = $_GET['slug'] ?? '';

$article = $db->fetch(
    "SELECT a.*, adm.full_name as author_name
     FROM actualites a
     LEFT JOIN admins adm ON a.author_id = adm.id
     WHERE a.slug = :slug AND a.status = 'publie'",
    ['slug' => $slug]
);

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    $pageTitle = 'Article non trouvé';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-exclamation-circle"></i></div>
        <h3 class="empty-state-title">Article non trouvé</h3>
        <p class="empty-state-text">L\'article que vous recherchez n\'existe pas ou a été retiré.</p>
        <a href="' . BASE_URL . '/actualites.php" class="btn btn-primary">Retour aux actualités</a>
    </div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = sanitize($article['title']);
$pageDescription = sanitize($article['excerpt'] ?? '');
require_once __DIR__ . '/includes/header.php';
?>

<div class="flex items-center gap-sm mb-md">
    <a href="<?= BASE_URL ?>/actualites.php" class="btn btn-ghost btn-sm">
        <i class="fas fa-arrow-left"></i> Retour aux actualités
    </a>
</div>

<div class="card">
    <?php if ($article['image']): ?>
    <div style="height: 300px; overflow: hidden; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
        <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <?php endif; ?>
    <div class="card-body">
        <div class="flex items-center gap-sm mb-md">
            <span class="badge badge-<?= $article['category'] === 'communique' ? 'danger' : ($article['category'] === 'evenement' ? 'warning' : 'info') ?>">
                <span class="badge-dot"></span>
                <?php if ($article['category'] === 'communique'): ?>Communiqué
                <?php elseif ($article['category'] === 'evenement'): ?>Événement
                <?php else: ?>Actualité<?php endif; ?>
            </span>
            <span class="text-small text-secondary">
                <i class="far fa-calendar"></i> <?= formatDateLong($article['created_at']) ?>
            </span>
            <?php if ($article['author_name']): ?>
            <span class="text-small text-secondary">
                <i class="far fa-user"></i> <?= sanitize($article['author_name']) ?>
            </span>
            <?php endif; ?>
        </div>

        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 var(--space-lg) 0; line-height: 1.3;">
            <?= sanitize($article['title']) ?>
        </h1>

        <div style="color: var(--text-primary); line-height: 1.8; font-size: 15px;">
            <?= $article['content'] ?>
        </div>

        <div style="margin-top: var(--space-xl); padding-top: var(--space-lg); border-top: 1px solid var(--border-light);">
            <div class="flex items-center gap-sm">
                <span class="text-small text-secondary">Partager :</span>
                <a href="https://www.facebook.com/sharer.php?u=<?= urlencode(BASE_URL . '/actualite.php?slug=' . $article['slug']) ?>" target="_blank" class="topbar-btn" style="text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/actualite.php?slug=' . $article['slug']) ?>" target="_blank" class="topbar-btn" style="text-decoration: none;"><i class="fab fa-twitter"></i></a>
                <a href="https://www.linkedin.com/shareArticle?url=<?= urlencode(BASE_URL . '/actualite.php?slug=' . $article['slug']) ?>" target="_blank" class="topbar-btn" style="text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
