<?php 
    $bodyClass = 'page-inicio';
	include 'inc/templates/header.php';
    $splashMode = 'continue';
    $showSplashButton = false;
    $showScrollButton = true;
    $scrollTarget = '#section-quienes';
?>

                <div class="header header--delayed">
                    <?php include 'inc/templates/nav.php'; ?>
                </div>

        <section id="section-hero" class="page-section page-section--hero">
            <div class="contenedorHeader contenedorHeader--inicio">
                <?php include 'inc/templates/splash-hero.php'; ?>
            </div>
        </section>

        <section id="section-quienes" class="reveal-section page-section">
            <div class="section-quienes-inicio">
                <div class="texto1 ordenador">
                    <p class="nombre"><span>¿Qué y quiénes somos?</span></p>
                    <h3>Somos BitFlow, un equipo de desarrollo de software</h3>
                    <p class="nosotros">
                        Somos un equipo fundado por dos desarrolladores con años de experiencia en tecnología. 
                        Creamos aplicaciones web, móviles y soluciones digitales que resuelven problemas reales 
                        para empresas y emprendedores.
                    </p>
                    <a href="quienes_somos.php" class="nuestra-historia">Nuestra Historia</a>
                </div>

                <div id="quienes_somos2" class="texto1 mobile">
                    <p class="nombre"><span>¿Qué y quiénes somos?</span></p>
                    <h3>Somos BitFlow, un equipo de desarrollo de software</h3>
                    <p class="nosotros">
                        Somos un equipo fundado por dos desarrolladores con años de experiencia en tecnología. 
                        Creamos aplicaciones web, móviles y soluciones digitales que resuelven problemas reales 
                        para empresas y emprendedores.
                    </p>
                </div>

                <div class="hex-gallery-center">
                    <?php include 'inc/templates/hex-gallery.php'; ?>
                </div>

                <div class="section-scroll-hint section-scroll-hint--quienes">
                    <?php $scrollTarget = '#section-proyectos'; include 'inc/templates/boton-scroll.php'; ?>
                </div>

                <div class="btn-nuestra-historia2 mobile-only-historia">
                    <a href="quienes_somos.php" class="nuestra-historia2">Nuestra Historia</a>
                </div>
            </div>
        </section>
        
        <section id="section-proyectos" class="reveal-section page-section">
            <div id="Proyect" class="Proyect">
                <p class="nombre glitch" data-text="Nuestros Proyectos"><span>Nuestros Proyectos</span></p>
                <div class="contenedor-Proyect">
                    <div class="Proy">
                        <div class="proy-icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Contapp</h3>
                        <p>
                            Plataforma de gestión contable y administrativa diseñada para simplificar 
                            el control financiero de tu negocio.
                        </p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>
    
                    <div class="Proy">
                        <div class="proy-icon"><i class="fas fa-satellite-dish"></i></div>
                        <h3>Sirius</h3>
                        <p>
                            Sistema de monitoreo y resultados en tiempo real. Visualizá datos 
                            y métricas de tu operación al instante.
                        </p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>
    
                    <div class="Proy">
                        <div class="proy-icon"><i class="fas fa-user-minus"></i></div>
                        <h3>Unfollower Assist</h3>
                        <p>
                            Herramienta inteligente para gestionar y analizar seguidores 
                            en redes sociales de forma eficiente.
                        </p>
                        <a href="contacto.php" class="boton">Más información</a>
                    </div>
                </div>
            </div>
            <div class="section-scroll-hint">
                <?php $scrollTarget = '#section-contacto'; include 'inc/templates/boton-scroll.php'; ?>
            </div>
        </section>

        <section id="section-contacto" class="reveal-section page-section">
            <div id="inscripcion" class="contenedor2">
                <div class="texto1 ordenador">
                    <p class="nombre"><span>¿Tenés un proyecto en mente?</span></p>
                    <h3>Hablemos</h3>
                    <p class="nosotros">
                        Si necesitás una aplicación, sitio web o solución digital a medida, 
                        contactanos y contanos tu idea. Nosotros la hacemos realidad.
                    </p>
                    <div class="contacto-botones">
                        <a href="#" class="nuestra-historia btn-whatsapp wa-picker-trigger">Contactar vía Whatsapp</a>
                        <a href="contacto.php" class="nuestra-historia btn-otro">Contactarse por otro medio</a>
                    </div>
                </div>

                <div class="texto1 mobile">
                    <p class="nombre"><span>¿Tenés un proyecto en mente?</span></p>
                    <h3>Hablemos</h3>
                    <p class="nosotros">
                        Si necesitás una aplicación, sitio web o solución digital a medida, 
                        contactanos y contanos tu idea.
                    </p>
                </div>
            </div>

            <div class="btn-nuestra-historia2 contacto-botones-mobile">
                <a href="#" class="nuestra-historia2 btn-whatsapp wa-picker-trigger">Contactar vía Whatsapp</a>
                <a href="contacto.php" class="nuestra-historia2 btn-otro">Contactarse por otro medio</a>
            </div>

            <?php include 'inc/templates/contacto-showcase.php'; ?>

            <div class="section-scroll-hint">
                <?php $scrollTarget = '#section-mapa'; include 'inc/templates/boton-scroll.php'; ?>
            </div>
        </section>

        <section id="section-mapa" class="reveal-section page-section page-section--map">
            <div id="mapa" class="mapa"></div>
        </section>

<?php 
	include 'inc/templates/footer.php';
?>
<script src="js/splash.js"></script>
<script src="js/scroll-sections.js"></script>
<script src="js/contacto-showcase.js"></script>
<script src="js/map.js"></script>
