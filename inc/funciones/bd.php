<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'bitflow';
    $port = '3306';
    
$admin_setup_key = '123456';

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!$conn->set_charset('utf8mb4')) {
    error_log("Error seteando charset utf8mb4: " . $conn->error);
}

// Asegurar collation uniforme en TODAS las queries
$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("SET collation_connection = 'utf8mb4_unicode_ci'");
// Timestamps MySQL alineados a hora Argentina (misma convención que la UI y UpdatedAt en PedidoCanal).
@$conn->query("SET time_zone = '-03:00'");

/**
 * JSON UTF-8 para respuestas API (acentos y eñes legibles en el cliente).
 */
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
