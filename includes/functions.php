<?php
require_once __DIR__ . '/config.php';

// ---------- Sécurité ----------

function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function generateSlug(string $text): string
{
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

// ---------- CSRF Protection ----------

function csrfToken(): string
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function csrfValidate(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && hash_equals(csrfToken(), $token);
}

function csrfCheckOrDie(): void
{
    if (!csrfValidate()) {
        http_response_code(403);
        die('Token CSRF invalide. Veuillez réessayer.');
    }
}

// ---------- Rate Limiting ----------

function rateLimit(string $key, int $maxAttempts = 5, int $windowSeconds = 300): bool
{
    $file = sys_get_temp_dir() . '/drh_rl_' . md5($key);
    $now = time();
    $attempts = [];

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            $attempts = array_filter($data, fn($t) => $t > $now - $windowSeconds);
        }
    }

    if (count($attempts) >= $maxAttempts) return false;

    $attempts[] = $now;
    file_put_contents($file, json_encode(array_values($attempts)));
    return true;
}

function getRemainingAttempts(string $key, int $maxAttempts = 5, int $windowSeconds = 300): int
{
    $file = sys_get_temp_dir() . '/drh_rl_' . md5($key);
    $now = time();
    $attempts = [];

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            $attempts = array_filter($data, fn($t) => $t > $now - $windowSeconds);
        }
    }

    return max(0, $maxAttempts - count($attempts));
}

// ---------- Activity Logs ----------

function logActivity(string $action, string $details = '', int $adminId = 0): void
{
    try {
        $db = Database::getInstance();
        $db->insert('activity_logs', [
            'admin_id' => $adminId ?: (Session::get('admin_id') ?: 0),
            'action' => $action,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } catch (Exception $e) {
        // Silent fail for logging
    }
}

// ---------- Pagination ----------

function paginate(string $sql, array $params = [], int $perPage = 12): array
{
    $db = Database::getInstance();
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $perPage;

    // Count total
    $countSql = preg_replace('/SELECT .+? FROM/i', 'SELECT COUNT(*) as total FROM', $sql, 1);
    $countSql = preg_replace('/ORDER BY .+$/i', '', $countSql);
    $total = (int)$db->fetch($countSql, $params)['total'];
    $totalPages = max(1, ceil($total / $perPage));

    $page = min($page, $totalPages);
    $offset = ($page - 1) * $perPage;

    $sql .= " LIMIT $perPage OFFSET $offset";
    $items = $db->fetchAll($sql, $params);

    return [
        'items' => $items,
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total,
        'totalPages' => $totalPages,
        'hasPrev' => $page > 1,
        'hasNext' => $page < $totalPages,
    ];
}

function renderPagination(array $pagination, string $baseUrl = ''): void
{
    if ($pagination['totalPages'] <= 1) return;

    $page = $pagination['page'];
    $total = $pagination['totalPages'];
    $separator = str_contains($baseUrl, '?') ? '&' : '?';

    echo '<div class="flex items-center justify-center mt-lg" style="gap: 6px;">';

    // Previous
    if ($pagination['hasPrev']) {
        echo '<a href="' . $baseUrl . $separator . 'page=' . ($page - 1) . '" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i></a>';
    }

    // Page numbers
    $start = max(1, $page - 2);
    $end = min($total, $page + 2);

    if ($start > 1) {
        echo '<a href="' . $baseUrl . $separator . 'page=1" class="btn btn-ghost btn-sm">1</a>';
        if ($start > 2) echo '<span style="color: var(--text-tertiary); padding: 0 4px;">...</span>';
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $page ? 'style="background: var(--color-primary); color: white; pointer-events: none;"' : '';
        echo '<a href="' . $baseUrl . $separator . 'page=' . $i . '" class="btn btn-ghost btn-sm" ' . $active . '>' . $i . '</a>';
    }

    if ($end < $total) {
        if ($end < $total - 1) echo '<span style="color: var(--text-tertiary); padding: 0 4px;">...</span>';
        echo '<a href="' . $baseUrl . $separator . 'page=' . $total . '" class="btn btn-ghost btn-sm">' . $total . '</a>';
    }

    // Next
    if ($pagination['hasNext']) {
        echo '<a href="' . $baseUrl . $separator . 'page=' . ($page + 1) . '" class="btn btn-ghost btn-sm"><i class="fas fa-chevron-right"></i></a>';
    }

    echo '</div>';
    echo '<div class="text-center mt-sm"><span class="text-small text-secondary">' . $pagination['total'] . ' résultat(s) — Page ' . $pagination['page'] . '/' . $pagination['totalPages'] . '</span></div>';
}

// ---------- Email ----------

function sendEmail(string $to, string $subject, string $htmlBody, string $fromEmail = '', string $fromName = ''): bool
{
    $fromEmail = $fromEmail ?: CONTACT_EMAIL;
    $fromName = $fromName ?: SITE_NAME;

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        "From: {$fromName} <{$fromEmail}>",
        'X-Mailer: PHP/' . phpversion(),
    ];

    return mail($to, $subject, $htmlBody, implode("\r\n", $headers));
}

// ---------- Image helpers ----------

function getFileIcon(string $filename): string
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => 'fa-file-pdf', 'doc' => 'fa-file-word', 'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel', 'xlsx' => 'fa-file-excel', 'csv' => 'fa-file-csv',
        'ppt' => 'fa-file-powerpoint', 'pptx' => 'fa-file-powerpoint',
        'zip' => 'fa-file-zipper', 'rar' => 'fa-file-zipper', '7z' => 'fa-file-zipper',
        'jpg' => 'fa-file-image', 'jpeg' => 'fa-file-image', 'png' => 'fa-file-image',
        'gif' => 'fa-file-image', 'svg' => 'fa-file-image', 'webp' => 'fa-file-image',
        'mp4' => 'fa-file-video', 'avi' => 'fa-file-video', 'mov' => 'fa-file-video',
        'mp3' => 'fa-file-audio', 'wav' => 'fa-file-audio',
        'txt' => 'fa-file-lines', 'md' => 'fa-file-lines',
    ];
    return $icons[$ext] ?? 'fa-file';
}

