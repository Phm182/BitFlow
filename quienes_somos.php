<?php
    $bodyClass = 'page-sub page-quienes has-scroll-sections';
    $hideSiteFooter = true;
    include 'inc/templates/header.php';
?>

<div class="header section-Proyect-header">
    <?php include 'inc/templates/nav.php'; ?>
</div>

<div class="page-sub-glow page-sub-glow--left" aria-hidden="true"></div>
<div class="page-sub-glow page-sub-glow--right" aria-hidden="true"></div>

<section id="qs-hero" class="page-section page-section--hero qs-section qs-section--hero">
    <canvas class="qs-constellation" id="qs-constellation" aria-hidden="true"></canvas>

    <div class="qs-hero-inner">
        <p class="page-hero__brand"><span>BitFlow</span></p>
        <h1 class="page-hero__title">¿Quiénes somos?</h1>
        <p class="page-hero__lead">
            Un equipo de desarrollo fundado por dos desarrolladores apasionados por la tecnología.
            Creamos productos digitales que impactan de verdad.
        </p>

        <div class="page-quienes__gallery hex-gallery-section">
            <?php include 'inc/templates/hex-gallery.php'; ?>
        </div>
    </div>

    <div class="section-scroll-hint">
        <?php $scrollTarget = '#qs-historia'; include 'inc/templates/boton-scroll.php'; ?>
    </div>
</section>

<section id="qs-historia" class="page-section qs-section qs-section--story" aria-label="Nuestra historia">
    <div class="qs-story-inner">
        <p class="page-story__eyebrow">Nuestra historia</p>
        <h2 class="page-story__heading">De la idea al código</h2>

        <ol class="page-story__rail">
            <li class="page-story__step">
                <span class="page-story__marker" aria-hidden="true"><i class="fas fa-lightbulb"></i></span>
                <div class="page-story__body">
                    <h3>Origen</h3>
                    <p>
                        BitFlow nació de dos desarrolladores con una visión: software de calidad
                        que resuelve problemas reales. De proyectos entre amigos a empresa de
                        desarrollo web y soporte técnico.
                    </p>
                </div>
            </li>
            <li class="page-story__step">
                <span class="page-story__marker" aria-hidden="true"><i class="fas fa-cubes"></i></span>
                <div class="page-story__body">
                    <h3>¿Qué hacemos?</h3>
                    <ul class="page-story__services">
                        <li><i class="fas fa-code" aria-hidden="true"></i> Desarrollo de Software</li>
                        <li><i class="fas fa-laptop" aria-hidden="true"></i> Armado PC/notebooks</li>
                        <li><i class="fas fa-headset" aria-hidden="true"></i> Soporte y mantenimiento</li>
                    </ul>
                </div>
            </li>
            <li class="page-story__step">
                <span class="page-story__marker" aria-hidden="true"><i class="fas fa-compass"></i></span>
                <div class="page-story__body">
                    <h3>Nuestra filosofía</h3>
                    <p>
                        Tecnología accesible, funcional y bien diseñada. Cada proyecto como propio:
                        código sólido, UX clara y comunicación transparente.
                    </p>
                </div>
            </li>
            <li class="page-story__step">
                <span class="page-story__marker" aria-hidden="true"><i class="fas fa-route"></i></span>
                <div class="page-story__body">
                    <h3>Cómo trabajamos</h3>
                    <p>
                        Escuchamos tu necesidad, diseñamos la solución y la entregamos lista para usar.
                        Sin vueltas: plazos claros, avance visible y acompañamiento después del lanzamiento.
                    </p>
                </div>
            </li>
        </ol>

        <div class="page-cta page-cta--inline">
            <p class="page-cta__text">¿Querés construir algo con nosotros?</p>
            <a href="contacto.php" class="page-cta__btn">Hablemos de tu proyecto</a>
        </div>
    </div>
</section>

<div class="scroll-next-fixed" id="scroll-next-fixed" hidden>
    <?php $scrollTarget = '#qs-historia'; include 'inc/templates/boton-scroll.php'; ?>
</div>

<?php
    $extraScripts = array(
        'js/scroll-sections.js',
        'js/quienes-constellation.js',
    );
    include 'inc/templates/footer.php';
?>
