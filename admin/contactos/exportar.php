<?php
require_once dirname(__DIR__) . '/bootstrap.php';
admin_require_auth();

$search = trim((string) ($_GET['q'] ?? ''));
$state = (string) ($_GET['estado'] ?? '');
$archive = (string) ($_GET['archivo'] ?? 'activos');
$dateFrom = (string) ($_GET['desde'] ?? '');
$dateTo = (string) ($_GET['hasta'] ?? '');

if ($state !== '' && !in_array($state, ADMIN_CONTACT_STATES, true)) {
    $state = '';
}
if (!in_array($archive, ['activos', 'archivados', 'todos'], true)) {
    $archive = 'activos';
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
    $dateFrom = '';
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
    $dateTo = '';
}

$stmt = $conn->prepare(
    "SELECT id, nombre, celular, email, consulta, metodo, estado, archivado, notas_admin, fecha_registro, actualizado_at
     FROM contacto
     WHERE
        (? = '' OR nombre LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%') OR celular LIKE CONCAT('%', ?, '%') OR consulta LIKE CONCAT('%', ?, '%'))
        AND (? = '' OR estado = ?)
        AND (? = 'todos' OR (? = 'archivados' AND archivado = 1) OR (? = 'activos' AND archivado = 0))
        AND (? = '' OR fecha_registro >= CONCAT(?, ' 00:00:00'))
        AND (? = '' OR fecha_registro <= CONCAT(?, ' 23:59:59'))
     ORDER BY fecha_registro DESC, id DESC"
);
$stmt->bind_param(
    'ssssssssssssss',
    $search,
    $search,
    $search,
    $search,
    $search,
    $state,
    $state,
    $archive,
    $archive,
    $archive,
    $dateFrom,
    $dateFrom,
    $dateTo,
    $dateTo
);
$stmt->execute();
$contacts = admin_stmt_fetch_all($stmt);
$stmt->close();

function admin_csv_value($value): string
{
    $text = (string) $value;
    if ($text !== '' && preg_match('/^[=\-+@\t\r]/', $text)) {
        return "'" . $text;
    }
    return $text;
}

admin_audit($conn, 'contactos_exportados', 'contacto', null, 'Exportación CSV con ' . count($contacts) . ' registros');

header_remove('Content-Security-Policy');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="contactos-bitflow-' . date('Y-m-d-His') . '.csv"');
header('X-Content-Type-Options: nosniff');

$output = fopen('php://output', 'wb');
fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ['ID', 'Nombre', 'Celular', 'Email', 'Consulta', 'Método', 'Estado', 'Archivado', 'Notas internas', 'Fecha', 'Actualizado'], ';');

foreach ($contacts as $row) {
    fputcsv($output, [
        (int) $row['id'],
        admin_csv_value($row['nombre']),
        admin_csv_value($row['celular']),
        admin_csv_value($row['email']),
        admin_csv_value($row['consulta']),
        admin_csv_value($row['metodo']),
        admin_csv_value($row['estado']),
        !empty($row['archivado']) ? 'Sí' : 'No',
        admin_csv_value($row['notas_admin'] ?? ''),
        $row['fecha_registro'],
        $row['actualizado_at'] ?? '',
    ], ';');
}

fclose($output);
exit;

