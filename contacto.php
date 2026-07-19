<?php
    $bodyClass = 'page-sub page-contacto page-oneshot';
    $hideSiteFooter = true;
    include 'inc/templates/header.php';
?>

<div class="header section-Proyect-header">
    <?php include 'inc/templates/nav.php'; ?>
</div>

<div class="page-sub-glow page-sub-glow--left" aria-hidden="true"></div>
<div class="page-sub-glow page-sub-glow--right" aria-hidden="true"></div>

<main class="oneshot-main">
    <div class="oneshot-inner oneshot-inner--contacto">
        <header class="oneshot-head oneshot-head--compact">
            <p class="page-hero__brand"><span>BitFlow</span></p>
            <h1 class="page-hero__title">Contactanos</h1>
            <p class="page-hero__lead">
                Contanos tu idea. Respondemos con foco técnico y comercial.
            </p>
        </header>

        <div class="page-contacto-grid">
            <div class="page-contacto__form">
                <?php include 'inc/templates/Contacto.php'; ?>
            </div>
            <div class="page-contacto__viz">
                <?php include 'inc/templates/contacto-showcase.php'; ?>
            </div>
        </div>

        <p class="oneshot-meta">
            Comercial <a href="https://wa.me/5491157595207" target="_blank" rel="noopener noreferrer">+54 9 11 5759-5207</a>
            · Técnica <a href="https://wa.me/5491159546184" target="_blank" rel="noopener noreferrer">+54 9 11 5954-6184</a>
        </p>
    </div>
</main>

<?php
    $extraScripts = array('js/contacto-showcase.js');
    include 'inc/templates/footer.php';
?>
