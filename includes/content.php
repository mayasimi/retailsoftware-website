<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

const CONTENT_IMAGE_KEYS = [
    'site.logo',
    'home.hero_image_first',
    'home.hero_image_second',
    'home.about_image',
    'about.image_primary',
    'features.image_sales',
    'features.image_inventory',
    'features.image_receipts',
    'features.image_orders',
    'features.image_shop',
    'features.image_customers',
    'features.image_access',
    'features.image_vendors',
    'features.image_waste',
    'features.image_analytics',
];

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function content(string $key): string
{
    static $values = null;

    if ($values === null) {
        $statement = database()->query('SELECT content_key, content_value FROM site_content');
        $values = [];
        foreach ($statement as $row) {
            $values[$row['content_key']] = $row['content_value'];
        }
    }

    if (!array_key_exists($key, $values)) {
        throw new RuntimeException('Missing site content: ' . $key);
    }

    return $values[$key];
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function pageTitle(string $titleKey): string
{
    return h(content($titleKey)) . ' | ' . h(content('site.name'));
}

function adminCsrfToken(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validateCsrfToken(): bool
{
    $submitted = $_POST['csrf_token'] ?? '';
    return is_string($submitted)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submitted);
}
