<?php
require_once __DIR__ . '/config.php';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$base     = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$per_page = 50000;
$page     = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 0;

// Load semua slug dari db_brand.txt
$raw    = file(DB_BRAND_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$slugs  = [];
foreach ($raw as $line) {
    $slugs[] = strtolower(preg_replace('/\s+/', '-', trim($line)));
}
$total = count($slugs);
$pages = (int)ceil($total / $per_page);

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

// ── SITEMAP INDEX (sitemap.xml tanpa ?p) ─────────────────
if ($page === 0) {
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    for ($i = 1; $i <= $pages; $i++) {
        echo "  <sitemap>\n";
        echo "    <loc>" . htmlspecialchars("{$protocol}://{$host}{$base}/sitemap-{$i}.xml") . "</loc>\n";
        echo "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
        echo "  </sitemap>\n";
    }
    echo '</sitemapindex>';
    exit;
}

// ── SITEMAP PAGE (sitemap-N.xml) ──────────────────────────
$chunk = array_slice($slugs, ($page - 1) * $per_page, $per_page);

echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($chunk as $slug) {
    $days_since  = max(1, (int)((time() - strtotime('2025-07-01')) / 86400));
    $pub_offset  = abs(crc32($slug . '_pub')) % $days_since;
    $lastmod     = date('Y-m-d', strtotime('2025-07-01') + ($pub_offset * 86400));

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars("{$protocol}://{$host}{$base}/{$slug}/") . "</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>';
