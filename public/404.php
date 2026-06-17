<?php require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';
?>
<main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; flex-direction: column; text-align: center; padding: var(--space-3xl) var(--space-md);">
    <div class="glitch-wrapper" style="margin-bottom: var(--space-xl);">
        <div class="glitch" data-text="404" style="font-size: 8rem; font-weight: 900; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">404</div>
    </div>
    <h1 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: var(--space-md);">Page introuvable</h1>
    <p style="color: var(--text-secondary); margin-bottom: var(--space-xl); max-width: 400px;">La page que vous recherchez n'existe pas ou a été déplacée.</p>
    <a href="<?= BASE_URL ?>/" class="btn btn-primary"><i class="fas fa-home"></i> Retour à l'accueil</a>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
