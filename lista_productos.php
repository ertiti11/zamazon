<!DOCTYPE html>
<html>

<head>
    <title>Lista de Productos</title>
    <?php require './css.php' ?>
</head>

<body>
    <?php require './components/navbar.php' ?>
    <h2 class="text-center my-5">Lista de Productos</h2>

    <div style="display: flex; justify-content:center; align-items:center">
        <?php
        require './con_bbdd.php';

        
        // Consulta para obtener los nombres de los productos y sus imágenes
        $sql = "SELECT * FROM producto";
        $result = $conexion->query($sql);
        echo "<table class='table table-borderless w-50'>";
        echo "<thead class='table-primary'>";
        echo "<tr>";
        echo "<th scope='col' >imagen</th>";
        echo "<th scope='col' >id</th>";
        echo "<th scope='col' >nombre</th>";
        echo "<th scope='col' >precio</th>";
        echo "<th scope='col' >descripcion</th>";
        echo "<th scope='col' >cantidad</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<ltr>';
                echo '<td><img src="' . $row["imagen"] . '" alt="' . $row["nombre_producto"] . '" width="100" height="100"></td>';
                echo '<td>' . $row["id"] . '</td>';
                echo '<td>' . $row["nombre_producto"] . '</td>';
                echo '<td>' . $row["precio"] . '€ </td>';
                echo '<td>' . $row["descripcion"] . '</td>';
                echo '<td>' . $row["cantidad"] . '</td>';
                echo '</tr>';
            }
        } else {
            echo "<li>No hay productos disponibles</li>";
        }

        $conexion->close();
        ?>
    </div>
</body>

</html>