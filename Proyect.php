<?php
    $bodyClass = 'page-sub page-proyectos page-oneshot';
    $hideSiteFooter = true;
    include 'inc/templates/header.php';
?>

<div class="header section-Proyect-header">
    <?php include 'inc/templates/nav.php'; ?>
</div>

<div class="page-sub-glow page-sub-glow--left" aria-hidden="true"></div>
<div class="page-sub-glow page-sub-glow--right" aria-hidden="true"></div>

<main class="oneshot-main">
    <div class="oneshot-inner">
        <header class="oneshot-head">
            <p class="page-hero__brand"><span>BitFlow</span></p>
            <h1 class="page-hero__title glitch" data-text="Nuestros Proyectos">Nuestros Proyectos</h1>
            <p class="page-hero__lead">
                Productos digitales de la idea a producción.
            </p>
        </header>

        <div class="Proyect pagina-Proyect oneshot-proyectos" id="Proyect">
            <div class="proyectos-carousel">
                <div class="contenedor-Proyect" id="proyectos-track">
                    <div class="Proy">
                        <div class="proy-icon proy-icon--logo">
                            <img class="proy-logo proy-logo--contapp" src="imgLogo/Contapp.png" alt="Logo de Contapp">
                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                        </div>
                        <h3>Contapp</h3>
                        <p>Gestión contable y administrativa con reportes y dashboard en tiempo real.</p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>

                    <div class="Proy">
                        <div class="proy-icon proy-icon--logo">
                            <img class="proy-logo proy-logo--sirius" src="imgLogo/Sirius.png" alt="Logo de Sirius">
                            <i class="fas fa-satellite-dish" aria-hidden="true"></i>
                        </div>
                        <h3>Sirius</h3>
                        <p>Escuela náutica: cursos de lanchas, veleros y yates con práctica real en Costanera Norte.</p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>

                    <div class="Proy">
                        <div class="proy-icon proy-icon--logo">
                            <img class="proy-logo proy-logo--unfollow" src="imgLogo/Unfollow%20Assist.png" alt="Logo de Unfollow Assist">
                            <i class="fas fa-user-minus" aria-hidden="true"></i>
                        </div>
                        <h3>Unfollower Assist</h3>
                        <p>Análisis inteligente de seguidores y presencia en redes.</p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>
                </div>
                <div class="proyectos-carousel__controls" aria-hidden="false">
                    <button type="button" class="proyectos-carousel__arrow proyectos-carousel__arrow--prev" aria-label="Proyecto anterior">‹</button>
                    <div class="proyectos-dots" id="proyectos-dots" role="tablist" aria-label="Proyectos"></div>
                    <button type="button" class="proyectos-carousel__arrow proyectos-carousel__arrow--next" aria-label="Proyecto siguiente">›</button>
                </div>
            </div>

            <div class="proyectos-forge" id="proyectos-forge" aria-hidden="true">
                <canvas class="proyectos-forge__canvas" id="proyectos-forge-canvas"></canvas>
                <div class="proyectos-forge__content">
                    <div class="proyectos-forge__stack" aria-hidden="true">
                        <span class="proyectos-forge__layer" style="--i:0">UI</span>
                        <span class="proyectos-forge__layer" style="--i:1">API</span>
                        <span class="proyectos-forge__layer" style="--i:2">Data</span>
                        <span class="proyectos-forge__layer" style="--i:3">Cloud</span>
                    </div>
                    <div class="proyectos-forge__beam" aria-hidden="true"></div>
                    <div class="proyectos-forge__outputs" id="proyectos-forge-outputs"></div>
                    <p class="proyectos-forge__caption">Arquitectura a medida · de la idea al producto</p>
                </div>
            </div>
        </div>

        <div class="page-cta page-cta--inline">
            <p class="page-cta__text">¿Querés construir algo con nosotros?</p>
            <a href="contacto.php" class="page-cta__btn">Hablemos de tu proyecto</a>
        </div>

        <p class="oneshot-meta">
            Comercial <a href="mailto:yamila.barral@cont-app.com">yamila.barral@cont-app.com</a>
            · Técnica <a href="mailto:pablo.morales@cont-app.com">pablo.morales@cont-app.com</a>
        </p>
    </div>
</main>

<?php
    $extraScripts = array(
        'js/proyectos-carousel.js',
        'js/proyectos-forge.js',
    );
    include 'inc/templates/footer.php';
?>
