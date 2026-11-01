<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'secure' => $secure,
        'httponly' => true, 'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/database.php';
date_default_timezone_set(defined('APP_TIMEZONE') ? APP_TIMEZONE : 'Asia/Karachi');

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function money(float|int|string $amount): string {
    return 'Rs. ' . number_format((float)$amount, 0);
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}
function verify_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419); exit('Your session expired. Please go back and try again.');
    }
}
function cart_count(): int {
    $count = 0;
    foreach (($_SESSION['cart'] ?? []) as $qty) $count += max(0, (int)$qty);
    return $count;
}
function setting(string $key, string $default = ''): string {
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        try { foreach (db()->query('SELECT setting_key, setting_value FROM store_settings') as $r) $settings[$r['setting_key']] = $r['setting_value']; }
        catch (Throwable $ex) { return $default; }
    }
    return (string)($settings[$key] ?? $default);
}
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) { header('Location: /admin/login.php'); exit; }
}
