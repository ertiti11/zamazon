<?php
session_start();
require './bd/con_bbdd.php';

if (isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    $stmt = $conexion->prepare("SELECT IdCesta FROM cestas WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "La cesta no existe";
    } else {
        while ($fila = $result->fetch_assoc()) {
            $idCesta = $fila["IdCesta"];
        }
    }
    $stmt->close();

    // Consultar productos en la cesta
    $sqlCesta = "SELECT pc.cantidad,p.nombreProducto,p.descripcion,p.precio,p.imagen FROM productoscestas pc
    JOIN productos p ON pc.IdProducto = p.IdProducto
    WHERE pc.IdCesta = ?";
    $stmtCesta = $conexion->prepare($sqlCesta);
    $stmtCesta->bind_param("i", $idCesta);
    $stmtCesta->execute();
    $resultCesta = $stmtCesta->get_result();
} else {
    header("location: /login.php");
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCesta = $_SESSION["IdCesta"];
    $sqlVaciarCesta = "DELETE FROM productoscestas WHERE IdCesta = ?";
    $stmtVaciarCesta = $conexion->prepare($sqlVaciarCesta);
    $stmtVaciarCesta->bind_param("i", $idCesta);
    $stmtVaciarCesta->execute();

    if ($stmtVaciarCesta->affected_rows > 0) {
        $success = "Cesta vaciada correctamente.";
    } else {
        $error = "Error al vaciar la cesta: " . $stmtVaciarCesta->error;
    }

    $stmtVaciarCesta->close();
    header("location: cesta.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cesta</title>
    <?php require './css.php'; ?>
</head>

<body>
    <?php require './components/navbar.php'; ?>
    <h2 class="text-center my-5">Cesta</h2>
    <div style="display: flex; justify-content:center; align-items:center">
        <table class="table" style="width:33em">
            <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Nombre Producto</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultCesta->fetch_assoc()) : ?>
                    <tr>
                        <td><img src="<?php echo $row['imagen']; ?>" alt="Imagen del producto" width="100" height="100"></td>
                        <td><?php echo $row['nombreProducto']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- precio total de la cesta -->

    </div>
    <?php


    $sqlPrecioTotal = "SELECT SUM(p.precio * pc.cantidad) AS precioTotal FROM productoscestas pc
JOIN productos p ON pc.IdProducto = p.IdProducto
WHERE pc.IdCesta = ?";
    $stmtPrecioTotal = $conexion->prepare($sqlPrecioTotal);
    $stmtPrecioTotal->bind_param("i", $idCesta);
    $stmtPrecioTotal->execute();
    $resultPrecioTotal = $stmtPrecioTotal->get_result();
    $precioTotal = $resultPrecioTotal->fetch_assoc();
    $_SESSION["precioTotal"] = $precioTotal["precioTotal"];
    echo "<h3 style='text-align:center'>Precio total: " . number_format($precioTotal["precioTotal"], 2) . "€</h3>";


    ?>
</body>
<form method="post" style="width: 100%; display:flex; align-items: center; justify-content:center">

    <input type="submit" id="vaciarCesta" class="btn btn-danger" value="borrar cesta" />
</form>
<form method="POST" action="finalizar_pedido.php">
    <input type="submit" value="Finalizar pedido">
</form>

</html>


<?php
$stmtCesta->close();
$conexion->close();
?>