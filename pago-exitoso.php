<?php 
     include_once 'inc/funciones/funciones.php'; //llamado a la funcion de seleccionar datos de la base de datos
?>

<?php include_once 'inc/templates/header.php'; ?>

<div class="header section-Proyect-header">
    <?php 
        include 'inc/templates/nav.php';
    ?>
</div>

<section class="section_quienes">

     <div class="exitoso">
       <div>
          <?php $contacto = $resultado -> fetch_assoc() ?> 
          
          

          <h2 class="conferencia">Gracias <span><?php echo $contacto['nombre']; ?></span> por tu Pago</h2>
     
          <hr>
          
               
                         <h3>Tu operación se realizó con exito</h3>
                 
       </div>
       <div>
          <a href="index.php" class="nuestra-historia">Regresar a la web</a>
       </div>
     </div>
       

            
 

</section>    
<?php include_once 'inc/templates/footer.php'; ?>