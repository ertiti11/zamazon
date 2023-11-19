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
                    $sql = "SELECT IdProducto FROM productos WHERE nombreProducto = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("s", $producto->nombreProducto); // "s" indica que el parámetro es una cadena
                    $stmt->execute();
                    $stmt->bind_result($idProducto);
                    $stmt->fetch();
                    echo '<tr>';
                    echo '<td><img src="' . $producto->imagen . '" alt="' . $producto->nombreProducto . '" width="100" height="100"></td>';
                    echo '<td>' . $producto->nombreProducto . '</td>';
                    echo '<td>' . $producto->precio . '€ </td>';
                    echo '<td>' . $producto->descripcion . '</td>';
                    echo '<td>' . $producto->cantidad . '</td>';
                    echo "<td>";
                    echo '<form method="post">';
                    echo "<input type='hidden' name='nombre' value='$idProducto'></input>";
                    echo '<select name="cantidad" id="cantidad">';
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    echo "<input type='submit' value='añadir a la cesta'></input>";
                    echo "</form>"; // Cierra el formulario después de cada iteración
                    echo "</td>";
                    echo '</tr>';
                    $stmt->close();
                }

                echo "</tbody>";
                echo "</table><br>";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $cantidad = $_POST["cantidad"];
                    $idCesta = $_SESSION["IdCesta"];
                    $idProducto = $_POST["nombre"];
                    echo "id:" . $idProducto . " cesta:" . $idCesta . " cantidad." . $cantidad;

                    // Utiliza una consulta preparada para la inserción
                    $sql = "INSERT INTO productoscestas (IdProducto, IdCesta, cantidad) VALUES (?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("iii", $idProducto, $idCesta, $cantidad);
                    $stmt->execute();

                    // Verifica si la inserción fue exitosa
                    if ($stmt->affected_rows > 0) {
                        echo "Producto añadido a la cesta correctamente.";
                    } else {
                        echo "Error al añadir el producto a la cesta: " . $stmt->error;
                    }

                    // Cierra la consulta preparada
                    $stmt->close();
                }
                ?>
    </div>
</body>

</html>