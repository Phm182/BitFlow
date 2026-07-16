<?php 
    $bodyClass = 'page-inicio';
	include 'inc/templates/header.php';
    $splashMode = 'continue';
    $showSplashButton = false;
    $showScrollButton = true;
    $scrollTarget = '#section-hacemos';
?>

                <div class="header header--delayed">
                    <?php include 'inc/templates/nav.php'; ?>
                </div>

        <section id="section-hero" class="page-section page-section--hero">
            <div class="contenedorHeader contenedorHeader--inicio">
                <?php include 'inc/templates/splash-hero.php'; ?>
            </div>
        </section>

        <section id="section-hacemos" class="reveal-section page-section">
            <div class="hacemos-wrap">
                <p class="nombre"><span>¿Qué hacemos?</span></p>

                <div class="hacemos-tabs" role="tablist" aria-label="Servicios BitFlow">
                    <button type="button" class="hacemos-tab is-active" role="tab" id="tab-software" aria-selected="true" aria-controls="panel-software" data-tab="software">
                        <i class="fas fa-code" aria-hidden="true"></i>
                        <span class="hacemos-tab__label hacemos-tab__label--full">Desarrollo de Software</span>
                        <span class="hacemos-tab__label hacemos-tab__label--short">Software</span>
                    </button>
                    <button type="button" class="hacemos-tab" role="tab" id="tab-hardware" aria-selected="false" aria-controls="panel-hardware" data-tab="hardware" tabindex="-1">
                        <i class="fas fa-laptop" aria-hidden="true"></i>
                        <span class="hacemos-tab__label hacemos-tab__label--full">Armado PC/notebooks</span>
                        <span class="hacemos-tab__label hacemos-tab__label--short">Armado PC</span>
                    </button>
                    <button type="button" class="hacemos-tab" role="tab" id="tab-soporte" aria-selected="false" aria-controls="panel-soporte" data-tab="soporte" tabindex="-1">
                        <i class="fas fa-headset" aria-hidden="true"></i>
                        <span class="hacemos-tab__label hacemos-tab__label--full">Soporte y mantenimiento</span>
                        <span class="hacemos-tab__label hacemos-tab__label--short">Soporte</span>
                    </button>
                </div>

                <div class="hacemos-panels">
                    <article class="hacemos-panel is-active" id="panel-software" role="tabpanel" aria-labelledby="tab-software">
                        <div class="hacemos-panel__icon"><i class="fas fa-rocket" aria-hidden="true"></i></div>
                        <h3>Software que acelera tu negocio</h3>
                        <p class="hacemos-panel__lead">
                            Convertimos tu idea en un producto digital listo para competir:
                            aplicaciones web, móviles y sistemas a medida que automatizan procesos,
                            reducen costos y te dan ventaja real frente al mercado.
                        </p>
                        <ul class="hacemos-panel__points">
                            <li><strong>Resultado medible:</strong> soluciones pensadas para vender más, operar mejor y escalar sin fricción.</li>
                            <li><strong>A medida, sin plantillas genéricas:</strong> arquitectura sólida, interfaces claras y foco en lo que tu cliente necesita.</li>
                            <li><strong>De la idea al lanzamiento:</strong> acompañamos diseño, desarrollo y puesta en marcha para que salgas al mercado con impacto.</li>
                        </ul>
                        <a href="contacto.php" class="hacemos-panel__cta">Quiero mi solución digital</a>
                    </article>

                    <article class="hacemos-panel" id="panel-hardware" role="tabpanel" aria-labelledby="tab-hardware" hidden>
                        <div class="hacemos-panel__icon"><i class="fas fa-microchip" aria-hidden="true"></i></div>
                        <h3>PCs y notebooks armadas para rendir</h3>
                        <p class="hacemos-panel__lead">
                            Armamos equipos a medida según cómo trabajás: oficina, diseño, desarrollo o uso intensivo.
                            Elegimos componentes con criterio técnico para que pagues rendimiento real, no marketing.
                        </p>
                        <ul class="hacemos-panel__points">
                            <li><strong>Configuración inteligente:</strong> balance perfecto entre potencia, silenciosidad y presupuesto.</li>
                            <li><strong>Listo para usar:</strong> armado, optimización e instalación para que arranques productivo desde el día uno.</li>
                            <li><strong>Confianza BitFlow:</strong> hardware seleccionado y probado para durar, con asesoramiento claro y sin vueltas.</li>
                        </ul>
                        <a href="contacto.php" class="hacemos-panel__cta">Armá tu equipo con nosotros</a>
                    </article>

                    <article class="hacemos-panel" id="panel-soporte" role="tabpanel" aria-labelledby="tab-soporte" hidden>
                        <div class="hacemos-panel__icon"><i class="fas fa-shield-alt" aria-hidden="true"></i></div>
                        <h3>Soporte integral: software + hardware</h3>
                        <p class="hacemos-panel__lead">
                            Mantenemos tu operación estable. Cuidamos tus aplicaciones, sitios y sistemas,
                            y también tus PCs y notebooks, para que nada frene tu día a día ni tus ventas.
                        </p>
                        <ul class="hacemos-panel__points">
                            <li><strong>Software siempre al día:</strong> correcciones, mejoras, seguridad y monitoreo para que tu producto no se detenga.</li>
                            <li><strong>Hardware sin sorpresas:</strong> diagnóstico, mantenimiento preventivo y reparación de equipos de trabajo.</li>
                            <li><strong>Respuesta rápida:</strong> soporte cercano cuando lo necesitás, para minimizar caídas y maximizar continuidad.</li>
                        </ul>
                        <a href="contacto.php" class="hacemos-panel__cta">Quiero soporte continuo</a>
                    </article>
                </div>
            </div>

            <div class="section-scroll-hint section-scroll-hint--hacemos">
                <?php $scrollTarget = '#section-quienes'; include 'inc/templates/boton-scroll.php'; ?>
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
                    <p class="nosotros">
                        Somos <strong class="brand-inline">BitFlow</strong>, un equipo fundado por dos desarrolladores
                        con años de experiencia en tecnología. Creamos aplicaciones web, móviles y soluciones
                        digitales que resuelven problemas reales para empresas y emprendedores.
                    </p>
                </div>

                <div class="hex-gallery-center">
                    <?php include 'inc/templates/hex-gallery.php'; ?>
                </div>

                <div class="btn-nuestra-historia2 mobile-only-historia">
                    <a href="quienes_somos.php" class="nuestra-historia2">Nuestra Historia</a>
                </div>

                <div class="section-scroll-hint section-scroll-hint--quienes">
                    <?php $scrollTarget = '#section-proyectos'; include 'inc/templates/boton-scroll.php'; ?>
                </div>
            </div>
        </section>
        
        <section id="section-proyectos" class="reveal-section page-section">
            <div id="Proyect" class="Proyect">
                <p class="nombre glitch" data-text="Nuestros Proyectos"><span>Nuestros Proyectos</span></p>
                <div class="proyectos-carousel">
                    <div class="contenedor-Proyect" id="proyectos-track">
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
                    <div class="proyectos-carousel__controls" aria-hidden="false">
                        <button type="button" class="proyectos-carousel__arrow proyectos-carousel__arrow--prev" aria-label="Proyecto anterior">‹</button>
                        <div class="proyectos-dots" id="proyectos-dots" role="tablist" aria-label="Proyectos"></div>
                        <button type="button" class="proyectos-carousel__arrow proyectos-carousel__arrow--next" aria-label="Proyecto siguiente">›</button>
                    </div>
                    <p class="proyectos-swipe-hint">Deslizá para ver más proyectos</p>
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
                    <p class="nosotros">
                        Si necesitás una aplicación, sitio web o solución digital a medida, 
                        contactanos y contanos tu idea.
                    </p>
                </div>
            </div>

            <div class="btn-nuestra-historia2 contacto-botones-mobile">
                <a href="#" class="nuestra-historia2 btn-whatsapp wa-picker-trigger"><i class="fa fa-whatsapp"></i> WhatsApp</a>
                <a href="contacto.php" class="nuestra-historia2 btn-otro">Otro medio</a>
            </div>

            <?php include 'inc/templates/contacto-showcase.php'; ?>

            <div class="section-scroll-hint">
                <?php $scrollTarget = '#section-mapa'; include 'inc/templates/boton-scroll.php'; ?>
            </div>
        </section>

        <section id="section-mapa" class="reveal-section page-section page-section--map">
            <div class="geo-showcase" id="geo-showcase">
                <canvas class="geo-showcase__globe" id="geo-globe"></canvas>
                <div class="geo-showcase__glow geo-showcase__glow--left"></div>
                <div class="geo-showcase__glow geo-showcase__glow--right"></div>

                <div class="geo-info">
                    <p class="geo-info__eyebrow">Nuestra ubicación</p>
                    <h3 class="geo-info__title">BitFlow · <span>Buenos Aires</span></h3>
                    <p class="geo-info__place">Villa Lugano, Ciudad de Buenos Aires — Argentina</p>
                    <p class="geo-info__coords"><i class="fas fa-map-marker-alt"></i> 34.68° S · 58.49° O</p>
                    <a class="geo-info__btn" href="https://www.google.com/maps?q=-34.679276,-58.489494" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-directions"></i> Cómo llegar
                    </a>
                    <p class="geo-info__hint">Arrastrá el planeta para girarlo</p>
                </div>
            </div>
        </section>

<?php
    $extraScripts = array(
        'js/splash.js',
        'js/scroll-sections.js',
        'js/hacemos-tabs.js',
        'js/proyectos-carousel.js',
        'js/contacto-showcase.js',
        'js/map.js',
    );
	include 'inc/templates/footer.php';
?>
