<?php
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "amazon";

    $conexion = new Mysqli($servername, $username, $password, $dbname)
        or die("Error de conexión");
?>