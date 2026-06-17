<?php
$pageTitle = 'Actualité';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);
$article = ['title' => '', 'slug' => '', 'content' => '', 'excerpt' => '', 'category' => 'actualite', 'status' => 'brouillon', 'image' => ''];

if ($id) {
    $existing = $db->fetch("SELECT * FROM actualites WHERE id = :id", ['id' => $id]);
    if ($existing) $article = $existing;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $data = [
        'title' => sanitize($_POST['title'] ?? ''),
        'slug' => !empty($_POST['slug']) ? sanitize($_POST['slug']) : generateSlug($_POST['title'] ?? ''),
        'content' => $_POST['content'] ?? '',
        'excerpt' => sanitize($_POST['excerpt'] ?? ''),
        'category' => sanitize($_POST['category'] ?? 'actualite'),
        'status' => sanitize($_POST['status'] ?? 'brouillon'),
        'author_id' => Session::get('admin_id'),
    ];

    if (empty($data['title'])) {
        flash('danger', 'Le titre est requis.');
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadFile($_FILES['image'], 'actualites');
            if ($uploaded) {
                if ($article['image']) {
                    $oldFile = UPLOAD_PATH . '/' . $article['image'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
                $data['image'] = $uploaded;
            }
        }

        if ($id) {
            $db->update('actualites', $data, 'id = :id', ['id' => $id]);
            logActivity('article_updated', "Article: {$data['title']}");
            flash('success', 'Actualité mise à jour avec succès.');
        } else {
            $id = $db->insert('actualites', $data);
            logActivity('article_created', "Article: {$data['title']}");
            flash('success', 'Actualité créée avec succès.');
        }
        redirect(BASE_URL . '/admin/actualites.php');
    }
}
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h2 class="card-title"><?= $id ? 'Modifier' : 'Nouvelle' ?> actualité</h2>
        <a href="<?= BASE_URL ?>/admin/actualites.php" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    <form method="POST" enctype="multipart/form-data" class="card-body">
        <?= csrfField() ?>
        <div class="flex flex-col gap-md">
            <div class="form-group">
                <label class="form-label">Titre <span style="color: var(--color-danger);">*</span></label>
                <input type="text" name="title" value="<?= sanitize($article['title']) ?>" required class="form-input" data-generate-slug>
            </div>
            <div class="form-group">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" value="<?= sanitize($article['slug']) ?>" class="form-input" data-slug-target>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="actualite" <?= $article['category'] === 'actualite' ? 'selected' : '' ?>>Actualité</option>
                        <option value="evenement" <?= $article['category'] === 'evenement' ? 'selected' : '' ?>>Événement</option>
                        <option value="communique" <?= $article['category'] === 'communique' ? 'selected' : '' ?>>Communiqué</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="brouillon" <?= $article['status'] === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="publie" <?= $article['status'] === 'publie' ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Extrait (résumé)</label>
                <textarea name="excerpt" rows="3" class="form-textarea"><?= sanitize($article['excerpt']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Contenu</label>
                <textarea name="content" rows="15" class="form-textarea" style="min-height: 300px;"><?= sanitize($article['content']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" accept="image/*" class="form-input" style="padding: 8px;">
                <?php if ($article['image']): ?>
                <p class="text-small text-secondary mt-sm">Image actuelle : <?= sanitize($article['image']) ?></p>
                <?php endif; ?>
            </div>
            <div style="border-top: 1px solid var(--border-light); padding-top: var(--space-md);" class="flex gap-sm">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $id ? 'Mettre à jour' : 'Publier' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/actualites.php" class="btn btn-outline">Annuler</a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
