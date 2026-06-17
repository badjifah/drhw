<?php
$pageTitle = 'Journal des activités';
require_once __DIR__ . '/../includes/functions.php';
requireSession();
include __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear_all') {
    csrfCheckOrDie();
    $db->query("TRUNCATE TABLE activity_logs");
    logActivity('logs_cleared', 'Journal des activités effacé');
    flash('success', 'Journal effacé.');
    redirect(BASE_URL . '/admin/activity_logs.php');
}

$pagination = paginate(
    "SELECT al.*, a.nom as admin_nom FROM activity_logs al LEFT JOIN admins a ON al.admin_id = a.id ORDER BY al.created_at DESC",
    [], 20
);
$logs = $pagination['items'];
?>
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-clock-rotate-left"></i> Journal des activités</h1>
        <form method="POST" onsubmit="return confirm('Tout effacer ?')">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="clear_all">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Tout effacer</button>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Détails</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-small text-muted"><?= formatDate($log['created_at'], 'd/m/Y H:i') ?></td>
                            <td class="font-medium"><?= sanitize($log['admin_nom'] ?? 'Système') ?></td>
                            <td>
                                <span class="badge badge-primary"><?= sanitize($log['action']) ?></span>
                            </td>
                            <td class="text-small text-muted"><?= sanitize(truncate($log['details'] ?? '', 80)) ?></td>
                            <td class="text-small text-muted font-mono"><?= sanitize($log['ip_address'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="5" class="text-center text-muted" style="padding: var(--space-xl);">Aucune activité enregistrée</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?= renderPagination($pagination, '?') ?>
</div>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
