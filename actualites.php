<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$category = $_GET['categorie'] ?? '';

$where = "status = 'publie'";
$params = [];
if ($category && in_array($category, ['actualite', 'evenement', 'communique'])) {
    $where .= " AND category = :category";
    $params['category'] = $category;
}

$pagination = paginate(
    "SELECT id, title, slug, content, excerpt, image, category, created_at FROM actualites WHERE $where ORDER BY created_at DESC",
    $params,
    6
);
$actualites = $pagination['items'];

$pageTitle = 'Actualités';
$pageDescription = 'Retrouvez toutes les actualités, événements et communiqués de la DRH.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <span class="sep">/</span>
        <span>Actualités</span>
    </div>
    <h1 class="page-title">Actualités RH</h1>
    <p class="page-subtitle">Annonces, événements et communiqués officiels</p>
</div>

<!-- Filter tabs -->
<div class="tabs">
    <a href="<?= BASE_URL ?>/actualites.php" class="tab <?= !$category ? 'active' : '' ?>">Toutes</a>
    <a href="<?= BASE_URL ?>/actualites.php?categorie=actualite" class="tab <?= $category === 'actualite' ? 'active' : '' ?>">Actualités</a>
    <a href="<?= BASE_URL ?>/actualites.php?categorie=evenement" class="tab <?= $category === 'evenement' ? 'active' : '' ?>">Événements</a>
    <a href="<?= BASE_URL ?>/actualites.php?categorie=communique" class="tab <?= $category === 'communique' ? 'active' : '' ?>">Communiqués</a>
</div>

<?php if (empty($actualites)): ?>
<div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
    <h3 class="empty-state-title">Aucun article trouvé</h3>
    <p class="empty-state-text">Aucune actualité ne correspond à votre sélection.</p>
</div>
<?php else: ?>
<div class="grid grid-3">
    <?php foreach ($actualites as $i => $article): ?>
    <a href="<?= BASE_URL ?>/actualite.php?slug=<?= sanitize($article['slug']) ?>" class="article-card reveal fade-up delay-<?= ($i % 3) + 1 ?>">
        <div class="article-card-image">
            <?php if ($article['image']): ?>
            <img src="<?= BASE_URL ?>/assets/uploads/<?= sanitize($article['image']) ?>" alt="<?= sanitize($article['title']) ?>">
            <?php else: ?>
            <i class="fas fa-newspaper"></i>
            <?php endif; ?>
        </div>
        <div class="article-card-body">
            <div class="article-card-meta">
                <span class="badge badge-<?= categoryBadgeClass($article['category']) ?>">
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
<?php
$baseUrl = '?';
if ($category) $baseUrl = '?categorie=' . urlencode($category);
renderPagination($pagination, $baseUrl);
?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
