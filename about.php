<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'À propos';
$pageDescription = 'Découvrez la DRH : missions, vision, valeurs et historique.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <span class="sep">/</span>
        <span>À propos</span>
    </div>
    <h1 class="page-title">À propos de la DRH</h1>
    <p class="page-subtitle">Direction des Ressources Humaines du Ministère de la Sécurité et de la Protection Civile</p>
</div>

<div class="grid grid-2 mb-lg">
    <div class="card reveal fade-up">
        <div class="card-body">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--space-md);">
                <div style="width: 44px; height: 44px; background: var(--gradient-card-1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                    <i class="fas fa-bullseye"></i>
                </div>
                <p class="card-title" style="margin: 0;">Notre mission</p>
            </div>
            <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: var(--space-md);">
                La Direction des Ressources Humaines (DRH) est une structure centrale du Ministère de la Sécurité et de la Protection Civile. Elle est chargée de la conception, de la mise en œuvre et du suivi de la politique de gestion des ressources humaines du ministère.
            </p>
            <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: var(--space-md);">
                Notre mission fondamentale est d'assurer une gestion optimale, transparente et équitable des ressources humaines, en veillant au bien-être et à l'épanouissement professionnel de chaque agent.
            </p>
            <p style="color: var(--text-secondary); line-height: 1.8;">
                Nous nous engageons à moderniser en continu nos pratiques RH pour offrir un service public de qualité, efficient et adapté aux enjeux contemporains de la sécurité et de la protection civile.
            </p>
        </div>
    </div>
    <div class="card reveal delay-2 fade-up">
        <div class="card-body text-center">
            <div style="width: 64px; height: 64px; background: var(--gradient-card-2); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white; font-size: 28px; box-shadow: 0 8px 25px rgba(6,182,212,0.25);">
                <i class="fas fa-eye"></i>
            </div>
            <p class="card-title" style="margin-bottom: var(--space-sm);">Notre vision</p>
            <p style="color: var(--text-secondary); line-height: 1.8; max-width: 400px; margin: 0 auto;">
                Devenir une direction de référence en matière de gestion des ressources humaines dans le secteur de la sécurité, reconnue pour son professionnalisme, son innovation et son engagement au service des agents.
            </p>
        </div>
    </div>
</div>

<!-- Valeurs -->
<div class="card mb-lg reveal fade-up">
    <div class="card-header">
        <h2 class="card-title">Nos valeurs fondamentales</h2>
    </div>
    <div class="card-body">
        <div class="grid grid-4">
            <div class="stat-card reveal delay-1" style="text-align: center;">
                <div style="width: 52px; height: 52px; background: var(--gradient-card-1); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white; font-size: 22px; box-shadow: 0 6px 20px rgba(124,58,237,0.2);">
                    <i class="fas fa-handshake"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Probité</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Intégrité et éthique irréprochables dans toutes nos actions.</p>
            </div>
            <div class="stat-card reveal delay-2" style="text-align: center;">
                <div style="width: 52px; height: 52px; background: var(--gradient-card-2); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white; font-size: 22px; box-shadow: 0 6px 20px rgba(6,182,212,0.2);">
                    <i class="fas fa-scale-balanced"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Équité</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Traitement juste et impartial de tous les agents.</p>
            </div>
            <div class="stat-card reveal delay-3" style="text-align: center;">
                <div style="width: 52px; height: 52px; background: var(--gradient-card-3); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white; font-size: 22px; box-shadow: 0 6px 20px rgba(249,115,22,0.2);">
                    <i class="fas fa-star"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Excellence</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Recherche permanente de la qualité et de l'amélioration continue.</p>
            </div>
            <div class="stat-card reveal delay-4" style="text-align: center;">
                <div style="width: 52px; height: 52px; background: var(--gradient-card-4); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md); color: white; font-size: 22px; box-shadow: 0 6px 20px rgba(16,185,129,0.2);">
                    <i class="fas fa-users"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 6px 0; font-size: 15px;">Solidarité</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Esprit d'équipe et entraide pour atteindre nos objectifs.</p>
            </div>
        </div>
    </div>
</div>

<!-- Historique -->
<div class="card reveal fade-up">
    <div class="card-header">
        <h2 class="card-title">Notre historique</h2>
    </div>
    <div class="card-body">
        <div style="border-left: 3px solid var(--color-primary); padding-left: var(--space-lg);">
            <div style="position: relative; padding-bottom: var(--space-lg);">
                <div style="position: absolute; left: -36px; top: 4px; width: 22px; height: 22px; background: var(--gradient-card-1); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(124,58,237,0.3);">
                    <i class="fas fa-check" style="color: white; font-size: 10px;"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 4px 0;">2024 - Aujourd'hui</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Modernisation des systèmes d'information RH et digitalisation des processus administratifs.</p>
            </div>
            <div style="position: relative; padding-bottom: var(--space-lg);">
                <div style="position: absolute; left: -36px; top: 4px; width: 22px; height: 22px; background: var(--gradient-card-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(6,182,212,0.3);">
                    <i class="fas fa-check" style="color: white; font-size: 10px;"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 4px 0;">2020 - 2024</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Renforcement des capacités et mise en place de la GPEC.</p>
            </div>
            <div style="position: relative; padding-bottom: var(--space-lg);">
                <div style="position: absolute; left: -36px; top: 4px; width: 22px; height: 22px; background: var(--gradient-card-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(249,115,22,0.3);">
                    <i class="fas fa-check" style="color: white; font-size: 10px;"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 4px 0;">2015 - 2020</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Informatisation des services et dématérialisation des dossiers.</p>
            </div>
            <div style="position: relative; padding-bottom: var(--space-lg);">
                <div style="position: absolute; left: -36px; top: 4px; width: 22px; height: 22px; background: var(--gradient-card-4); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                    <i class="fas fa-check" style="color: white; font-size: 10px;"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 4px 0;">2010 - 2015</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Structuration des divisions pour une meilleure efficacité opérationnelle.</p>
            </div>
            <div style="position: relative;">
                <div style="position: absolute; left: -36px; top: 4px; width: 22px; height: 22px; background: var(--gradient-card-5); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(236,72,153,0.3);">
                    <i class="fas fa-star" style="color: white; font-size: 10px;"></i>
                </div>
                <p style="font-weight: 700; margin: 0 0 4px 0;">2008</p>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">Création de la Direction des Ressources Humaines.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
