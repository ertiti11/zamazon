<?php
        $servername = "localhost";
        $username = "root";
        $password = "123456789";
        $dbname = "amazon";

    $conexion = new Mysqli($servername, $username, $password, $dbname)
        or die("Error de conexión");
?>