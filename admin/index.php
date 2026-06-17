<?php
$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/includes/admin_header.php';

$db = Database::getInstance();

$stats = [
    'actualites' => $db->count('actualites'),
    'actualites_publiees' => $db->count('actualites', "status = 'publie'"),
    'documents' => $db->count('documents'),
    'messages' => $db->count('messages'),
    'messages_non_lus' => $db->count('messages', 'lu = FALSE'),
    'services' => $db->count('services', "status = 'actif'"),
];

$last_actualites = $db->fetchAll("SELECT id, title, category, status, created_at FROM actualites ORDER BY created_at DESC LIMIT 5");
$last_messages = $db->fetchAll("SELECT id, name, email, subject, lu, created_at FROM messages ORDER BY created_at DESC LIMIT 5");
?>

<div class="stats-grid stats-4 mb-lg">
    <div class="stat-card">
        <p class="stat-card-label">Actualités</p>
        <p class="stat-card-value"><?= $stats['actualites'] ?></p>
        <p class="stat-card-change" style="color: var(--color-success);"><?= $stats['actualites_publiees'] ?> publiées</p>
    </div>
    <div class="stat-card">
        <p class="stat-card-label">Documents</p>
        <p class="stat-card-value"><?= $stats['documents'] ?></p>
    </div>
    <div class="stat-card">
        <p class="stat-card-label">Messages</p>
        <p class="stat-card-value"><?= $stats['messages'] ?></p>
        <?php if ($stats['messages_non_lus'] > 0): ?>
        <p class="stat-card-change" style="color: var(--color-danger);"><?= $stats['messages_non_lus'] ?> non lu(s)</p>
        <?php endif; ?>
    </div>
    <div class="stat-card">
        <p class="stat-card-label">Services actifs</p>
        <p class="stat-card-value"><?= $stats['services'] ?></p>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Dernières actualités</h2>
            <a href="<?= BASE_URL ?>/admin/actualites.php" class="btn btn-ghost btn-sm">Voir tout</a>
        </div>
        <?php if (empty($last_actualites)): ?>
        <div class="card-body">
            <div class="empty-state" style="padding: var(--space-lg);">
                <p class="text-secondary" style="margin: 0;">Aucune actualité.</p>
            </div>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($last_actualites as $a): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= sanitize(truncate($a['title'], 50)) ?></td>
                        <td>
                            <span class="badge badge-<?= $a['status'] === 'publie' ? 'success' : 'neutral' ?>">
                                <span class="badge-dot"></span>
                                <?= $a['status'] === 'publie' ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td class="text-secondary"><?= formatDate($a['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Derniers messages</h2>
            <a href="<?= BASE_URL ?>/admin/messages.php" class="btn btn-ghost btn-sm">Voir tout</a>
        </div>
        <?php if (empty($last_messages)): ?>
        <div class="card-body">
            <div class="empty-state" style="padding: var(--space-lg);">
                <p class="text-secondary" style="margin: 0;">Aucun message.</p>
            </div>
        </div>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Expéditeur</th>
                        <th>Sujet</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($last_messages as $m): ?>
                    <tr style="<?= !$m['lu'] ? 'background: var(--color-primary-bg);' : '' ?>">
                        <td style="font-weight: 500;">
                            <?= sanitize($m['name']) ?>
                            <?php if (!$m['lu']): ?>
                            <span class="badge badge-danger" style="font-size: 10px; padding: 1px 6px; margin-left: 4px;">Nouveau</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-secondary"><?= sanitize(truncate($m['subject'] ?? 'Sans sujet', 40)) ?></td>
                        <td class="text-secondary"><?= formatDate($m['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
