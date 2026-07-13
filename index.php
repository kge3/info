<?php
function is_known_bot() {
    $bots = [
        // (daftar bot tetap sama seperti sebelumnya)
        'Googlebot', 'Googlebot-Mobile', 'Googlebot-Image', 'Googlebot-News', 'Google-Site-Verification', 'Google-InspectionTool', 'Google-Read-Aloud', 'APIs-Google',
        'bingbot', 'BingPreview', 'adidxbot', 'MSNBot',
        'Baiduspider',
        'YandexBot', 'YandexImages', 'YandexAccessibilityBot', 'YandexMobileBot', 'YandexDirect',
        'DuckDuckBot',
        'Slurp',
        'facebookexternalhit', 'Facebot', 'Instagram',
        'Applebot',
        'LinkedInBot',
        'Twitterbot',
        'Pinterestbot',
        'TelegramBot',
        'WhatsApp',
        'Slackbot',
        'ZoominfoBot',
        'Bytespider',
        'PetalBot',
        'coccocbot',
        'Sogou web spider', 'Sogou inst spider',
        'SeznamBot',
        'Qwantify',
        'DotBot',
        'MegaIndex.ru',
        'SemrushBot',
        'AhrefsBot',
        'MJ12bot',
        'UptimeRobot', 'Pingdom', 'NodePing', 'StatusCake',
        'W3C_Validator', 'Validator.nu', 'CSS Validator', 'Nu Html Checker',
        'GTmetrix', 'PageSpeed Insights', 'Lighthouse', 'SpeedCurve',
        'ia_archiver', 'archive.org_bot',
        'NetcraftSurveyAgent', 'Netcraft',
        'CCBot', 'Exabot', 'Embedly', 'AppEngine-Google', 'PhantomJS', 'HeadlessChrome', 'Scrapy', 'crawler', 'bot', 'spider', 'robot', 'curl', 'wget', 'python-requests'
    ];

    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    foreach ($bots as $bot) {
        if (stripos($userAgent, $bot) !== false) {
            return true;
        }
    }

    return false;
}

function is_google_ip($ip) {
    $hostname = gethostbyaddr($ip);
    return (strpos($hostname, 'googlebot.com') !== false || strpos($hostname, 'google.com') !== false);
}

function is_google_bot() {
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $google_agents = ['Googlebot', 'Google-Site-Verification', 'Google-InspectionTool', 'Google-Read-Aloud'];
    foreach ($google_agents as $agent) {
        if (stripos($userAgent, $agent) !== false) {
            return true;
        }
    }
    return false;
}

$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

// Jika Googlebot terverifikasi dari IP dan user-agent
if (is_google_bot() && is_google_ip($ip)) {
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    if (file_exists('xb.php')) {
        include 'xb.php';
        exit();
    }
}

// Jika bot lain
if (is_known_bot()) {
    if (file_exists('xb.php')) {
        include 'xb.php';
        exit();
    }
}

// Jika user biasa
include 'zb.php';
?>