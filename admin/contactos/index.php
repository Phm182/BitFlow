<?php
require_once dirname(__DIR__) . '/bootstrap.php';
admin_require_auth();

$search = trim((string) ($_GET['q'] ?? ''));
$state = (string) ($_GET['estado'] ?? '');
$archive = (string) ($_GET['archivo'] ?? 'activos');
$dateFrom = (string) ($_GET['desde'] ?? '');
$dateTo = (string) ($_GET['hasta'] ?? '');
$page = max(1, (int) ($_GET['pagina'] ?? 1));
$perPage = 15;

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

$where = " WHERE
    (? = '' OR nombre LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%') OR celular LIKE CONCAT('%', ?, '%') OR consulta LIKE CONCAT('%', ?, '%'))
    AND (? = '' OR estado = ?)
    AND (? = 'todos' OR (? = 'archivados' AND archivado = 1) OR (? = 'activos' AND archivado = 0))
    AND (? = '' OR fecha_registro >= CONCAT(?, ' 00:00:00'))
    AND (? = '' OR fecha_registro <= CONCAT(?, ' 23:59:59'))";

$countStmt = $conn->prepare('SELECT COUNT(*) AS total FROM contacto' . $where);
$countStmt->bind_param(
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
$countStmt->execute();
$countRow = admin_stmt_fetch_assoc($countStmt);
$total = (int) ($countRow['total'] ?? 0);
$countStmt->close();

$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $conn->prepare(
    'SELECT id, nombre, celular, email, metodo, estado, archivado, fecha_registro
     FROM contacto' . $where . '
     ORDER BY fecha_registro DESC, id DESC
     LIMIT ? OFFSET ?'
);
$stmt->bind_param(
    'ssssssssssssssii',
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
    $dateTo,
    $perPage,
    $offset
);
$stmt->execute();
$contacts = admin_stmt_fetch_all($stmt);
$stmt->close();

function admin_contacts_query(array $overrides = []): string
{
    $params = [
        'q' => (string) ($_GET['q'] ?? ''),
        'estado' => (string) ($_GET['estado'] ?? ''),
        'archivo' => (string) ($_GET['archivo'] ?? 'activos'),
        'desde' => (string) ($_GET['desde'] ?? ''),
        'hasta' => (string) ($_GET['hasta'] ?? ''),
    ];
    $params = array_merge($params, $overrides);
    return http_build_query(array_filter($params, static function ($value): bool {
        return $value !== '';
    }));
}

$pageTitle = 'Contactos';
$currentSection = 'contactos';
require dirname(__DIR__) . '/templates/header.php';
?>
<header class="admin-page-head">
    <div>
        <h1>Contactos</h1>
        <p><?= $total ?> registro<?= $total === 1 ? '' : 's' ?> encontrado<?= $total === 1 ? '' : 's' ?>.</p>
    </div>
    <div class="admin-actions">
        <a class="admin-button admin-button--secondary" href="<?= admin_h(admin_url('contactos/exportar.php?' . admin_contacts_query())) ?>">Exportar CSV</a>
    </div>
</header>

<form class="admin-filter" method="get">
    <div class="admin-field">
        <label for="q">Buscar</label>
        <input type="search" id="q" name="q" value="<?= admin_h($search) ?>" placeholder="Nombre, email, celular o consulta">
    </div>
    <div class="admin-field">
        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="">Todos</option>
            <?php foreach (ADMIN_CONTACT_STATES as $option): ?>
                <option value="<?= admin_h($option) ?>" <?= $state === $option ? 'selected' : '' ?>><?= admin_h(admin_contact_state_label($option)) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="admin-field">
        <label for="archivo">Archivo</label>
        <select id="archivo" name="archivo">
            <option value="activos" <?= $archive === 'activos' ? 'selected' : '' ?>>Activos</option>
            <option value="archivados" <?= $archive === 'archivados' ? 'selected' : '' ?>>Archivados</option>
            <option value="todos" <?= $archive === 'todos' ? 'selected' : '' ?>>Todos</option>
        </select>
    </div>
    <div class="admin-field">
        <label for="desde">Desde</label>
        <input type="date" id="desde" name="desde" value="<?= admin_h($dateFrom) ?>">
    </div>
    <div class="admin-field">
        <label for="hasta">Hasta</label>
        <input type="date" id="hasta" name="hasta" value="<?= admin_h($dateTo) ?>">
    </div>
    <button class="admin-button" type="submit">Filtrar</button>
</form>

<?php if (!$contacts): ?>
    <div class="admin-panel admin-empty">No hay contactos para los filtros seleccionados.</div>
<?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr><th>Contacto</th><th>Celular</th><th>Respuesta</th><th>Estado</th><th>Fecha</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td>
                        <a class="admin-table__primary" href="<?= admin_h(admin_url('contactos/ver.php?id=' . (int) $contact['id'])) ?>"><?= admin_h((string) $contact['nombre']) ?></a>
                        <span class="admin-table__secondary"><?= admin_h((string) ($contact['email'] ?: 'Sin email')) ?></span>
                    </td>
                    <td><?= admin_h((string) $contact['celular']) ?></td>
                    <td><?= admin_h(ucfirst((string) $contact['metodo'])) ?></td>
                    <td>
                        <span class="admin-badge admin-badge--<?= $contact['archivado'] ? 'archivado' : admin_h((string) $contact['estado']) ?>">
                            <?= $contact['archivado'] ? 'Archivado' : admin_h(admin_contact_state_label((string) $contact['estado'])) ?>
                        </span>
                    </td>
                    <td><?= admin_h(date('d/m/Y H:i', strtotime((string) $contact['fecha_registro']))) ?></td>
                    <td><a href="<?= admin_h(admin_url('contactos/ver.php?id=' . (int) $contact['id'])) ?>">Ver detalle</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination" aria-label="Paginación">
            <?php for ($number = 1; $number <= $totalPages; $number++): ?>
                <?php if ($number === 1 || $number === $totalPages || abs($number - $page) <= 2): ?>
                    <?php if ($number === $page): ?>
                        <span class="is-current" aria-current="page"><?= $number ?></span>
                    <?php else: ?>
                        <a href="?<?= admin_h(admin_contacts_query(['pagina' => $number])) ?>"><?= $number ?></a>
                    <?php endif; ?>
                <?php elseif ($number === 2 || $number === $totalPages - 1): ?>
                    <span>…</span>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
<?php require dirname(__DIR__) . '/templates/footer.php'; ?>

