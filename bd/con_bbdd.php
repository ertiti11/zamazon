<?php
        $servername = "localhost";
        $username = "root";
        $password = "123456789";
        $dbname = "zamazon";

    $conexion = new Mysqli($servername, $username, $password, $dbname)
        or die("Error de conexión");
?>