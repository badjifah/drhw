<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Session.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheckOrDie();
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $db = Database::getInstance();
        $admin = $db->fetch("SELECT * FROM admins WHERE username = :username", ['username' => $username]);

        if ($admin && password_verify($password, $admin['password'])) {
            Session::set('admin_id', $admin['id']);
            Session::set('admin_username', $admin['username']);
            Session::set('admin_name', $admin['full_name']);
            Session::set('admin_role', $admin['role']);
            $db->update('admins', ['last_login' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $admin['id']]);
            redirect(BASE_URL . '/admin/index.php');
        } else {
            $error = 'Identifiants incorrects.';
        }
    }
}

$pageTitle = 'Connexion';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | DRH Administration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --color-primary: #7C3AED;
            --gradient-main: linear-gradient(135deg, #8B5CF6 0%, #06B6D4 50%, #F472B6 100%);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --color-danger: #EF4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, #f8fafc 0%, #ede9fe 50%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.08) 0%, transparent 70%);
            top: -150px; right: -150px;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(6,182,212,0.06) 0%, transparent 70%);
            bottom: -120px; left: -120px;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(2deg); }
            66% { transform: translateY(10px) rotate(-2deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glowPulse {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(139,92,246,0.3)); }
            50% { filter: drop-shadow(0 0 20px rgba(139,92,246,0.5)); }
        }
        .login-wrapper {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-xl);
            padding: 44px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
        }
        .login-logo {
            width: 68px; height: 68px;
            background: var(--gradient-main);
            border-radius: var(--radius-lg);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            color: white; font-size: 30px;
            box-shadow: 0 8px 30px rgba(139,92,246,0.25);
            animation: glowPulse 3s ease infinite;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .login-logo:hover { transform: rotate(-12deg) scale(1.08); }
        .login-title {
            font-size: 24px; font-weight: 800;
            text-align: center; color: var(--text-primary);
            margin-bottom: 4px;
        }
        .login-subtitle {
            font-size: 13px; text-align: center;
            color: var(--text-secondary); margin-bottom: 32px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--text-primary); margin-bottom: 8px;
        }
        .form-input {
            width: 100%; padding: 13px 16px;
            font-size: 14px; font-family: var(--font-family);
            color: var(--text-primary);
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-md);
            outline: none; transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
            background: #ffffff;
        }
        .form-input::placeholder { color: #94a3b8; }
        .btn {
            width: 100%; padding: 13px 16px;
            font-size: 14px; font-weight: 700;
            font-family: var(--font-family);
            background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);
            color: white; border: none;
            border-radius: var(--radius-md);
            cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 20px rgba(139,92,246,0.25);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(139,92,246,0.35);
        }
        .btn:active { transform: translateY(0) scale(0.98); }
        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius-md);
            padding: 12px 14px;
            font-size: 13px; font-weight: 500;
            color: #EF4444;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .back-link {
            display: block; text-align: center;
            margin-top: 24px; font-size: 13px; font-weight: 500;
            color: #94a3b8;
            text-decoration: none; transition: color 0.3s ease;
        }
        .back-link:hover { color: #475569; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo"><i class="fas fa-shield-halved"></i></div>
            <h1 class="login-title">DRH Administration</h1>
            <p class="login-subtitle">Direction des Ressources Humaines</p>

            <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?= sanitize($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <?= csrfField() ?>
                <div class="form-group">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" required autocomplete="username" class="form-input" placeholder="admin">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" required autocomplete="current-password" class="form-input" placeholder="••••••••">
                </div>
                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
        </div>

        <a href="<?= BASE_URL ?>/index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour au site
        </a>
    </div>
</body>
</html>
