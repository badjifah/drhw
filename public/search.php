<?php require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';

$query = trim($_GET['q'] ?? '');
$results = ['actualites' => [], 'documents' => [], 'services' => []];

if (mb_strlen($query) >= 2) {
    $db = Database::getInstance();
    $param = ['q' => "%{$query}%"];
    
    $results['actualites'] = $db->fetchAll(
        "SELECT * FROM actualites WHERE statut = 'publie' AND (titre LIKE :q OR contenu LIKE :q) ORDER BY created_at DESC LIMIT 10", $param
    );
    $results['documents'] = $db->fetchAll(
        "SELECT * FROM documents WHERE titre LIKE :q OR description LIKE :q ORDER BY created_at DESC LIMIT 10", $param
    );
    $results['services'] = $db->fetchAll(
        "SELECT * FROM services WHERE actif = 1 AND (nom LIKE :q OR description LIKE :q) ORDER BY nom LIMIT 10", $param
    );
}

$totalResults = count($results['actualites']) + count($results['documents']) + count($results['services']);
?>
<main class="main-content">
    <div class="container" style="padding-top: calc(var(--topbar-height) + var(--space-2xl)); padding-bottom: var(--space-3xl);">
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Accueil</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span class="current">Recherche</span>
        </nav>

        <div style="max-width: 700px; margin: 0 auto var(--space-xl);">
            <form method="GET" action="" class="search-bar" style="position: relative;">
                <input type="hidden" name="page" value="search">
                <input type="text" name="q" value="<?= sanitize($query) ?>" placeholder="Rechercher actualités, documents, services..." style="width: 100%; padding: var(--space-md) var(--space-lg) var(--space-md) 50px; border: 1px solid var(--border-primary); border-radius: var(--radius-xl); background: var(--bg-glass); color: var(--text-primary); font-size: 1rem;" required>
                <i class="fas fa-search" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary);"></i>
            </form>
        </div>

        <?php if (!empty($query)): ?>
            <div style="margin-bottom: var(--space-xl);">
                <span class="text-secondary"><?= $totalResults ?> résultat(s) pour « <?= sanitize($query) ?> »</span>
            </div>

            <?php if (!empty($results['actualites'])): ?>
                <section style="margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-primary); font-size: 1.2rem; margin-bottom: var(--space-md);"><i class="fas fa-newspaper"></i> Actualités</h2>
                    <div class="card-grid">
                        <?php foreach ($results['actualites'] as $a): ?>
                            <div class="card card-hover tilt-card" style="cursor: pointer;" onclick="location.href='<?= BASE_URL ?>/?page=news-detail&id=<?= $a['id'] ?>'">
                                <?php if (!empty($a['image'])): ?>
                                    <div class="card-image"><img src="<?= BASE_URL ?>/assets/uploads/actualites/<?= $a['image'] ?>" alt="<?= sanitize($a['titre']) ?>"></div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="badge badge-primary"><?= formatDate($a['created_at']) ?></span>
                                    <h3 class="card-title" style="margin-top: var(--space-sm);"><?= sanitize(truncate($a['titre'], 80)) ?></h3>
                                    <p class="card-text"><?= sanitize(truncate($a['contenu'], 120)) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($results['documents'])): ?>
                <section style="margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-secondary); font-size: 1.2rem; margin-bottom: var(--space-md);"><i class="fas fa-folder-open"></i> Documents</h2>
                    <div class="card-grid">
                        <?php foreach ($results['documents'] as $d): ?>
                            <div class="card card-hover tilt-card">
                                <div class="card-body">
                                    <div class="file-icon" style="margin-bottom: var(--space-md);"><i class="fas <?= getFileIcon($d['fichier']) ?>" style="font-size: 2rem; color: <?= getFileColor($d['fichier']) ?>;"></i></div>
                                    <h3 class="card-title" style="font-size: 0.95rem;"><?= sanitize(truncate($d['titre'], 60)) ?></h3>
                                    <a href="<?= BASE_URL ?>/api/download.php?id=<?= $d['id'] ?>" class="btn btn-outline btn-sm" style="margin-top: var(--space-sm);"><i class="fas fa-download"></i> Télécharger</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($results['services'])): ?>
                <section style="margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--color-accent); font-size: 1.2rem; margin-bottom: var(--space-md);"><i class="fas fa-concierge-bell"></i> Services</h2>
                    <div class="card-grid">
                        <?php foreach ($results['services'] as $s): ?>
                            <div class="card card-hover tilt-card">
                                <div class="card-body">
                                    <div class="file-icon" style="margin-bottom: var(--space-md);"><i class="fas <?= $s['icone'] ?? 'fa-cog' ?>" style="font-size: 1.8rem; color: var(--color-primary);"></i></div>
                                    <h3 class="card-title" style="font-size: 1rem;"><?= sanitize($s['nom']) ?></h3>
                                    <p class="card-text"><?= sanitize(truncate($s['description'], 100)) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($totalResults === 0): ?>
                <div style="text-align: center; padding: var(--space-3xl) 0;">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--text-tertiary); margin-bottom: var(--space-md);"></i>
                    <p class="text-secondary">Aucun résultat trouvé pour « <?= sanitize($query) ?> »</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: var(--space-3xl) 0;">
                <i class="fas fa-search" style="font-size: 3rem; color: var(--text-tertiary); margin-bottom: var(--space-md);"></i>
                <p class="text-secondary">Entrez au moins 2 caractères pour lancer une recherche</p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
