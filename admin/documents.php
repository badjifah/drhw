<?php
$pageTitle = 'Gestion des documents';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $doc = $db->fetch("SELECT file_path FROM documents WHERE id = :id", ['id' => $id]);
        if ($doc && $doc['file_path']) {
            $filePath = UPLOAD_PATH . '/' . $doc['file_path'];
            if (file_exists($filePath)) unlink($filePath);
        }
        $db->delete('documents', 'id = :id', ['id' => $id]);
        logActivity('document_deleted', "Document ID: $id");
        flash('success', 'Document supprimé avec succès.');
        redirect(BASE_URL . '/admin/documents.php');
    }
}

$pagination = paginate("SELECT * FROM documents ORDER BY created_at DESC", [], 10);
$documents = $pagination['items'];
?>

<div>
    <div class="flex items-center justify-between mb-md">
        <span class="text-small text-secondary"><?= count($documents) ?> document(s)</span>
        <a href="<?= BASE_URL ?>/admin/document_form.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouveau document
        </a>
    </div>

    <div class="card">
        <?php if (empty($documents)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-folder-open"></i></div>
            <h3 class="empty-state-title">Aucun document</h3>
            <p class="empty-state-text">Ajoutez votre premier document.</p>
            <a href="<?= BASE_URL ?>/admin/document_form.php" class="btn btn-primary">Ajouter un document</a>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Taille</th>
                        <th>Téléchargements</th>
                        <th>Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $d): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= sanitize($d['title']) ?></td>
                        <td>
                            <?php if ($d['category']): ?>
                            <span class="badge badge-neutral"><?= sanitize($d['category']) ?></span>
                            <?php else: ?>
                            <span class="text-secondary">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-secondary"><?= sanitize($d['file_size'] ?? '—') ?></td>
                        <td class="text-secondary"><?= (int)$d['downloads'] ?></td>
                        <td class="text-secondary"><?= formatDate($d['created_at']) ?></td>
                        <td style="text-align: right;">
                            <a href="<?= BASE_URL ?>/admin/document_form.php?id=<?= $d['id'] ?>" class="btn btn-ghost btn-sm">Modifier</a>
                            <form method="POST" action="" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
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
