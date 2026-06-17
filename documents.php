<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$cat = $_GET['categorie'] ?? '';

$where = '1';
$params = [];
if ($cat) {
    $where = 'category = :category';
    $params['category'] = $cat;
}

$pagination = paginate(
    "SELECT * FROM documents WHERE $where ORDER BY created_at DESC",
    $params,
    9
);
$documents = $pagination['items'];

$categories = $db->fetchAll("SELECT DISTINCT category FROM documents WHERE category IS NOT NULL AND category != '' ORDER BY category");

$pageTitle = 'Espace documents';
$pageDescription = 'Téléchargez les documents officiels, formulaires et publications de la DRH.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <span class="sep">/</span>
        <span>Documents</span>
    </div>
    <h1 class="page-title">Espace documents</h1>
    <p class="page-subtitle">Formulaires, textes officiels, rapports et publications</p>
</div>

<?php if (!empty($categories)): ?>
<div class="tabs">
    <a href="<?= BASE_URL ?>/documents.php" class="tab <?= !$cat ? 'active' : '' ?>">Tous</a>
    <?php foreach ($categories as $c): ?>
    <a href="<?= BASE_URL ?>/documents.php?categorie=<?= urlencode($c['category']) ?>" class="tab <?= $cat === $c['category'] ? 'active' : '' ?>"><?= sanitize($c['category']) ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($documents)): ?>
<div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-folder-open"></i></div>
    <h3 class="empty-state-title">Aucun document disponible</h3>
    <p class="empty-state-text">Aucun document n'a été publié pour le moment.</p>
</div>
<?php else: ?>
<div class="grid grid-3">
    <?php foreach ($documents as $i => $doc): ?>
    <div class="doc-card reveal fade-up delay-<?= ($i % 3) + 1 ?>">
        <div class="doc-card-icon">
            <i class="fas fa-file-pdf"></i>
        </div>
        <div class="doc-card-info">
            <p class="doc-card-title"><?= sanitize($doc['title']) ?></p>
            <p class="doc-card-meta">
                <?php if ($doc['file_size']): ?><?= sanitize($doc['file_size']) ?> • <?php endif; ?>
                <?= (int)$doc['downloads'] ?> téléchargement(s)
            </p>
            <a href="<?= BASE_URL ?>/api/download.php?id=<?= (int)$doc['id'] ?>" class="btn btn-ghost btn-sm" style="margin-top: 8px;">
                <i class="fas fa-download"></i> Télécharger
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php
$baseUrl = '?';
if ($cat) $baseUrl = '?categorie=' . urlencode($cat);
renderPagination($pagination, $baseUrl);
?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
