<?php
/**
 * Copiá este archivo como bd.php y completá las credenciales del entorno.
 * bd.php NO se versiona (está en .gitignore) para no pisar producción.
 */
$host = 'localhost';
$user = 'USUARIO_BD';
$password = 'PASSWORD_BD';
$dbname = 'bitflow';
$port = 3306;

$admin_setup_key = 'CAMBIAR_CLAVE_INSTALACION';

$conn = new mysqli($host, $user, $password, $dbname, (int) $port);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

if (!$conn->set_charset('utf8mb4')) {
    error_log('Error seteando charset utf8mb4: ' . $conn->error);
}

$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("SET collation_connection = 'utf8mb4_unicode_ci'");
@$conn->query("SET time_zone = '-03:00'");

if (!function_exists('contapp_json_encode')) {
    function contapp_json_encode($data, int $extraFlags = 0): string
    {
        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | $extraFlags;
        $json = json_encode($data, $flags);
        if ($json === false) {
            return '{"success":false,"message":"Error al codificar respuesta JSON"}';
        }
        return $json;
    }
}

if (!function_exists('contapp_json_response')) {
    function contapp_json_response(array $data, int $httpCode = 200): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        http_response_code($httpCode);
        echo contapp_json_encode($data);
        exit;
    }
}
