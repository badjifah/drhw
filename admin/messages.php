<?php
$pageTitle = 'Messages reçus';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'delete' && $id) {
        $db->delete('messages', 'id = :id', ['id' => $id]);
        logActivity('message_deleted', "Message ID: $id");
        flash('success', 'Message supprimé.');
        redirect(BASE_URL . '/admin/messages.php');
    } elseif ($action === 'lu' && $id) {
        $db->update('messages', ['lu' => true], 'id = :id', ['id' => $id]);
        logActivity('message_read', "Message ID: $id");
        redirect(BASE_URL . '/admin/messages.php');
    }
}

$pagination = paginate("SELECT * FROM messages ORDER BY lu ASC, created_at DESC", [], 15);
$messages = $pagination['items'];
?>

<div>
    <span class="text-small text-secondary mb-md" style="display: block;"><?= count($messages) ?> message(s)</span>

    <div class="card">
        <?php if (empty($messages)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-envelope-open-text"></i></div>
            <h3 class="empty-state-title">Aucun message</h3>
            <p class="empty-state-text">Les messages du formulaire de contact apparaîtront ici.</p>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Expéditeur</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $m): ?>
                    <tr style="<?= !$m['lu'] ? 'background: var(--color-primary-bg);' : '' ?>">
                        <td style="font-weight: 500;">
                            <?= sanitize($m['name']) ?>
                            <?php if (!$m['lu']): ?>
                            <span class="badge badge-danger" style="font-size: 10px; padding: 1px 6px; margin-left: 4px;">Nouveau</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-secondary"><?= sanitize($m['email']) ?></td>
                        <td class="text-secondary"><?= sanitize($m['subject'] ?? '—') ?></td>
                        <td class="text-secondary"><?= formatDate($m['created_at']) ?></td>
                        <td style="text-align: right;">
                            <?php if (!$m['lu']): ?>
                            <form method="POST" action="" style="display:inline">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="lu">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-ghost btn-sm">Marquer lu</button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php foreach ($messages as $m): ?>
        <div style="border-top: 1px solid var(--border-light); padding: var(--space-md) var(--space-lg); <?= !$m['lu'] ? 'background: var(--color-primary-bg);' : '' ?>">
            <div class="flex items-center justify-between mb-sm">
                <div>
                    <strong><?= sanitize($m['name']) ?></strong>
                    <span class="text-secondary" style="font-size: 13px; margin-left: var(--space-sm);"><?= sanitize($m['email']) ?></span>
                </div>
                <div class="flex items-center gap-sm">
                    <?php if (!$m['lu']): ?>
                    <form method="POST" action="" style="display:inline">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="lu">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <button type="submit" class="btn btn-ghost btn-sm">Marquer lu</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" action="" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?')">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Supprimer</button>
                    </form>
                </div>
            </div>
            <?php if ($m['subject']): ?>
            <p style="font-weight: 500; margin: 0 0 var(--space-xs) 0;">Sujet : <?= sanitize($m['subject']) ?></p>
            <?php endif; ?>
            <p style="color: var(--text-secondary); margin: 0; font-size: 14px; line-height: 1.7;"><?= nl2br(sanitize($m['message'])) ?></p>
            <p class="text-small text-secondary mt-sm"><?= formatDateLong($m['created_at']) ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <?= renderPagination($pagination, '?') ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
