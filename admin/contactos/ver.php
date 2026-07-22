<?php
require_once dirname(__DIR__) . '/bootstrap.php';
admin_require_auth();

$contactId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($contactId <= 0) {
    admin_flash('error', 'Contacto no válido.');
    admin_redirect('contactos/index.php');
}

function admin_find_contact(mysqli $conn, int $id): ?array
{
    $stmt = $conn->prepare('SELECT * FROM contacto WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $contact = admin_stmt_fetch_assoc($stmt);
    $stmt->close();
    return $contact;
}

$contact = admin_find_contact($conn, $contactId);
if ($contact === null) {
    http_response_code(404);
    $pageTitle = 'Contacto no encontrado';
    $currentSection = 'contactos';
    require dirname(__DIR__) . '/templates/header.php';
    echo '<div class="admin-panel admin-empty">El contacto solicitado no existe.</div>';
    require dirname(__DIR__) . '/templates/footer.php';
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $action = (string) ($_POST['accion'] ?? '');

    if ($action === 'guardar') {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $celular = trim((string) ($_POST['celular'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $consulta = trim((string) ($_POST['consulta'] ?? ''));
        $metodo = (string) ($_POST['metodo'] ?? '');
        $estado = (string) ($_POST['estado'] ?? '');
        $notas = trim((string) ($_POST['notas_admin'] ?? ''));

        if ($nombre === '' || strlen($nombre) > 100) {
            $errors[] = 'Ingresá un nombre de hasta 100 caracteres.';
        }
        if ($celular === '' || strlen($celular) > 30) {
            $errors[] = 'Ingresá un celular de hasta 30 caracteres.';
        }
        if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150)) {
            $errors[] = 'Ingresá un email válido.';
        }
        if (!in_array($metodo, ['whatsapp', 'email', 'ambos metodos'], true)) {
            $errors[] = 'Seleccioná un método de respuesta válido.';
        }
        if (!in_array($estado, ADMIN_CONTACT_STATES, true)) {
            $errors[] = 'Seleccioná un estado válido.';
        }

        if (!$errors) {
            $adminId = (int) admin_user()['id'];
            $stmt = $conn->prepare(
                'UPDATE contacto
                 SET nombre = ?, celular = ?, email = NULLIF(?, \'\'), consulta = ?, metodo = ?, estado = ?, notas_admin = ?, actualizado_por = ?
                 WHERE id = ?'
            );
            $stmt->bind_param('sssssssii', $nombre, $celular, $email, $consulta, $metodo, $estado, $notas, $adminId, $contactId);
            $stmt->execute();
            $stmt->close();
            admin_audit($conn, 'contacto_actualizado', 'contacto', $contactId, 'Datos, estado o notas actualizados');
            admin_flash('success', 'Contacto actualizado correctamente.');
            admin_redirect('contactos/ver.php?id=' . $contactId);
        }

        $contact = array_merge($contact, [
            'nombre' => $nombre,
            'celular' => $celular,
            'email' => $email,
            'consulta' => $consulta,
            'metodo' => $metodo,
            'estado' => $estado,
            'notas_admin' => $notas,
        ]);
    } elseif ($action === 'archivo') {
        $newArchivedValue = (int) !$contact['archivado'];
        $stmt = $conn->prepare('UPDATE contacto SET archivado = ?, actualizado_por = ? WHERE id = ?');
        $adminId = (int) admin_user()['id'];
        $stmt->bind_param('iii', $newArchivedValue, $adminId, $contactId);
        $stmt->execute();
        $stmt->close();
        admin_audit($conn, $newArchivedValue ? 'contacto_archivado' : 'contacto_desarchivado', 'contacto', $contactId);
        admin_flash('success', $newArchivedValue ? 'Contacto archivado.' : 'Contacto desarchivado.');
        admin_redirect('contactos/ver.php?id=' . $contactId);
    } elseif ($action === 'eliminar') {
        $stmt = $conn->prepare('DELETE FROM contacto WHERE id = ?');
        $stmt->bind_param('i', $contactId);
        $stmt->execute();
        $stmt->close();
        admin_audit($conn, 'contacto_eliminado', 'contacto', $contactId, 'Registro eliminado definitivamente');
        admin_flash('success', 'Contacto eliminado definitivamente.');
        admin_redirect('contactos/index.php');
    } else {
        http_response_code(400);
        exit('Acción no válida.');
    }
}

$pageTitle = 'Contacto #' . $contactId;
$currentSection = 'contactos';
require dirname(__DIR__) . '/templates/header.php';
?>
<header class="admin-page-head">
    <div>
        <a href="<?= admin_h(admin_url('contactos/index.php')) ?>">← Volver a contactos</a>
        <h1><?= admin_h((string) $contact['nombre']) ?></h1>
        <p>Recibido el <?= admin_h(date('d/m/Y \a \l\a\s H:i', strtotime((string) $contact['fecha_registro']))) ?>.</p>
    </div>
    <span class="admin-badge admin-badge--<?= $contact['archivado'] ? 'archivado' : admin_h((string) $contact['estado']) ?>">
        <?= $contact['archivado'] ? 'Archivado' : admin_h(admin_contact_state_label((string) $contact['estado'])) ?>
    </span>
</header>

<?php foreach ($errors as $error): ?>
    <div class="admin-flash admin-flash--error" role="alert"><?= admin_h($error) ?></div>
<?php endforeach; ?>

<div class="admin-grid admin-grid--detail">
    <section class="admin-panel">
        <h2>Detalle original</h2>
        <dl class="admin-detail">
            <div><dt>Nombre</dt><dd><?= admin_h((string) $contact['nombre']) ?></dd></div>
            <div><dt>Celular</dt><dd><a href="https://wa.me/<?= admin_h(preg_replace('/\D+/', '', (string) $contact['celular'])) ?>" target="_blank" rel="noopener"><?= admin_h((string) $contact['celular']) ?></a></dd></div>
            <div><dt>Email</dt><dd><?= $contact['email'] ? '<a href="mailto:' . admin_h((string) $contact['email']) . '">' . admin_h((string) $contact['email']) . '</a>' : 'Sin email' ?></dd></div>
            <div><dt>Método preferido</dt><dd><?= admin_h(ucfirst((string) $contact['metodo'])) ?></dd></div>
            <div><dt>Consulta</dt><dd><?= admin_h((string) $contact['consulta']) ?></dd></div>
        </dl>
    </section>

    <section class="admin-panel">
        <h2>Editar y gestionar</h2>
        <form class="admin-form" method="post">
            <?= admin_csrf_field() ?>
            <input type="hidden" name="id" value="<?= $contactId ?>">
            <input type="hidden" name="accion" value="guardar">
            <div class="admin-field">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= admin_h((string) $contact['nombre']) ?>" required maxlength="100">
            </div>
            <div class="admin-field">
                <label for="celular">Celular</label>
                <input type="tel" id="celular" name="celular" value="<?= admin_h((string) $contact['celular']) ?>" required maxlength="30">
            </div>
            <div class="admin-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= admin_h((string) $contact['email']) ?>" maxlength="150">
            </div>
            <div class="admin-field">
                <label for="metodo">Método de respuesta</label>
                <select id="metodo" name="metodo">
                    <option value="whatsapp" <?= $contact['metodo'] === 'whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                    <option value="email" <?= $contact['metodo'] === 'email' ? 'selected' : '' ?>>Correo electrónico</option>
                    <option value="ambos metodos" <?= $contact['metodo'] === 'ambos metodos' ? 'selected' : '' ?>>Ambos</option>
                </select>
            </div>
            <div class="admin-field">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <?php foreach (ADMIN_CONTACT_STATES as $state): ?>
                        <option value="<?= admin_h($state) ?>" <?= $contact['estado'] === $state ? 'selected' : '' ?>><?= admin_h(admin_contact_state_label($state)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="admin-field">
                <label for="consulta">Consulta</label>
                <textarea id="consulta" name="consulta"><?= admin_h((string) $contact['consulta']) ?></textarea>
            </div>
            <div class="admin-field">
                <label for="notas_admin">Notas internas</label>
                <textarea id="notas_admin" name="notas_admin" placeholder="Solo visibles para administradores"><?= admin_h((string) $contact['notas_admin']) ?></textarea>
            </div>
            <button class="admin-button" type="submit">Guardar cambios</button>
        </form>
    </section>
</div>

<section class="admin-panel admin-danger-zone">
    <h2>Acciones del registro</h2>
    <div class="admin-actions">
        <form method="post" data-confirm="¿Confirmás que querés <?= $contact['archivado'] ? 'desarchivar' : 'archivar' ?> este contacto?">
            <?= admin_csrf_field() ?>
            <input type="hidden" name="id" value="<?= $contactId ?>">
            <input type="hidden" name="accion" value="archivo">
            <button class="admin-button admin-button--secondary" type="submit"><?= $contact['archivado'] ? 'Desarchivar' : 'Archivar' ?></button>
        </form>
        <form method="post" data-confirm="Esta acción elimina el contacto definitivamente y no se puede deshacer. ¿Continuar?">
            <?= admin_csrf_field() ?>
            <input type="hidden" name="id" value="<?= $contactId ?>">
            <input type="hidden" name="accion" value="eliminar">
            <button class="admin-button admin-button--danger" type="submit">Eliminar definitivamente</button>
        </form>
    </div>
</section>
<?php require dirname(__DIR__) . '/templates/footer.php'; ?>

