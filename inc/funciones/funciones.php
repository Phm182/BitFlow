<?php //crea la variable para tomar el contacto desde la base de datos

      try {
         require_once('inc/funciones/bd.php');
         $sql = " SELECT id, nombre, celular, email, consulta, metodo FROM contacto ";
         $sql .= " ORDER BY id desc "; // Ordenar, se puede elegir cualquier elemento
         $resultado = $conn -> query($sql);
    } catch (\Exception $e) {
         echo $e -> getMessage();
    }
    ?>