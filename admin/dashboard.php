<?php
require_once __DIR__ . '/bootstrap.php';
admin_require_auth();

$stats = ['total' => 0, 'nuevos' => 0, 'pendientes' => 0, 'archivados' => 0];
if (admin_table_exists($conn, 'contacto')) {
    $result = $conn->query(
        "SELECT
            COUNT(*) AS total,
            SUM(estado = 'nuevo' AND archivado = 0) AS nuevos,
            SUM(estado IN ('nuevo', 'en_proceso') AND archivado = 0) AS pendientes,
            SUM(archivado = 1) AS archivados
         FROM contacto"
    );
    $row = $result->fetch_assoc() ?: [];
    foreach ($stats as $key => $value) {
        $stats[$key] = (int) ($row[$key] ?? 0);
    }
}

$recentContacts = [];
if (admin_table_exists($conn, 'contacto')) {
    $result = $conn->query('SELECT id, nombre, email, estado, fecha_registro FROM contacto WHERE archivado = 0 ORDER BY fecha_registro DESC, id DESC LIMIT 6');
    $recentContacts = $result->fetch_all(MYSQLI_ASSOC);
}

$auditResult = $conn->query(
    'SELECT a.accion, a.entidad, a.entidad_id, a.detalle, a.creado_at, u.usuario
     FROM admin_auditoria a
     LEFT JOIN admin_usuarios u ON u.id = a.admin_id
     ORDER BY a.creado_at DESC, a.id DESC
     LIMIT 8'
);
$audits = $auditResult->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Resumen';
$currentSection = 'dashboard';
require __DIR__ . '/templates/header.php';
?>
<header class="admin-page-head">
    <div>
        <h1>Resumen</h1>
        <p>Hola, <?= admin_h((string) admin_user()['usuario']) ?>. Este es el estado actual del sitio.</p>
    </div>
    <a class="admin-button" href="<?= admin_h(admin_url('contactos/index.php?estado=nuevo')) ?>">Ver nuevos</a>
</header>

<section class="admin-stats" aria-label="Resumen de contactos">
    <article class="admin-stat"><span>Total de contactos</span><strong><?= $stats['total'] ?></strong></article>
    <article class="admin-stat"><span>Nuevos</span><strong><?= $stats['nuevos'] ?></strong></article>
    <article class="admin-stat"><span>Pendientes</span><strong><?= $stats['pendientes'] ?></strong></article>
    <article class="admin-stat"><span>Archivados</span><strong><?= $stats['archivados'] ?></strong></article>
</section>

<div class="admin-grid admin-grid--dashboard">
    <section class="admin-panel">
        <div class="admin-page-head">
            <div><h2>Contactos recientes</h2></div>
            <a href="<?= admin_h(admin_url('contactos/index.php')) ?>">Ver todos</a>
        </div>
        <?php if (!$recentContacts): ?>
            <p class="admin-empty">Todavía no hay contactos registrados.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Contacto</th><th>Estado</th><th>Fecha</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($recentContacts as $contact): ?>
                        <tr>
                            <td>
                                <a class="admin-table__primary" href="<?= admin_h(admin_url('contactos/ver.php?id=' . (int) $contact['id'])) ?>"><?= admin_h((string) $contact['nombre']) ?></a>
                                <span class="admin-table__secondary"><?= admin_h((string) ($contact['email'] ?: 'Sin email')) ?></span>
                            </td>
                            <td><span class="admin-badge admin-badge--<?= admin_h((string) $contact['estado']) ?>"><?= admin_h(admin_contact_state_label((string) $contact['estado'])) ?></span></td>
                            <td><?= admin_h(date('d/m/Y H:i', strtotime((string) $contact['fecha_registro']))) ?></td>
                            <td><a href="<?= admin_h(admin_url('contactos/ver.php?id=' . (int) $contact['id'])) ?>">Abrir</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <aside class="admin-panel">
        <h2>Auditoría reciente</h2>
        <?php if (!$audits): ?>
            <p class="admin-empty">Sin actividad registrada.</p>
        <?php else: ?>
            <ul class="admin-audit-list">
                <?php foreach ($audits as $audit): ?>
                    <li>
                        <strong><?= admin_h((string) ($audit['usuario'] ?: 'Sistema')) ?></strong>
                        · <?= admin_h(str_replace('_', ' ', (string) $audit['accion'])) ?>
                        <?php if ($audit['entidad_id']): ?> #<?= (int) $audit['entidad_id'] ?><?php endif; ?>
                        <time datetime="<?= admin_h((string) $audit['creado_at']) ?>"><?= admin_h(date('d/m/Y H:i', strtotime((string) $audit['creado_at']))) ?></time>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </aside>
</div>
<?php require __DIR__ . '/templates/footer.php'; ?>

