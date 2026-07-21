<?php
declare(strict_types=1);

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === 'bootstrap.php') {
    http_response_code(404);
    exit;
}

ini_set('display_errors', '0');
set_exception_handler(static function (Throwable $exception): void {
    error_log('[BitFlow Admin] ' . $exception->getMessage() . "\n" . $exception->getTraceAsString());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }
    echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Error</title><body><h1>No pudimos completar la operación</h1><p>Intentá nuevamente. Si el problema continúa, revisá el registro de errores del servidor.</p></body></html>';
    exit;
});

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; img-src 'self' data:; style-src 'self'; script-src 'self'");
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header_remove('X-Powered-By');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('BITFLOW_ADMIN');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

require_once dirname(__DIR__) . '/inc/funciones/bd.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const ADMIN_LOGIN_MAX_ATTEMPTS = 5;
const ADMIN_LOGIN_BLOCK_MINUTES = 15;
const ADMIN_CONTACT_STATES = ['nuevo', 'en_proceso', 'respondido', 'cerrado'];

function admin_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function admin_base_url(): string
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/admin/index.php'));
    $position = strpos($script, '/admin/');
    return $position === false ? '/admin' : substr($script, 0, $position + 6);
}

function admin_url(string $path = ''): string
{
    return rtrim(admin_base_url(), '/') . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

function admin_redirect(string $path): void
{
    header('Location: ' . admin_url($path), true, 303);
    exit;
}

function admin_csrf_token(): string
{
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['admin_csrf'];
}

function admin_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . admin_h(admin_csrf_token()) . '">';
}

function admin_verify_csrf(): void
{
    $token = (string) ($_POST['csrf_token'] ?? '');
    if ($token === '' || !hash_equals(admin_csrf_token(), $token)) {
        http_response_code(403);
        exit('La solicitud venció o no es válida. Volvé atrás e intentá nuevamente.');
    }
}

function admin_flash(string $type, string $message): void
{
    $_SESSION['admin_flash'][] = ['type' => $type, 'message' => $message];
}

function admin_take_flashes(): array
{
    $flashes = $_SESSION['admin_flash'] ?? [];
    unset($_SESSION['admin_flash']);
    return is_array($flashes) ? $flashes : [];
}

function admin_table_exists(mysqli $conn, string $table): bool
{
    $stmt = $conn->prepare('SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?');
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();
    return $exists;
}

