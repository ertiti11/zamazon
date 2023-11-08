<?php
    $_servidor = 'localhost';
    $_usuario = 'root';
    $_contrasena = '123456789';
    $_base_de_datos = 'videojuegos';

    $conexion = new Mysqli($_servidor, 
                            $_usuario, 
                            $_contrasena, 
                            $_base_de_datos)
        or die("Error de conexión");
?>