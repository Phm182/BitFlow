<?php

// Credenciales de la base de datos - XAMPP
// Si usás MAMP, cambiá la contraseña a 'root' y el puerto si corresponde.

    $conn = new mysqli('localhost', 'root', '', 'bitflow');

    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');

?>
