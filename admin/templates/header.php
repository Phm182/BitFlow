<?php
$pageTitle = isset($pageTitle) ? (string) $pageTitle : 'Administración';
$currentSection = isset($currentSection) ? (string) $currentSection : '';
$authenticatedUser = admin_user();
?>
<!doctype html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title><?= admin_h($pageTitle) ?> | BitFlow Admin</title>
    <link rel="icon" type="image/png" href="<?= admin_h(admin_site_url('img/favicon.png')) ?>" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= admin_h(admin_site_url('img/favicon.png')) ?>">
    <link rel="stylesheet" href="<?= admin_h(admin_url('assets/admin.css')) ?>">
</head>
<body>
<?php if ($authenticatedUser): ?>
    <header class="admin-topbar">
        <a class="admin-brand" href="<?= admin_h(admin_url('dashboard.php')) ?>">
            <img class="admin-brand__logo" src="<?= admin_h(admin_site_url('img/logo-bitflow-hd.png')) ?>" alt="BitFlow">
            <span><small>Admin</small></span>
        </a>
        <button class="admin-menu-button" type="button" data-menu-toggle aria-expanded="false" aria-controls="admin-nav">Menú</button>
        <nav class="admin-nav" id="admin-nav" data-menu>
            <a class="<?= $currentSection === 'dashboard' ? 'is-active' : '' ?>" href="<?= admin_h(admin_url('dashboard.php')) ?>">Resumen</a>
            <a class="<?= $currentSection === 'contactos' ? 'is-active' : '' ?>" href="<?= admin_h(admin_url('contactos/index.php')) ?>">Contactos</a>
            <a href="<?= admin_h(admin_site_url('inicio.php')) ?>" target="_blank" rel="noopener">Ver sitio</a>
            <form action="<?= admin_h(admin_url('logout.php')) ?>" method="post">
                <?= admin_csrf_field() ?>
                <button type="submit" class="admin-nav__logout">Salir</button>
            </form>
        </nav>
    </header>
<?php endif; ?>

<main class="<?= $authenticatedUser ? 'admin-main' : 'admin-auth-shell' ?>">
    <?php foreach (admin_take_flashes() as $flash): ?>
        <div class="admin-flash admin-flash--<?= admin_h((string) ($flash['type'] ?? 'info')) ?>" role="status">
            <?= admin_h((string) ($flash['message'] ?? '')) ?>
        </div>
    <?php endforeach; ?>

