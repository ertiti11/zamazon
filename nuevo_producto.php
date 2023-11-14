<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ./login.php");
    exit;
}

$error;
$success = '';

require './bd/con_bbdd.php';
require 'clases/producto.php';
require './utils/validacion.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorNombre="";
    $errorPrecio="";
    $errorDescripcion="";
    $errorCantidad="";
    $producto = new Producto($conexion);

    $nombre = sanitizeInput($_POST["nombre"]);
    $precio = sanitizeInput($_POST["precio"]);
    $descripcion = sanitizeInput($_POST["descripcion"]);
    $cantidad = sanitizeInput($_POST["cantidad"]);
    $imagen = $_FILES["imagen"];

    $errorNombre = validarCadena($nombre);
    $errorPrecio = validarNumeroDecimal($precio);
    $errorDescripcion = validarDescripcion($descripcion);
    $errorCantidad = validarCantidad($cantidad);

    if ($errorNombre == "" && $errorPrecio == "" && $errorDescripcion == "" && $errorCantidad == "") {
        $producto->crearProducto($nombre, $precio, $descripcion, $cantidad, $imagen);
        if ($producto->guardarProducto()) {
            $success = "Producto agregado con éxito.";
        } else {
            $error = "Error al agregar el producto.";
        }
    }

}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulario de Producto</title>
    <?php require './css.php'; ?>
    <link rel="stylesheet" href="./signin.css">
</head>

<body>
    <header>
        <?php require './components/navbar.php'; ?>
    </header>

    <main class="form-signin">
        <form class="" method="post" enctype="multipart/form-data">
            <h1 class="h3 mb-3 fw-normal">Nuevo producto</h1>
            <?php if (!empty($errorNombre)) echo "<div class='alert alert-danger' role='alert'>$errorNombre</div>"; ?>
            <?php if (!empty($errorPrecio)) echo "<div class='alert alert-danger' role='alert'>$errorPrecio</div>"; ?>
            <?php if (!empty($errorDescripcion)) echo "<div class='alert alert-danger' role='alert'>$errorDescripcion</div>"; ?>
            <?php if (!empty($errorCantidad)) echo "<div class='alert alert-danger' role='alert'>$errorCantidad</div>"; ?>
            <?php if (!empty($success)) echo "<div class='alert alert-success' role='alert'>$success</div>"; ?>

            <div class="form-floating">
                <input type="text" name="nombre" class="form-control" id="floatingInput" placeholder="Nombre de producto" required>
                <label for="floatingInput">Nombre de producto</label>
            </div>

            <div class="form-floating">
                <input type="text" name="precio" class="form-control" id="floatingPassword" placeholder="Precio" required>
                <label for="floatingPassword">Precio</label>
            </div>

            <div class="form-floating">
                <input type="text" name="descripcion" class="form-control" id="floatingPassword" placeholder="Descripcion" required>
                <label for="floatingPassword">Descripción</label>
            </div>

            <div class="form-floating">
                <input type="number" name="cantidad" class="form-control" id="floatingPassword" placeholder="Cantidad" required>
                <label for="floatingPassword">Cantidad</label>
            </div>

            <div class="form-floating">
                <input type="file" name="imagen" class="form-control" id="floatingPassword" required>
                <label for="floatingPassword">Imagen</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Añadir producto</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
        </form>
    </main>
</body>

</html>