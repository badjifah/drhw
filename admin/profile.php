<?php
require_once __DIR__ . '/../includes/functions.php';
requireSession();
include __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();
$adminId = Session::get('admin_id');
$admin = $db->fetch("SELECT * FROM admins WHERE id = :id", ['id' => $adminId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!password_verify($currentPassword, $admin['password'])) {
        flash('danger', 'Mot de passe actuel incorrect.');
        redirect('?page=profile');
    }

    if (empty($newPassword)) {
        flash('warning', 'Aucun changement de mot de passe.');
        redirect('?page=profile');
    }

    if ($newPassword !== $confirmPassword) {
        flash('danger', 'Les mots de passe ne correspondent pas.');
        redirect('?page=profile');
    }

    if (strlen($newPassword) < 6) {
        flash('danger', 'Le mot de passe doit contenir au moins 6 caractères.');
        redirect('?page=profile');
    }

    $db->update('admins', ['password' => password_hash($newPassword, PASSWORD_BCRYPT)], 'id = :id', ['id' => $adminId]);
    logActivity('password_changed', 'Mot de passe modifié');
    flash('success', 'Mot de passe mis à jour avec succès.');
    redirect('?page=profile');
}
?>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-shield"></i> Mon profil</h1>
    </div>

    <div style="max-width: 600px;">
        <div class="card" style="margin-bottom: var(--space-xl);">
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: var(--space-lg); margin-bottom: var(--space-xl);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; font-weight: 700;">
                        <?= strtoupper(substr($admin['nom'], 0, 2)) ?>
                    </div>
                    <div>
                        <h3 style="color: var(--text-primary); margin: 0;"><?= sanitize($admin['nom']) ?></h3>
                        <p class="text-muted text-small">@<?= sanitize($admin['login']) ?> · <?= sanitize($admin['email']) ?></p>
                        <span class="badge badge-<?= $admin['role'] === 'super_admin' ? 'warning' : 'primary' ?>"><?= $admin['role'] ?? 'admin' ?></span>
                    </div>
                </div>

                <form method="POST">
                    <?= csrfField() ?>
                    <div class="form-group">
                        <label class="form-label">Mot de passe actuel *</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe *</label>
                        <input type="password" name="new_password" class="form-input" minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmer le nouveau mot de passe *</label>
                        <input type="password" name="confirm_password" class="form-input" minlength="6">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Modifier le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
