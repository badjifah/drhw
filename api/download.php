<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';

$db = Database::getInstance();
$id = (int)($_GET['id'] ?? 0);

$doc = $db->fetch("SELECT * FROM documents WHERE id = :id", ['id' => $id]);

if (!$doc) {
    header('HTTP/1.0 404 Not Found');
    die('Document non trouvé.');
}

$filePath = UPLOAD_PATH . '/' . $doc['file_path'];
if (!file_exists($filePath)) {
    header('HTTP/1.0 404 Not Found');
    die('Fichier non trouvé sur le serveur.');
}

$db->update('documents', ['downloads' => $doc['downloads'] + 1], 'id = :id', ['id' => $id]);

$filename = basename($doc['file_path']);
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

$mimeTypes = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
];

$mime = $mimeTypes[$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $doc['title'] . '.' . $ext . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache');
readfile($filePath);
exit;
