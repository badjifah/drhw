<?php
$pageTitle = 'Paramètres';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $keys = ['site_name', 'site_description', 'site_email', 'site_phone', 'site_address', 'directeur_prenom', 'directeur_name', 'directeur_grade', 'directeur_title', 'directeur_message', 'directeur_photo'];
    foreach ($keys as $key) {
        if ($key === 'directeur_photo' && !empty($_FILES['directeur_photo']['tmp_name'])) {
            $uploaded = uploadFile($_FILES['directeur_photo'], 'directeur');
            if ($uploaded) $value = $uploaded;
            else { flash('danger', 'Erreur upload photo.'); redirect(BASE_URL . '/admin/settings.php'); }
        } else {
            $value = $_POST[$key] ?? '';
        }
        $existing = $db->fetch("SELECT id FROM settings WHERE key_name = :key", ['key' => $key]);
        if ($existing) {
            $db->update('settings', ['value' => $value], 'key_name = :key', ['key' => $key]);
        } else {
            $db->insert('settings', ['key_name' => $key, 'value' => $value]);
        }
    }
    logActivity('settings_updated', 'Paramètres modifiés');
    flash('success', 'Paramètres mis à jour avec succès.');
    redirect(BASE_URL . '/admin/settings.php');
}

$settings = [];
$rows = $db->fetchAll("SELECT * FROM settings ORDER BY key_name");
foreach ($rows as $r) {
    $settings[$r['key_name']] = $r['value'];
}
?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h2 class="card-title">Paramètres du site</h2>
    </div>
    <form method="POST" class="card-body" enctype="multipart/form-data">
        <?= csrfField() ?>
        <div class="flex flex-col gap-md">
            <div>
                <p style="font-weight: 600; font-size: 15px; color: var(--text-primary); margin: 0 0 var(--space-md) 0; padding-bottom: var(--space-sm); border-bottom: 1px solid var(--border-light);">Informations générales</p>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Nom du site</label>
                        <input type="text" name="site_name" value="<?= sanitize($settings['site_name'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email de contact</label>
                        <input type="email" name="site_email" value="<?= sanitize($settings['site_email'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="site_phone" value="<?= sanitize($settings['site_phone'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="site_address" value="<?= sanitize($settings['site_address'] ?? '') ?>" class="form-input">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <div class="form-group">
                            <label class="form-label">Description du site</label>
                            <textarea name="site_description" rows="3" class="form-textarea"><?= sanitize($settings['site_description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p style="font-weight: 600; font-size: 15px; color: var(--text-primary); margin: 0 0 var(--space-md) 0; padding-bottom: var(--space-sm); border-bottom: 1px solid var(--border-light);">Mot du Directeur</p>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="directeur_prenom" value="<?= sanitize($settings['directeur_prenom'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <input type="text" name="directeur_name" value="<?= sanitize($settings['directeur_name'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Grade / Fonction</label>
                        <input type="text" name="directeur_grade" value="<?= sanitize($settings['directeur_grade'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Titre</label>
                        <input type="text" name="directeur_title" value="<?= sanitize($settings['directeur_title'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Photo</label>
                        <input type="file" name="directeur_photo" accept="image/*" class="form-input">
                        <?php if (!empty($settings['directeur_photo'])): ?>
                            <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
                                <img src="<?= BASE_URL ?>/assets/uploads/directeur/<?= $settings['directeur_photo'] ?>" alt="Photo actuelle" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--border-primary);">
                                <span class="text-small text-muted">Photo actuelle</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <div class="form-group">
                            <label class="form-label">Message du Directeur</label>
                            <textarea name="directeur_message" rows="5" class="form-textarea"><?= sanitize($settings['directeur_message'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-light); padding-top: var(--space-md);">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les paramètres
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
