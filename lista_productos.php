<!DOCTYPE html>
<html>

<head>
    <title>Lista de Productos</title>
    <?php require './css.php'; ?>
</head>

<body>

    <?php
    session_start();
    require './components/navbar.php';
    require './bd/con_bbdd.php';
    require './clases/producto.php';

    // Obtener la lista de productos usando la clase Producto
    $productos = array(); // Arreglo para almacenar los objetos Producto

    $sql = "SELECT * FROM PRODUCTOS";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Crear un objeto Producto por cada registro
            $producto = new Producto($conexion);
            $producto->crearProducto(
                $row["nombreProducto"],
                $row["precio"],
                $row["descripcion"],
                $row["cantidad"],
                $row["imagen"]
            );

            $productos[] = $producto; // Agregar el objeto Producto al arreglo
        }
    }
    $conexion->close();
    ?>

    <h2 class="text-center my-5">Lista de Productos</h2>
    <?php echo $_SESSION["rol"]; ?>
    <div style="display: flex; justify-content:center; align-items:center">
        <?php
        echo "<table class='table table-borderless w-50'>";
        echo "<thead class='table-primary'>";
        echo "<tr>";
        echo "<th scope='col' >imagen</th>";
        echo "<th scope='col' >nombre</th>";
        echo "<th scope='col' >precio</th>";
        echo "<th scope='col' >descripcion</th>";
        echo "<th scope='col' >cantidad</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($productos as $producto) {
            echo '<tr>';
            echo '<td><img src="' . $producto->imagen . '" alt="' . $producto->nombreProducto. '" width="100" height="100"></td>';
            echo '<td>' . $producto->nombreProducto. '</td>';
            echo '<td>' . $producto->precio . '€ </td>';
            echo '<td>' . $producto->descripcion . '</td>';
            echo '<td>' . $producto->cantidad . '</td>';
            echo '</tr>';
        }

        echo "</tbody>";
        echo "</table>";
        ?>
    </div>
</body>

</html>