function getFileColor(string $filename): string
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $colors = [
        'pdf' => '#EF4444', 'doc' => '#3B82F6', 'docx' => '#3B82F6',
        'xls' => '#10B981', 'xlsx' => '#10B981', 'csv' => '#10B981',
        'ppt' => '#F97316', 'pptx' => '#F97316',
        'zip' => '#8B5CF6', 'rar' => '#8B5CF6',
        'jpg' => '#EC4899', 'jpeg' => '#EC4899', 'png' => '#EC4899',
    ];
    return $colors[$ext] ?? '#8B5CF6';
}

function truncate(string $text, int $length = 150): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

function formatDate(string $date, string $format = 'd/m/Y'): string
{
    return date($format, strtotime($date));
}

function formatDateLong(string $date): string
{
    $timestamp = strtotime($date);
    $mois = [
        'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars',
        'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
        'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre',
        'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
    ];
    $jours = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
              'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];
    $date_str = $jours[date('l', $timestamp)] . ' ' . date('j', $timestamp) . ' ' . $mois[date('F', $timestamp)] . ' ' . date('Y', $timestamp);
    return $date_str;
}

function formatFileSize(int $bytes): string
{
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' Go';
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' Mo';
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' Ko';
    return $bytes . ' octets';
}

// ---------- Messages flash ----------

function flash(string $type, string $message): void
{
    Session::setFlash('flash_' . $type, $message);
}

function flashDisplay(): void
{
    $types = ['success', 'danger', 'warning', 'info'];
    foreach ($types as $type) {
        $msg = Session::getFlash('flash_' . $type);
        if ($msg) {
            echo '<div data-toast="' . sanitize($msg) . '" data-toast-type="' . $type . '"></div>';
        }
    }
}

// ---------- Upload ----------

function uploadFile(array $file, string $subdir = ''): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) return null;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS)) return null;
    if ($file['size'] > MAX_FILE_SIZE) return null;

    // MIME type validation
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
        'text/plain', 'text/csv',
    ];
    if (!in_array($mime, $allowedMimes)) return null;

    $targetDir = UPLOAD_PATH . ($subdir ? '/' . $subdir : '');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $filename = uniqid() . '_' . time() . '.' . $ext;
    $targetFile = $targetDir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ($subdir ? $subdir . '/' : '') . $filename;
    }
    return null;
}

// ---------- Redirection ----------

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function redirectBack(): void
{
    $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    redirect($ref);
}

// ---------- Affichage ----------

function activePage(string $page): string
{
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}

function isActive(string $slug): string
{
    return (isset($_GET['page']) && $_GET['page'] === $slug) ? 'active' : '';
}

function getSetting(string $key, string $default = ''): string
{
    try {
        $db = Database::getInstance();
        $result = $db->fetch("SELECT value FROM settings WHERE key_name = :key", ['key' => $key]);
        return $result ? $result['value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

// ---------- SEO helpers ----------

function renderMetaTags(string $title, string $description, string $url = '', string $image = ''): void
{
    $url = $url ?: ($_SERVER['REQUEST_URI'] ?? '');
    $fullUrl = BASE_URL . $url;
    $image = $image ?: BASE_URL . '/assets/img/og-default.png';
    ?>
    <meta property="og:title" content="<?= sanitize($title) ?> | <?= SITE_NAME ?>">
    <meta property="og:description" content="<?= sanitize($description) ?>">
    <meta property="og:url" content="<?= $fullUrl ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= $image ?>">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= sanitize($title) ?> | <?= SITE_NAME ?>">
    <meta name="twitter:description" content="<?= sanitize($description) ?>">
    <meta name="twitter:image" content="<?= $image ?>">
    <link rel="canonical" href="<?= $fullUrl ?>">
    <?php
}
