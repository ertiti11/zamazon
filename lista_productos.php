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

    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $stmt = $conexion->prepare("SELECT IdCestas FROM cestas WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "La cesta no existe";
        } else {
            while ($fila = $result->fetch_assoc()) {
                $_SESSION["IdCesta"] = $fila["IdCestas"];
            }
        }
        $stmt->close();
    }

    // Obtener la lista de productos usando la clase Producto
    $productos = array(); // Arreglo para almacenar los objetos Producto

    $sql = "SELECT * FROM PRODUCTOS";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Crear un objeto Producto por cada registro
            $producto = new Producto($conexion);
            $producto->crearProducto(
                $row["IdProducto"],
                $row["nombreProducto"],
                $row["precio"],
                $row["descripcion"],
                $row["cantidad"],
                $row["imagen"]
            );

            $productos[] = $producto; // Agregar el objeto Producto al arreglo
        }
    }

    $result->close(); // Cerrar el resultado después de obtener la lista de productos
    $conexion->close();
    ?>

    <h2 class="text-center my-5">Lista de Productos</h2>
    <div style="display: flex; justify-content:center; align-items:center">

        <table class='table table-borderless w-50'>
            <thead class='table-primary'>
                <tr>
                    <th scope='col'>imagen</th>
                    <th scope='col'>nombre</th>
                    <th scope='col'>precio</th>
                    <th scope='col'>descripcion</th>
                    <th scope='col'>cantidad</th>
                    <th scope='col'></th>
                </tr>
            </thead>
            <tbody>
                <?php
                require './bd/con_bbdd.php';
                foreach ($productos as $producto) {
                    echo '<tr>';
                    echo '<td><img src="' . $producto->imagen . '" alt="' . $producto->nombreProducto . '" width="100" height="100"></td>';
                    echo '<td>' . $producto->nombreProducto . '</td>';
                    echo '<td>' . $producto->precio . '€ </td>';
                    echo '<td>' . $producto->descripcion . '</td>';
                    echo '<td>' . $producto->cantidad . '</td>';
                    echo "<td>";
                    echo '<form method="post">';
                    echo "<input type='hidden' name='nombre' value='$producto->id'></input>";
                    echo '<select name="cantidad" id="cantidad">';
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    echo "<input type='submit' value='añadir a la cesta'></input>";
                    echo "</form>";
                    echo "</td>";
                    echo '</tr>';
                }
                echo "</tbody>";
                echo "</table><br>";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $cantidad = $_POST["cantidad"];
                    $idCesta = $_SESSION["IdCesta"];
                    $idProducto = $_POST["nombre"];
                    echo "id:" . $idProducto . " cesta:" . $idCesta . " cantidad." . $cantidad;

                    // Verificar si ya existe una entrada para el producto en la cesta
                    $sql_check = "SELECT IdProducto FROM productoscestas WHERE IdProducto = ? AND IdCesta = ?";
                    $stmt_check = $conexion->prepare($sql_check);
                    $stmt_check->bind_param("ii", $idProducto, $idCesta);
                    $stmt_check->execute();
                    $stmt_check->store_result();

                    if ($stmt_check->num_rows > 0) {
                        // Si ya existe, realizar una actualización en lugar de una inserción
                        $sql_update = "UPDATE productoscestas SET cantidad = cantidad + ? WHERE IdProducto = ? AND IdCesta = ?";
                        $stmt_update = $conexion->prepare($sql_update);
                        $stmt_update->bind_param("iii", $cantidad, $idProducto, $idCesta);
                        $stmt_update->execute();

                        if ($stmt_update->affected_rows > 0) {
                            echo "Cantidad actualizada correctamente.";
                        } else {
                            echo "Error al actualizar la cantidad: " . $stmt_update->error;
                        }

                        $stmt_update->close();
                    } else {
                        // Si no existe, realizar una inserción normal
                        $sql_insert = "INSERT INTO productoscestas (IdProducto, IdCesta, cantidad) VALUES (?, ?, ?)";
                        $stmt_insert = $conexion->prepare($sql_insert);
                        $stmt_insert->bind_param("iii", $idProducto, $idCesta, $cantidad);
                        $stmt_insert->execute();

                        if ($stmt_insert->affected_rows > 0) {
                            echo "Producto añadido a la cesta correctamente.";
                        } else {
                            echo "Error al añadir el producto a la cesta: " . $stmt_insert->error;
                        }

                        $stmt_insert->close();
                    }

                    $stmt_check->close();
                }
                ?>
    </div>
</body>

</html>