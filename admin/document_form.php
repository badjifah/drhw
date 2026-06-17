<?php
$pageTitle = 'Document';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);
$doc = ['title' => '', 'description' => '', 'category' => '', 'file_path' => ''];

if ($id) {
    $existing = $db->fetch("SELECT * FROM documents WHERE id = :id", ['id' => $id]);
    if ($existing) $doc = $existing;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $data = [
        'title' => sanitize($_POST['title'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'category' => sanitize($_POST['category'] ?? ''),
    ];

    if (empty($data['title'])) {
        flash('danger', 'Le titre est requis.');
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadFile($_FILES['file'], 'documents');
            if ($uploaded) {
                if ($doc['file_path']) {
                    $oldFile = UPLOAD_PATH . '/' . $doc['file_path'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
                $data['file_path'] = $uploaded;
                $data['file_size'] = formatFileSize($_FILES['file']['size']);
                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $data['file_type'] = $ext;
            }
        } elseif (!$id) {
            flash('danger', 'Veuillez sélectionner un fichier.');
        }

        if ($id) {
            $db->update('documents', $data, 'id = :id', ['id' => $id]);
            logActivity('document_updated', "Document: {$data['title']}");
            flash('success', 'Document mis à jour avec succès.');
        } else {
            $db->insert('documents', $data);
            logActivity('document_uploaded', "Document: {$data['title']}");
            flash('success', 'Document ajouté avec succès.');
        }
        redirect(BASE_URL . '/admin/documents.php');
    }
}
?>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h2 class="card-title"><?= $id ? 'Modifier' : 'Nouveau' ?> document</h2>
        <a href="<?= BASE_URL ?>/admin/documents.php" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    <form method="POST" enctype="multipart/form-data" class="card-body">
        <?= csrfField() ?>
        <div class="flex flex-col gap-md">
            <div class="form-group">
                <label class="form-label">Titre <span style="color: var(--color-danger);">*</span></label>
                <input type="text" name="title" value="<?= sanitize($doc['title']) ?>" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-textarea"><?= sanitize($doc['description']) ?></textarea>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <input type="text" name="category" value="<?= sanitize($doc['category']) ?>" class="form-input" placeholder="Ex: Formulaire, Rapport">
                </div>
                <div class="form-group">
                    <label class="form-label">Fichier <?= !$id ? '<span style="color: var(--color-danger);">*</span>' : '' ?></label>
                    <input type="file" name="file" <?= !$id ? 'required' : '' ?> class="form-input" style="padding: 8px;">
                    <?php if ($doc['file_path']): ?>
                    <p class="text-small text-secondary mt-sm">Fichier : <?= sanitize($doc['file_path']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="border-top: 1px solid var(--border-light); padding-top: var(--space-md);" class="flex gap-sm">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $id ? 'Mettre à jour' : 'Ajouter' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/documents.php" class="btn btn-outline">Annuler</a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
