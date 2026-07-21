<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido.');
}

admin_verify_csrf();

if (admin_user() !== null) {
    admin_audit($conn, 'logout', 'admin_usuarios', (int) admin_user()['id'], 'Cierre de sesión');
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => (bool) $params['secure'],
        'httponly' => (bool) $params['httponly'],
        'samesite' => 'Lax',
    ]);
}
session_destroy();
session_start();
admin_flash('success', 'Sesión cerrada correctamente.');
admin_redirect('login.php');

