<?php
require_once __DIR__ . '/bootstrap.php';

if (admin_user() !== null) {
    admin_redirect('dashboard.php');
}
if (admin_count_users($conn) === 0) {
    admin_redirect('setup.php');
}

$error = '';
$identifierValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $identifierValue = trim((string) ($_POST['identificador'] ?? ''));
    $identifier = strtolower($identifierValue);
    $password = (string) ($_POST['password'] ?? '');

    if (admin_login_is_blocked($conn, $identifier)) {
        $error = 'Demasiados intentos fallidos. Esperá 15 minutos antes de volver a intentar.';
    } else {
        $stmt = $conn->prepare('SELECT id, usuario, email, password_hash FROM admin_usuarios WHERE activo = 1 AND (LOWER(usuario) = ? OR LOWER(email) = ?) LIMIT 1');
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $user = admin_stmt_fetch_assoc($stmt);
        $stmt->close();

        if ($user && password_verify($password, (string) $user['password_hash'])) {
            admin_clear_login_failures($conn, $identifier);
            session_regenerate_id(true);
            $_SESSION['admin_user'] = [
                'id' => (int) $user['id'],
                'usuario' => (string) $user['usuario'],
                'email' => (string) $user['email'],
            ];
            unset($_SESSION['admin_csrf']);
            admin_csrf_token();

            $adminId = (int) $user['id'];
            $stmt = $conn->prepare('UPDATE admin_usuarios SET ultimo_acceso = NOW() WHERE id = ?');
            $stmt->bind_param('i', $adminId);
            $stmt->execute();
            $stmt->close();
            admin_audit($conn, 'login', 'admin_usuarios', $adminId, 'Inicio de sesión correcto');
            admin_redirect('dashboard.php');
        }

        admin_record_login_failure($conn, $identifier);
        $error = 'Usuario, email o contraseña incorrectos.';
    }
}

$pageTitle = 'Iniciar sesión';
require __DIR__ . '/templates/header.php';
?>
<section class="admin-auth-card">
    <div class="admin-brand">
        <img class="admin-brand__logo admin-brand__logo--auth" src="<?= admin_h(admin_site_url('img/logo-bitflow-hd.png')) ?>" alt="BitFlow">
        <span><small>Admin</small></span>
    </div>
    <h1>Iniciar sesión</h1>
    <p>Acceso exclusivo para administradores.</p>

    <?php if ($error !== ''): ?>
        <div class="admin-flash admin-flash--error" role="alert"><?= admin_h($error) ?></div>
    <?php endif; ?>

    <form class="admin-form" method="post">
        <?= admin_csrf_field() ?>
        <div class="admin-field">
            <label for="identificador">Usuario o email</label>
            <input type="text" id="identificador" name="identificador" value="<?= admin_h($identifierValue) ?>" required maxlength="190" autocomplete="username" autofocus>
        </div>
        <div class="admin-field">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button class="admin-button" type="submit">Ingresar</button>
    </form>
</section>
<?php require __DIR__ . '/templates/footer.php'; ?>

