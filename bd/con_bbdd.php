<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "zamazon";

$conexion = new Mysqli($servername, $username, $password, $dbname)
    or die("Error de conexión");
