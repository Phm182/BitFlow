<?php 
	include 'inc/templates/header.php';
?>

<div class="header section-Proyect-header">
    <?php 
        include 'inc/templates/nav.php';
    ?>
</div>

<!-- Contacto -->

<section>
            <div class="section_galeria">
                <div class="texto1">
                    <p class="nombre"><span>BitFlow</span></p>
                    <h3>Contactanos</h3>
                    <p class="nosotros" style="text-align: center; margin-bottom: 2rem;">
                        ¿Tenés un proyecto en mente? Escribinos y contanos tu idea.
                    </p>
                </div>

                <hr id="quienes_somos">

                <?php 
                    include 'inc/templates/Contacto.php';
                ?>

            </div>
        </section>


<?php 
	include 'inc/templates/footer.php';
?>