function admin_column_exists(mysqli $conn, string $table, string $column): bool
{
    $stmt = $conn->prepare('SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();
    return $exists;
}

function admin_ensure_schema(mysqli $conn): void
{
    $conn->query(
        "CREATE TABLE IF NOT EXISTS admin_usuarios (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            usuario VARCHAR(60) NOT NULL,
            email VARCHAR(150) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            ultimo_acceso DATETIME NULL,
            creado_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            actualizado_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uq_admin_usuario (usuario),
            UNIQUE KEY uq_admin_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $conn->query(
        "CREATE TABLE IF NOT EXISTS admin_auditoria (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            admin_id INT UNSIGNED NULL,
            accion VARCHAR(80) NOT NULL,
            entidad VARCHAR(80) NULL,
            entidad_id INT UNSIGNED NULL,
            detalle TEXT NULL,
            ip VARCHAR(45) NULL,
            user_agent VARCHAR(255) NULL,
            creado_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_auditoria_fecha (creado_at),
            KEY idx_auditoria_admin (admin_id),
            CONSTRAINT fk_auditoria_admin FOREIGN KEY (admin_id) REFERENCES admin_usuarios(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $conn->query(
        "CREATE TABLE IF NOT EXISTS admin_login_intentos (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            identificador VARCHAR(190) NOT NULL,
            ip VARCHAR(45) NOT NULL,
            intentos SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            ultimo_intento DATETIME NOT NULL,
            bloqueado_hasta DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_login_identificador_ip (identificador, ip),
            KEY idx_login_bloqueado (bloqueado_hasta)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    if (admin_table_exists($conn, 'contacto')) {
        $columns = [
            'estado' => "ALTER TABLE contacto ADD COLUMN estado VARCHAR(20) NOT NULL DEFAULT 'nuevo' AFTER metodo",
            'notas_admin' => 'ALTER TABLE contacto ADD COLUMN notas_admin TEXT NULL AFTER estado',
            'archivado' => 'ALTER TABLE contacto ADD COLUMN archivado TINYINT(1) NOT NULL DEFAULT 0 AFTER notas_admin',
            'actualizado_at' => 'ALTER TABLE contacto ADD COLUMN actualizado_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER fecha_registro',
            'actualizado_por' => 'ALTER TABLE contacto ADD COLUMN actualizado_por INT UNSIGNED NULL AFTER actualizado_at',
        ];
        foreach ($columns as $column => $sql) {
            if (!admin_column_exists($conn, 'contacto', $column)) {
                $conn->query($sql);
            }
        }
    }
}

admin_ensure_schema($conn);

function admin_count_users(mysqli $conn): int
{
    $result = $conn->query('SELECT COUNT(*) AS total FROM admin_usuarios');
    return (int) ($result->fetch_assoc()['total'] ?? 0);
}

function admin_user(): ?array
{
    return isset($_SESSION['admin_user']) && is_array($_SESSION['admin_user'])
        ? $_SESSION['admin_user']
        : null;
}

function admin_require_auth(): void
{
    if (admin_user() === null) {
        admin_flash('error', 'Iniciá sesión para continuar.');
        admin_redirect('login.php');
    }
}

function admin_audit(mysqli $conn, string $action, ?string $entity = null, ?int $entityId = null, ?string $detail = null, ?int $adminId = null): void
{
    $user = admin_user();
    $resolvedAdminId = $adminId ?? (isset($user['id']) ? (int) $user['id'] : null);
    $ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
    $agent = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    $stmt = $conn->prepare('INSERT INTO admin_auditoria (admin_id, accion, entidad, entidad_id, detalle, ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ississs', $resolvedAdminId, $action, $entity, $entityId, $detail, $ip, $agent);
    $stmt->execute();
    $stmt->close();
}

function admin_login_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? 'desconocida'), 0, 45);
}

function admin_login_is_blocked(mysqli $conn, string $identifier): bool
{
    $ip = admin_login_ip();
    $stmt = $conn->prepare('SELECT bloqueado_hasta FROM admin_login_intentos WHERE identificador = ? AND ip = ? LIMIT 1');
    $stmt->bind_param('ss', $identifier, $ip);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return !empty($row['bloqueado_hasta']) && strtotime((string) $row['bloqueado_hasta']) > time();
}

function admin_record_login_failure(mysqli $conn, string $identifier): void
{
    $ip = admin_login_ip();
    $stmt = $conn->prepare('SELECT intentos, ultimo_intento, bloqueado_hasta FROM admin_login_intentos WHERE identificador = ? AND ip = ? LIMIT 1');
    $stmt->bind_param('ss', $identifier, $ip);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $windowExpired = !empty($row['ultimo_intento'])
        && strtotime((string) $row['ultimo_intento']) < time() - ADMIN_LOGIN_BLOCK_MINUTES * 60;
    $blockExpired = !empty($row['bloqueado_hasta'])
        && strtotime((string) $row['bloqueado_hasta']) <= time();
    $attempts = ($windowExpired || $blockExpired) ? 1 : (int) ($row['intentos'] ?? 0) + 1;
    $blockedUntil = $attempts >= ADMIN_LOGIN_MAX_ATTEMPTS
        ? date('Y-m-d H:i:s', time() + ADMIN_LOGIN_BLOCK_MINUTES * 60)
        : null;

    $stmt = $conn->prepare(
        'INSERT INTO admin_login_intentos (identificador, ip, intentos, ultimo_intento, bloqueado_hasta)
         VALUES (?, ?, ?, NOW(), ?)
         ON DUPLICATE KEY UPDATE intentos = VALUES(intentos), ultimo_intento = NOW(), bloqueado_hasta = VALUES(bloqueado_hasta)'
    );
    $stmt->bind_param('ssis', $identifier, $ip, $attempts, $blockedUntil);
    $stmt->execute();
    $stmt->close();
}

function admin_clear_login_failures(mysqli $conn, string $identifier): void
{
    $ip = admin_login_ip();
    $stmt = $conn->prepare('DELETE FROM admin_login_intentos WHERE identificador = ? AND ip = ?');
    $stmt->bind_param('ss', $identifier, $ip);
    $stmt->execute();
    $stmt->close();
}

function admin_contact_state_label(string $state): string
{
    $labels = [
        'nuevo' => 'Nuevo',
        'en_proceso' => 'En proceso',
        'respondido' => 'Respondido',
        'cerrado' => 'Cerrado',
    ];
    return $labels[$state] ?? ucfirst(str_replace('_', ' ', $state));
}

