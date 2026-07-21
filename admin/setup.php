<?php
require_once __DIR__ . '/bootstrap.php';

if (admin_count_users($conn) > 0) {
    admin_redirect('login.php');
}

$errors = [];
$usuario = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $usuario = trim((string) ($_POST['usuario'] ?? ''));
    $email = trim(strtolower((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmacion'] ?? '');
    $setupKey = (string) ($_POST['setup_key'] ?? '');
    $configuredSetupKey = isset($admin_setup_key) ? (string) $admin_setup_key : '';

    if (!preg_match('/^[a-zA-Z0-9._-]{3,60}$/', $usuario)) {
        $errors[] = 'El usuario debe tener entre 3 y 60 caracteres y usar solo letras, números, punto, guion o guion bajo.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $errors[] = 'Ingresá un email válido.';
    }
    if (strlen($password) < 12) {
        $errors[] = 'La contraseña debe tener al menos 12 caracteres.';
    }
    if (!hash_equals($password, $passwordConfirmation)) {
        $errors[] = 'Las contraseñas no coinciden.';
    }
    if ($configuredSetupKey !== '' && !hash_equals($configuredSetupKey, $setupKey)) {
        $errors[] = 'La clave de instalación no es correcta.';
    }

    if (!$errors && admin_count_users($conn) === 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO admin_usuarios (usuario, email, password_hash) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $usuario, $email, $hash);
        try {
            $stmt->execute();
            $adminId = (int) $stmt->insert_id;
            $stmt->close();
            admin_audit($conn, 'admin_creado', 'admin_usuarios', $adminId, 'Primer administrador creado desde setup', $adminId);
            admin_flash('success', 'Administrador creado. Ya podés iniciar sesión.');
            admin_redirect('login.php');
        } catch (mysqli_sql_exception $exception) {
            $stmt->close();
            $errors[] = 'No se pudo crear el administrador. Verificá que el usuario y email no estén registrados.';
        }
    }
}

$pageTitle = 'Configuración inicial';
require __DIR__ . '/templates/header.php';
?>
<section class="admin-auth-card">
    <div class="admin-brand">
        <span class="admin-brand__mark">B</span>
        <span>BitFlow <small>Admin</small></span>
    </div>
    <h1>Crear primer administrador</h1>
    <p>Este formulario se deshabilita automáticamente después de crear la primera cuenta.</p>

    <?php foreach ($errors as $error): ?>
        <div class="admin-flash admin-flash--error"><?= admin_h($error) ?></div>
    <?php endforeach; ?>

    <form class="admin-form" method="post" autocomplete="off">
        <?= admin_csrf_field() ?>
        <?php if (!empty($admin_setup_key)): ?>
            <div class="admin-field">
                <label for="setup_key">Clave de instalación</label>
                <input type="password" id="setup_key" name="setup_key" required autocomplete="off">
            </div>
        <?php endif; ?>
        <div class="admin-field">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" value="<?= admin_h($usuario) ?>" required maxlength="60" autocomplete="username">
        </div>
        <div class="admin-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= admin_h($email) ?>" required maxlength="150" autocomplete="email">
        </div>
        <div class="admin-field">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required minlength="12" autocomplete="new-password">
            <small>Mínimo 12 caracteres. Usá una contraseña única.</small>
        </div>
        <div class="admin-field">
            <label for="password_confirmacion">Repetir contraseña</label>
            <input type="password" id="password_confirmacion" name="password_confirmacion" required minlength="12" autocomplete="new-password">
        </div>
        <button class="admin-button" type="submit">Crear administrador</button>
    </form>
</section>
<?php require __DIR__ . '/templates/footer.php'; ?>

