<?php

//log out y destruir la sesion
session_start();
session_destroy();
header('location: login.php');
?>