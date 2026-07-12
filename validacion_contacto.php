<?php if (isset($_POST['enviar'])):      //isset va a fijarse que la variable exista, si existe, continua 
        
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
        $celular = filter_var($_POST['celular'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $consulta = filter_var($_POST['consulta'], FILTER_SANITIZE_STRING);
        $metodo = filter_var($_POST['metodo'], FILTER_SANITIZE_STRING);    


        //Prepared Statements -- agregar información a la base de datos

        try {
            require_once('inc/funciones/bd.php');
            $stmt = $conn->prepare("INSERT INTO `contacto`(nombre, celular, email, consulta, metodo) VALUES(?,?,?,?,?) "); // ? cantidad de variable que agregamos este caso 8
            $stmt->bind_param("sssss", $nombre, $celular, $email, $consulta, $metodo);
            $stmt->execute();
            $stmt->close();
            $conn->close(); 
            header('Location: validacion_contacto.php?exitoso=1'); //redirecciona la pagina al llenar la base de datos
       } catch (\Exception $e) {                                // se pone toda la funcion al principio con redireccionamiento para que al recargar, no se recomplete el registro
            echo $e -> getMessage();
       }

        ?>  

        <?php endif; ?>

        

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
          
          

          <h2 class="conferencia">Gracias <span><?php echo $contacto['nombre']; ?></span> por tu consulta</h2>
     
          <hr>
          
               <?php if(isset($_GET['exitoso'])): 
                    if($_GET['exitoso'] == "1"): ?>
                         <h3>Tu consulta se realizó con exito, en breve recibirás tu respuesta por <?php echo $contacto['metodo']; ?>.</h3>
                    <?php    endif;
                    endif; ?>
       </div>
       <div>
          <a href="index.php" class="nuestra-historia">Regresar a la web</a>
       </div>
     </div>
       

            
 

</section>    
<?php include_once 'inc/templates/footer.php'; ?>

       