<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Session.php';
require_once __DIR__ . '/includes/functions.php';

$db = Database::getInstance();
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rateLimit('contact_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 5, 300)) {
        flash('danger', 'Trop de soumissions. Veuillez patienter 5 minutes.');
        redirect('?page=contact');
    }

    $prenom = sanitize($_POST['prenom'] ?? '');
    $nom = sanitize($_POST['nom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telephone = sanitize($_POST['telephone'] ?? '');
    $sujet = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($prenom)) $errors[] = 'Le prénom est requis.';
    if (empty($nom)) $errors[] = 'Le nom est requis.';
    if (!validateEmail($email)) $errors[] = 'Email invalide.';
    if (empty($sujet)) $errors[] = 'Le sujet est requis.';
    if (empty($message)) $errors[] = 'Le message est requis.';

    if (empty($errors)) {
        $db->insert('messages', [
            'name' => $prenom . ' ' . $nom,
            'email' => $email,
            'subject' => $sujet,
            'message' => $message
        ]);

        // Email to DRH
        sendEmail(
            CONTACT_EMAIL,
            '[DRH] Nouveau message: ' . $sujet,
            '<h2>Nouveau message de contact</h2>
            <p><strong>Nom:</strong> ' . $prenom . ' ' . $nom . '</p>
            <p><strong>Email:</strong> ' . $email . '</p>
            <p><strong>Téléphone:</strong> ' . $telephone . '</p>
            <p><strong>Sujet:</strong> ' . $sujet . '</p>
            <p><strong>Message:</strong></p>
            <div style="background:#f5f5f5;padding:15px;border-radius:8px;margin:10px 0;">' . nl2br(sanitize($message)) . '</div>'
        );

        // Auto-reply to user
        sendEmail(
            $email,
            '[DRH] Message reçu - ' . $sujet,
            '<div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;">
            <h2 style="color:#8B5CF6;">Merci pour votre message</h2>
            <p>Bonjour ' . $prenom . ',</p>
            <p>Nous avons bien reçu votre message concernant « ' . $sujet . ' ».</p>
            <p>Notre équipe vous répondra dans les meilleurs délais.</p>
            <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">
            <p style="color:#666;font-size:12px;">Direction des Ressources Humaines</p>
            </div>'
        );

        $success = true;
    }
}

$pageTitle = 'Contact';
$pageDescription = 'Contactez la Direction des Ressources Humaines.';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <span class="sep">/</span>
        <span>Contact</span>
    </div>
    <h1 class="page-title">Nous contacter</h1>
    <p class="page-subtitle">Une question ? Une demande ? N'hésitez pas à nous écrire</p>
</div>

<?php if ($success): ?>
<div data-toast="Message envoyé avec succès ! Nous vous répondrons dans les plus brefs délais." data-toast-type="success"></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div data-toast="Veuillez corriger les erreurs ci-dessous." data-toast-type="danger"></div>
<?php endif; ?>

<div class="grid grid-2">
    <div class="card reveal fade-up">
        <div class="card-header">
            <h2 class="card-title">Envoyez-nous un message</h2>
        </div>
        <form method="POST" class="card-body" data-contact-form>
            <div class="flex flex-col gap-md">
                <div class="form-group">
                    <label class="form-label" for="prenom">Prénom <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="prenom" name="prenom" required class="form-input" placeholder="Votre prénom">
                </div>
                <div class="form-group">
                    <label class="form-label" for="nom">Nom <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="nom" name="nom" required class="form-input" placeholder="Votre nom">
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email <span style="color: var(--color-danger);">*</span></label>
                    <input type="email" id="email" name="email" required class="form-input" placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label class="form-label" for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="form-input" placeholder="Votre numéro de téléphone">
                </div>
                <div class="form-group">
                    <label class="form-label" for="subject">Sujet <span style="color: var(--color-danger);">*</span></label>
                    <input type="text" id="subject" name="subject" required class="form-input" placeholder="Objet de votre message">
                </div>
                <div class="form-group">
                    <label class="form-label" for="message">Message <span style="color: var(--color-danger);">*</span></label>
                    <textarea id="message" name="message" rows="6" required class="form-textarea" placeholder="Votre message..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer le message
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-col gap-md">
        <div class="contact-info-card reveal delay-2 fade-up">
            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <p class="contact-info-label">Adresse</p>
                    <p class="contact-info-value"><?= sanitize(getSetting('site_address', CONTACT_ADDRESS)) ?></p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <p class="contact-info-label">Téléphone</p>
                    <p class="contact-info-value"><?= sanitize(getSetting('site_phone', CONTACT_PHONE)) ?></p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <p class="contact-info-label">Email</p>
                    <p class="contact-info-value"><?= sanitize(getSetting('site_email', CONTACT_EMAIL)) ?></p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <p class="contact-info-label">Horaires</p>
                    <p class="contact-info-value">Lun-Ven : 08h00 - 17h00</p>
                </div>
            </div>
        </div>

        <div class="card reveal delay-3 fade-up" style="height: 260px; overflow: hidden;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63644.67287891225!2d-4.015525!3d5.323541!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTknMjQuNyNOIDTCsDAwJzU0LjkiVw!5e0!3m2!1sfr!2sci!4v1" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
