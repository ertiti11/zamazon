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
$success_msg;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cantidad = $_POST["cantidad"];
    $idCesta = $_SESSION["IdCesta"];
    $idProducto = $_POST["nombre"];

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
            $success_msg = "Cantidad actualizada correctamente.";
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
    $conexion->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Lista de Productos</title>
    <?php require './css.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <h2 class="text-center my-5">Lista de Productos</h2>
    <div style="display: flex; width:100%; justify-content:center">

        <?php if (isset($success_msg)) echo "<div class='alert alert-success' style='width:33em' role='alert'>" . $success_msg . "</div>" ?>
    </div>
    <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>" ?>
    <section style="display: flex; flex-direction: column; padding: 1rem;">

        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead style="border-bottom: 1px solid;">
                <tr>
                    <th style="padding: 0.5rem;">Product</th>
                    <th style="padding: 0.5rem;">Image</th>
                    <th style="padding: 0.5rem;">Name</th>
                    <th style="padding: 0.5rem;">Description</th>
                    <th style="padding: 0.5rem;">Price</th>
                    <th style="padding: 0.5rem;">Quantity</th>
                    <th style="padding: 0.5rem;">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($productos as $producto) {
                    echo '<tr style="border-bottom: 2px solid #DADADA;">';
                    echo '<td style="padding: 0.5rem;"><img src="' . $producto->imagen . '" alt="' . $producto->nombreProducto . '" width="100" height="100"></td>';
                    echo '<td style="padding: 0.5rem;">' . $producto->nombreProducto . '</td>';
                    echo '<td style="padding: 0.5rem;">' . $producto->precio . '€ </td>';
                    echo '<td style="padding: 0.5rem;">' . $producto->descripcion . '</td>';
                    echo '<td style="padding: 0.5rem;">' . $producto->cantidad . '</td>';
                    echo '<td style="padding: 0.5rem;">';
                    echo '<form method="post" class="d-flex gap-4">';
                    echo "<input type='hidden' name='nombre' value='$producto->id'></input>";
                    echo '<select name="cantidad" style="
                    display: flex;
                    height: 2.5rem;
                    width: 100%;
                    align-items: center;
                    justify-content: space-between;
                    border-radius: 0.375rem;
                    border: 1px solid #e2e8f0;
                    background-color: #f7fafc;
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                    padding-top: 0.5rem;
                    padding-bottom: 0.5rem;
                    font-size: 0.875rem;
                    outline: none;
                    border-color: transparent;
                    box-shadow: 0 0 0 2px #ebf4ff;
                    placeholder: #a0aec0;
                    opacity: 0.5;
                  " id="cantidad">';
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    echo "<input type='submit' class='p-2' style='background-color:black;color:white;border-radius:8px;border:none' value='añadir a la cesta'></input>";
                    echo "</form>";
                    echo "</td>";
                    echo '</tr>';
                }
                echo "</tbody>";
                echo "</table><br>";

                ?>
                </div>

</body>

</html>