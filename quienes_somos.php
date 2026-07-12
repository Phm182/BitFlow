<?php 
	include 'inc/templates/header.php';
?>

<div class="header section-Proyect-header">
    <?php 
        include 'inc/templates/nav.php';
    ?>
</div>

<!-- ¿Quiénes somos? -->

<section>
                 <!-- Ordenador -->

            <div class="contenedor2 section_quienes">
                <div class="nosotros-img ordenador hex-gallery-section">
                    <?php include 'inc/templates/hex-gallery.php'; ?>
                </div>

                <div class="texto1 ordenador">
                    <p class="nombre"><span>BitFlow</span></p>
                    <h3>Desarrollo de Software</h3>
                    <p class="nosotros">
                        Somos una empresa de desarrollo de software fundada por dos desarrolladores 
                        apasionados por la tecnología. Creamos productos digitales que impactan 
                        de verdad: desde aplicaciones de gestión hasta herramientas de análisis 
                        y automatización.
                    </p>
                    <a href="#quienes_somos" class="nuestra-historia">Nuestra Historia</a>
                </div>

                <!-- Mobile -->

                <div class="texto1 mobile">
                    <p class="nombre"><span>BitFlow</span></p>
                    <h3>Desarrollo de Software</h3>
                    <p class="nosotros">
                        Somos una empresa de desarrollo de software fundada por dos desarrolladores 
                        apasionados por la tecnología. Creamos productos digitales que impactan 
                        de verdad.
                    </p>
                </div>
                <div class="nosotros-img mobile hex-gallery-section">
                    <?php include 'inc/templates/hex-gallery.php'; ?>
                </div>
            </div>
            <div class="btn-nuestra-historia2">
                <a href="#quienes_somos" class="nuestra-historia2">Nuestra Historia</a>
            </div>

            <hr id="quienes_somos">
            <div class="texto1">
                <h5> Nuestra Historia </h5>
                <div class="texto_historia">
                    <h3> De la idea al código </h3>
                    <p class="nosotros">
                        BitFlow nació de la unión de dos desarrolladores con una visión compartida: 
                        crear software de calidad que resuelva problemas reales. Lo que empezó como 
                        proyectos entre amigos se convirtió en una empresa dedicada al desarrollo 
                        de aplicaciones web, móviles y soluciones a medida.
                    </p>
                    <h3> Nuestros productos </h3>
                    <p class="nosotros">
                        A lo largo del camino desarrollamos Contapp, una plataforma de gestión contable; 
                        Sirius, un sistema de monitoreo y resultados en tiempo real; Unfollower Assist, 
                        una herramienta de análisis para redes sociales; y múltiples sitios web y 
                        aplicaciones para distintos clientes y rubros.
                    </p>
                    <h3> Nuestra filosofía </h3>
                    <p class="nosotros">
                        Creemos que la tecnología debe ser accesible, funcional y bien diseñada. 
                        Cada proyecto que tomamos lo tratamos como propio, priorizando la calidad del 
                        código, la experiencia del usuario y la comunicación transparente con nuestros clientes.
                    </p>
                    <h3> ¿Qué hacemos? </h3>
                    <p class="nosotros">
                        Desarrollo web, aplicaciones móviles, sistemas de gestión, APIs, integraciones, 
                        automatizaciones y consultoría tecnológica. Si tenés una idea, nosotros tenemos 
                        las herramientas y la experiencia para hacerla realidad.
                    </p>
                </div>
                
            </div>
            



        </section>


<?php 
	include 'inc/templates/footer.php';
?>
