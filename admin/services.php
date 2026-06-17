<?php
$pageTitle = 'Gestion des services';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $db->delete('services', 'id = :id', ['id' => $id]);
        logActivity('service_deleted', "Service ID: $id");
        flash('success', 'Service supprimé avec succès.');
        redirect(BASE_URL . '/admin/services.php');
    }
}

$pagination = paginate("SELECT * FROM services ORDER BY order_num", [], 10);
$services = $pagination['items'];
?>

<div>
    <div class="flex items-center justify-between mb-md">
        <span class="text-small text-secondary"><?= count($services) ?> service(s)</span>
        <a href="<?= BASE_URL ?>/admin/service_form.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouveau service
        </a>
    </div>

    <div class="card">
        <?php if (empty($services)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-building"></i></div>
            <h3 class="empty-state-title">Aucun service</h3>
            <p class="empty-state-text">Ajoutez votre premier service.</p>
            <a href="<?= BASE_URL ?>/admin/service_form.php" class="btn btn-primary">Ajouter un service</a>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Titre</th>
                        <th>Sous-titre</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                    <tr>
                        <td class="text-secondary"><?= (int)$s['order_num'] ?></td>
                        <td style="font-weight: 500;"><?= sanitize($s['title']) ?></td>
                        <td class="text-secondary"><?= sanitize($s['subtitle'] ?? '—') ?></td>
                        <td>
                            <span class="badge badge-<?= $s['status'] === 'actif' ? 'success' : 'neutral' ?>">
                                <span class="badge-dot"></span>
                                <?= $s['status'] === 'actif' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= BASE_URL ?>/admin/service_form.php?id=<?= $s['id'] ?>" class="btn btn-ghost btn-sm">Modifier</a>
                            <form method="POST" action="" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
