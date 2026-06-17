<?php
// ============================================================
// Configuration générale du site DRH
// ============================================================

// Environnement
define('ENV', 'production'); // 'development' ou 'production'

// Chemins
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/drhw');
define('UPLOAD_PATH', BASE_PATH . '/assets/uploads');
define('UPLOAD_URL', BASE_URL . '/assets/uploads');

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'drh_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Session
define('SESSION_NAME', 'DRH_SESSION');
define('SESSION_LIFETIME', 7200);

// Sécurité
define('SALT', 'votre_sel_unique_tres_long_changez_moi_2024_securite');

// Uploads
define('MAX_FILE_SIZE', 5242880);
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif']);

// Contact
define('CONTACT_EMAIL', 'contact@drh-securite.gouv');
define('CONTACT_PHONE', '+225 XX XX XX XX');
define('CONTACT_ADDRESS', 'Abidjan, Plateau, Côte d\'Ivoire');

// Site
define('SITE_NAME', 'Direction des Ressources Humaines');
define('SITE_DESCRIPTION', 'Site officiel de la DRH du Ministère de la Sécurité et de la Protection Civile');

// Affichage des erreurs
if (ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Fuseau horaire
date_default_timezone_set('Africa/Abidjan');

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}
