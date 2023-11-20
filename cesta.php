<?php
session_start();
require './bd/con_bbdd.php';


if (isset($_SESSION["usuario"])) {
    $usuario = $_SESSION["usuario"];
    $stmt = $conexion->prepare("SELECT IdCestas FROM cestas WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "La cesta no existe";
    } else {
        while ($fila = $result->fetch_assoc()) {
            $idCesta = $fila["IdCestas"];
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

    if ($resultCesta->num_rows > 0) {
        while ($rowCesta = $resultCesta->fetch_assoc()) {
            echo "Producto ID: " . $rowCesta["IdProducto"] . "<br>";
            echo "Nombre: " . $rowCesta["nombreProducto"] . "<br>";
            echo "Precio: " . $rowCesta["precio"] . "€<br>";
            echo "Descripción: " . $rowCesta["descripcion"] . "<br>";
            echo "cantidad: " . $rowCesta["cantidad"] . "<br>";
        }}
    $stmtCesta->close();
}
?>