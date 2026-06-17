<?php
require_once __DIR__ . '/../includes/functions.php';
requireSession();
include __DIR__ . '/includes/admin_header.php';

$query = mb_substr(trim($_GET['q'] ?? ''), 0, 100);
$results = ['actualites' => [], 'documents' => [], 'services' => [], 'messages' => [], 'admins' => []];

if (mb_strlen($query) >= 2) {
    $db = Database::getInstance();
    $param = ['q' => "%{$query}%"];
    
    $results['actualites'] = $db->fetchAll("SELECT * FROM actualites WHERE title LIKE :q OR content LIKE :q ORDER BY created_at DESC LIMIT 10", $param);
    $results['documents'] = $db->fetchAll("SELECT * FROM documents WHERE title LIKE :q OR description LIKE :q ORDER BY created_at DESC LIMIT 10", $param);
    $results['services'] = $db->fetchAll("SELECT * FROM services WHERE title LIKE :q OR description LIKE :q ORDER BY title LIMIT 10", $param);
    $results['messages'] = $db->fetchAll("SELECT * FROM messages WHERE subject LIKE :q OR message LIKE :q OR email LIKE :q ORDER BY created_at DESC LIMIT 10", $param);
    $results['admins'] = $db->fetchAll("SELECT * FROM admins WHERE full_name LIKE :q OR username LIKE :q OR email LIKE :q LIMIT 10", $param);
}

$totalResults = array_sum(array_map('count', $results));
?>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-search"></i> Recherche</h1>
        <span class="badge badge-primary"><?= $totalResults ?> résultat(s)</span>
    </div>

    <form method="GET" class="card" style="margin-bottom: var(--space-xl);">
        <div class="card-body" style="display: flex; gap: var(--space-md); align-items: end;">
            <input type="hidden" name="page" value="search">
            <div style="flex: 1;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="q" value="<?= sanitize($query) ?>" placeholder="Rechercher dans tout le site..." class="form-input" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Rechercher</button>
        </div>
    </form>

    <?php if (!empty($query)): ?>
        <?php foreach (['actualites' => 'Actualités', 'documents' => 'Documents', 'services' => 'Services', 'messages' => 'Messages', 'admins' => 'Administrateurs'] as $key => $label): ?>
            <?php if (!empty($results[$key])): ?>
                <div style="margin-bottom: var(--space-xl);">
                    <h2 style="color: var(--text-secondary); font-size: 1rem; margin-bottom: var(--space-md);"><?= $label ?> (<?= count($results[$key]) ?>)</h2>
                    <div class="card">
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results[$key] as $item): ?>
                                        <tr>
                                            <td class="font-medium"><?= sanitize(truncate($item['title'] ?? $item['subject'] ?? $item['name'] ?? $item['username'] ?? '', 60)) ?></td>
                                            <td class="text-muted text-small"><?= formatDate($item['created_at'] ?? date('Y-m-d')) ?></td>
                                            <td>
                                                <?php if ($key === 'actualites'): ?>
                                                    <a href="?page=actualite_form&id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                                                <?php elseif ($key === 'documents'): ?>
                                                    <a href="?page=document_form&id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                                                <?php elseif ($key === 'services'): ?>
                                                    <a href="?page=service_form&id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($totalResults === 0): ?>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: var(--space-3xl);">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--text-tertiary); margin-bottom: var(--space-md); display: block;"></i>
                    <p class="text-secondary">Aucun résultat trouvé</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
