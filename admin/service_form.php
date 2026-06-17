<?php
$pageTitle = 'Service';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);
$svc = ['title' => '', 'subtitle' => '', 'description' => '', 'icon' => 'building', 'missions' => '', 'chef_name' => '', 'chef_title' => '', 'order_num' => 0, 'status' => 'actif'];

if ($id) {
    $existing = $db->fetch("SELECT * FROM services WHERE id = :id", ['id' => $id]);
    if ($existing) $svc = $existing;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $data = [
        'title' => sanitize($_POST['title'] ?? ''),
        'subtitle' => sanitize($_POST['subtitle'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'icon' => sanitize($_POST['icon'] ?? 'building'),
        'missions' => sanitize($_POST['missions'] ?? ''),
        'chef_name' => sanitize($_POST['chef_name'] ?? ''),
        'chef_title' => sanitize($_POST['chef_title'] ?? ''),
        'order_num' => (int)($_POST['order_num'] ?? 0),
        'status' => sanitize($_POST['status'] ?? 'actif'),
    ];

    if (empty($data['title'])) {
        flash('danger', 'Le titre est requis.');
    } else {
        if ($id) {
            $db->update('services', $data, 'id = :id', ['id' => $id]);
            logActivity('service_updated', "Service: {$data['title']}");
            flash('success', 'Service mis à jour avec succès.');
        } else {
            $db->insert('services', $data);
            logActivity('service_created', "Service: {$data['title']}");
            flash('success', 'Service créé avec succès.');
        }
        redirect(BASE_URL . '/admin/services.php');
    }
}
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h2 class="card-title"><?= $id ? 'Modifier' : 'Nouveau' ?> service</h2>
        <a href="<?= BASE_URL ?>/admin/services.php" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    <form method="POST" class="card-body">
        <?= csrfField() ?>
        <div class="flex flex-col gap-md">
            <div class="form-group">
                <label class="form-label">Titre <span style="color: var(--color-danger);">*</span></label>
                <input type="text" name="title" value="<?= sanitize($svc['title']) ?>" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Sous-titre</label>
                <input type="text" name="subtitle" value="<?= sanitize($svc['subtitle']) ?>" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" rows="5" class="form-textarea"><?= sanitize($svc['description']) ?></textarea>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Icône (Font Awesome)</label>
                    <input type="text" name="icon" value="<?= sanitize($svc['icon']) ?>" class="form-input" placeholder="ex: user-tie, building">
                </div>
                <div class="form-group">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="order_num" value="<?= (int)$svc['order_num'] ?>" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Missions (séparées par des points-virgules)</label>
                <textarea name="missions" rows="3" class="form-textarea" placeholder="Mission 1;Mission 2;Mission 3"><?= sanitize($svc['missions']) ?></textarea>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Responsable (nom)</label>
                    <input type="text" name="chef_name" value="<?= sanitize($svc['chef_name']) ?>" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Titre du responsable</label>
                    <input type="text" name="chef_title" value="<?= sanitize($svc['chef_title']) ?>" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="actif" <?= $svc['status'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= $svc['status'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            <div style="border-top: 1px solid var(--border-light); padding-top: var(--space-md);" class="flex gap-sm">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= $id ? 'Mettre à jour' : 'Créer' ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/services.php" class="btn btn-outline">Annuler</a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
