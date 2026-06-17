<?php
$pageTitle = 'Gestion des actualités';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $article = $db->fetch("SELECT image FROM actualites WHERE id = :id", ['id' => $id]);
        if ($article && $article['image']) {
            $filePath = UPLOAD_PATH . '/' . $article['image'];
            if (file_exists($filePath)) unlink($filePath);
        }
        $db->delete('actualites', 'id = :id', ['id' => $id]);
        logActivity('article_deleted', "Article ID: $id");
        flash('success', 'Actualité supprimée avec succès.');
        redirect(BASE_URL . '/admin/actualites.php');
    }
}

$pagination = paginate("SELECT a.*, adm.full_name as author FROM actualites a LEFT JOIN admins adm ON a.author_id = adm.id ORDER BY a.created_at DESC", [], 10);
$actualites = $pagination['items'];
?>

<div>
    <div class="flex items-center justify-between mb-md">
        <span class="text-small text-secondary"><?= count($actualites) ?> actualité(s)</span>
        <a href="<?= BASE_URL ?>/admin/actualite_form.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouvelle actualité
        </a>
    </div>

    <div class="card">
        <?php if (empty($actualites)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-newspaper"></i></div>
            <h3 class="empty-state-title">Aucune actualité</h3>
            <p class="empty-state-text">Créez votre première actualité.</p>
            <a href="<?= BASE_URL ?>/admin/actualite_form.php" class="btn btn-primary">Créer une actualité</a>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Auteur</th>
                        <th>Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actualites as $a): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= sanitize(truncate($a['title'], 60)) ?></td>
                        <td>
                            <span class="badge badge-<?= $a['category'] === 'communique' ? 'danger' : ($a['category'] === 'evenement' ? 'warning' : 'info') ?>">
                                <span class="badge-dot"></span>
                                <?php if ($a['category'] === 'communique'): ?>Communiqué
                                <?php elseif ($a['category'] === 'evenement'): ?>Événement
                                <?php else: ?>Actualité<?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= $a['status'] === 'publie' ? 'success' : 'neutral' ?>">
                                <span class="badge-dot"></span>
                                <?= $a['status'] === 'publie' ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td class="text-secondary"><?= sanitize($a['author'] ?? '—') ?></td>
                        <td class="text-secondary"><?= formatDate($a['created_at']) ?></td>
                        <td style="text-align: right;">
                            <a href="<?= BASE_URL ?>/admin/actualite_form.php?id=<?= $a['id'] ?>" class="btn btn-ghost btn-sm">Modifier</a>
                            <form method="POST" action="" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        <?= renderPagination($pagination, '?') ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
