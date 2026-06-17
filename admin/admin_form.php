<?php
$pageTitle = 'Administrateur';
require_once __DIR__ . '/../includes/functions.php';
requireSession();
include __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);
$admin = $id ? $db->fetch("SELECT * FROM admins WHERE id = :id", ['id' => $id]) : null;
$isEdit = (bool)$admin;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $full_name = trim($_POST['nom'] ?? '');
    $username  = trim($_POST['login'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $role      = $_POST['role'] ?? 'admin';
    $password  = $_POST['password'] ?? '';

    if (empty($full_name) || empty($username) || empty($email)) {
        flash('danger', 'Nom, login et email sont obligatoires.');
        redirect(BASE_URL . "/admin/admin_form.php" . ($id ? "?id=$id" : ''));
    }

    $exists = $db->fetch("SELECT id FROM admins WHERE username = :username AND id != :id", ['username' => $username, 'id' => $id ?? 0]);
    if ($exists) {
        flash('danger', 'Ce login est déjà utilisé.');
        redirect(BASE_URL . "/admin/admin_form.php" . ($id ? "?id=$id" : ''));
    }

    $data = ['full_name' => $full_name, 'username' => $username, 'email' => $email, 'role' => $role];

    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    if ($isEdit) {
        if (empty($password)) unset($data['password']);
        $db->update('admins', $data, 'id = :id', ['id' => $id]);
        logActivity('admin_updated', "Admin: $full_name");
        flash('success', 'Administrateur mis à jour.');
    } else {
        if (empty($password)) {
            flash('danger', 'Le mot de passe est obligatoire pour un nouvel administrateur.');
            redirect(BASE_URL . "/admin/admin_form.php");
        }
        $db->insert('admins', $data);
        logActivity('admin_created', "Admin: $full_name");
        flash('success', 'Administrateur créé.');
    }
    redirect(BASE_URL . '/admin/admins.php');
}
?>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-<?= $isEdit ? 'pen' : 'plus' ?>"></i> <?= $isEdit ? 'Modifier' : 'Ajouter' ?> un administrateur</h1>
        <a href="<?= BASE_URL ?>/admin/admins.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <form method="POST" class="card">
        <?= csrfField() ?>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="nom" value="<?= sanitize($admin['full_name'] ?? '') ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Login *</label>
                    <input type="text" name="login" value="<?= sanitize($admin['username'] ?? '') ?>" class="form-input" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" value="<?= sanitize($admin['email'] ?? '') ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-input">
                        <option value="admin" <?= ($admin['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="superadmin" <?= ($admin['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label"><?= $isEdit ? 'Nouveau mot de passe (laisser vide pour conserver)' : 'Mot de passe *' ?></label>
                <input type="password" name="password" class="form-input" <?= $isEdit ? '' : 'required' ?> minlength="6">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $isEdit ? 'Mettre à jour' : 'Créer' ?></button>
        </div>
    </form>
</div>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
