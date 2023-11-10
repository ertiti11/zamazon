<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
    <?php require './css.php' ?>
</head>
<body>
    <h2>Lista de Productos</h2>
    <?php 
        session_start();
    
        //comprobar si ha iniciado sesion y si no pues le pondremos la sesion con valor a invitado y un boton de login y si ha iniciado le pondremos un boton de logout
        if(isset($_SESSION["usuario"])){
            echo '<a href="./logout.php">Logout</a>';
            echo '<a href="./producto.php">Añadir producto</a>';
        }else{
            echo '<a href="./login.php">Login</a>';
        }
    ?>
    <ul>
        <?php
        require './con_bbdd.php';

        // Consulta para obtener los nombres de los productos y sus imágenes
        $sql = "SELECT * FROM producto";
        $result = $conexion->query($sql);
        echo "<table class='table table-borderless'>";
        echo "<thead class='table-danger'>";
        echo "<tr>";
        echo "<th scope='col' >id</th>";
        echo "<th scope='col' >nombre</th>";
        echo "<th scope='col' >precio</th>";
        echo "<th scope='col' >descripcion</th>";
        echo "<th scope='col' >cantidad</th>";
        echo "<th scope='col' >imagen</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<ltr>';
                echo '<td>' . $row["id"] . '</td>';
                echo '<td>' . $row["nombre_producto"] . '</td>';
                echo '<td>' . $row["precio"] . '€ </td>';
                echo '<td>' . $row["descripcion"] . '</td>';
                echo '<td>' . $row["cantidad"] . '</td>';
                echo '<td><img src="' . $row["imagen"] . '" alt="' . $row["nombre_producto"] . '" width="100" height="100"></td>';
                echo '</tr>';
            }
        } else {
            echo "<li>No hay productos disponibles</li>";
        }

        $conexion->close();
        ?>
    </ul>
</body>
</html>
