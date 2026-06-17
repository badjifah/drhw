<?php
require_once __DIR__ . '/../includes/functions.php';
$db = Database::getInstance();

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$baseUrl = BASE_URL;

// Static pages
$staticPages = ['', '/?page=about', '/?page=services', '/?page=news', '/?page=documents', '/?page=contact'];
foreach ($staticPages as $p) {
    echo "<url><loc>{$baseUrl}{$p}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>";
}

// Dynamic articles
$articles = $db->fetchAll("SELECT slug, updated_at FROM actualites WHERE status = 'publie' ORDER BY created_at DESC");
foreach ($articles as $a) {
    $slug = $a['slug'] ?: $a['id'];
    echo "<url><loc>{$baseUrl}/?page=news-detail&id={$slug}</loc><lastmod>" . date('Y-m-d', strtotime($a['updated_at'] ?? date('Y-m-d'))) . "</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>";
}

echo '</urlset>';
