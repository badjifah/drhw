<?php
$pageTitle = 'Administrateurs';
require_once __DIR__ . '/../includes/functions.php';
requireSession();
include __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    csrfCheckOrDie();
    $id = (int)($_POST['id'] ?? 0);
    if ($id && $id != Session::get('admin_id')) {
        $db->delete('admins', 'id = :id', ['id' => $id]);
        logActivity('admin_deleted', "Admin ID: $id");
        flash('success', 'Administrateur supprimé.');
    } else {
        flash('danger', 'Impossible de supprimer votre propre compte.');
    }
    redirect(BASE_URL . '/admin/admins.php');
}

$admins = $db->fetchAll("SELECT * FROM admins ORDER BY nom");
?>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-users-gear"></i> Administrateurs</h1>
        <a href="<?= BASE_URL ?>/admin/admin_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter</a>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $a): ?>
                        <tr>
                            <td class="font-medium"><?= sanitize($a['nom']) ?></td>
                            <td class="text-muted">@<?= sanitize($a['login']) ?></td>
                            <td class="text-small"><?= sanitize($a['email']) ?></td>
                            <td><span class="badge badge-<?= $a['role'] === 'super_admin' ? 'warning' : 'primary' ?>"><?= $a['role'] ?? 'admin' ?></span></td>
                            <td class="text-small text-muted"><?= formatDate($a['date_creation'] ?? $a['created_at'] ?? date('Y-m-d')) ?></td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>/admin/admin_form.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                                <?php if ($a['id'] != Session::get('admin_id')): ?>
                                    <form method="POST" style="display: inline-block" onsubmit="return confirm('Supprimer cet administrateur ?')">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
