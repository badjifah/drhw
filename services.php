<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$services = $db->fetchAll("SELECT * FROM services WHERE status = 'actif' ORDER BY order_num");

$pageTitle = 'Nos services';
$pageDescription = 'Découvrez les divisions et services de la DRH.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <span class="sep">/</span>
        <span>Services</span>
    </div>
    <h1 class="page-title">Nos services</h1>
    <p class="page-subtitle">Des divisions spécialisées au service des agents et du personnel</p>
</div>

<?php if (empty($services)): ?>
<div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-building"></i></div>
    <h3 class="empty-state-title">Aucun service disponible</h3>
    <p class="empty-state-text">Les informations sur les services seront bientôt disponibles.</p>
</div>
<?php else: ?>
<div class="flex flex-col gap-md">
    <?php foreach ($services as $index => $svc): ?>
    <div class="card reveal fade-up delay-<?= ($index % 4) + 1 ?>">
        <div class="card-body">
            <div class="flex gap-lg">
                <?php $i = ($index % 4) + 1; ?>
                <div class="gradient-icon gradient-icon-<?= $i ?>" aria-hidden="true">
                    <i class="fas fa-<?= sanitize($svc['icon']) ?>"></i>
                </div>
                <div class="flex-1" style="flex:1;min-width:0;">
                    <div class="flex items-center justify-between mb-sm">
                        <div>
                            <p style="font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 4px 0;">Division</p>
                            <h2 style="font-size: 18px; font-weight: 700; margin: 0;"><?= sanitize($svc['title']) ?></h2>
                        </div>
                        <span class="badge badge-info">0<?= $index + 1 ?></span>
                    </div>
                    <?php if (!empty($svc['subtitle'])): ?>
                    <p style="font-weight: 600; color: var(--text-primary); margin: 0 0 var(--space-sm) 0;"><?= sanitize($svc['subtitle']) ?></p>
                    <?php endif; ?>
                    <p style="color: var(--text-secondary); line-height: 1.8; margin: 0 0 var(--space-md) 0;"><?= nl2br(sanitize($svc['description'])) ?></p>
                    <?php if (!empty($svc['missions'])): ?>
                    <div class="info-banner">
                        <div class="info-banner-icon">i</div>
                        <div>
                            <p class="info-banner-text" style="font-weight: 600; margin-bottom: 10px;">Nos missions</p>
                            <div class="grid grid-2" style="gap: 6px;">
                                <?php $missions = explode(';', $svc['missions']); ?>
                                <?php foreach ($missions as $mission): ?>
                                <?php if (trim($mission)): ?>
                                <div class="flex items-start gap-xs" style="font-size: 13px; color: var(--text-primary);">
                                    <i class="fas fa-check-circle" style="color: var(--color-success); font-size: 13px; margin-top: 3px;"></i>
                                    <?= sanitize(trim($mission)) ?>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
