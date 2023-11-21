<?php
session_start();
require './bd/con_bbdd.php';

// Inserta un nuevo pedido
$sql = "INSERT INTO Pedidos (usuario, precioTotal) VALUES (?, ?);";
$stmt = $conexion->prepare($sql);
$usuario = $_SESSION['usuario']; // Asume que el usuario logueado se almacena en la sesión
$precioTotal = $_SESSION['precioTotal'];
$stmt->bind_param("sd", $usuario, $precioTotal);
$stmt->execute();
$idPedido = $stmt->insert_id;


$contador = 1;
// Inserta las líneas del pedido
$idCesta = $_SESSION["IdCesta"];
$sql = "SELECT * FROM ProductosCestas WHERE IdCesta = '$idCesta'";

$resultado = $conexion->query($sql);
if ($resultado === false) {
    echo "Error al insertar las líneas del pedido: " . $conexion->error;
}

while ($fila = $resultado->fetch_assoc()) {
    $idProducto = $fila["IdProducto"];
    $cantidad = $fila["cantidad"];

    $comprobarUnitario = "SELECT precio FROM Productos WHERE idProducto = '$idProducto'";
    $resultadoPrecioUni = $conexion->query($comprobarUnitario);
    $filaPrecioUni = $resultadoPrecioUni->fetch_assoc();
    $precio = $filaPrecioUni["precio"];

    $sql = "INSERT INTO LineasPedidos (lineaPedido, idProducto, idPedido, precioUnitario, cantidad) 
    VALUES ('$contador', '$idProducto', '$idPedido', '$precio','$cantidad');";

    $conexion->query($sql);
    $contador++;
}

$sqlVaciarCesta = "DELETE FROM productoscestas WHERE IdCesta = ?";
$stmtVaciarCesta = $conexion->prepare($sqlVaciarCesta);
$stmtVaciarCesta->bind_param("i", $idCesta);
$stmtVaciarCesta->execute();

if ($stmtVaciarCesta->affected_rows > 0) {
    $success = "Cesta vaciada correctamente.";
} else {
    $error = "Error al vaciar la cesta: " . $stmtVaciarCesta->error;
}

// Vacía la cesta
// $idCesta = $_SESSION["IdCesta"];
// $sqlVaciarCesta = "DELETE FROM productoscestas WHERE IdCesta = ?";
// $stmtVaciarCesta = $conexion->prepare($sqlVaciarCesta);
// $stmtVaciarCesta->bind_param("i", $idCesta);
// $stmtVaciarCesta->execute();

// if ($stmtVaciarCesta->affected_rows > 0) {
//     $success = "Cesta vaciada correctamente.";
// } else {
//     $error = "Error al vaciar la cesta: " . $stmtVaciarCesta->error;
// }

$stmt->close();
header("Location: cesta.php"); // Redirige al usuario a la página de la cesta
