<?php
http_response_code(500);
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';
?>
<main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; flex-direction: column; text-align: center; padding: var(--space-3xl) var(--space-md);">
    <div style="margin-bottom: var(--space-xl);"><i class="fas fa-exclamation-triangle" style="font-size: 5rem; color: var(--color-accent); opacity: 0.8;"></i></div>
    <h1 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: var(--space-md);">Erreur interne du serveur</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--space-xl); max-width: 400px;">Une erreur inattendue s'est produite. Veuillez réessayer plus tard.</p>
    <a href="<?= BASE_URL ?>/" class="btn btn-primary"><i class="fas fa-home"></i> Retour à l'accueil</a>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
