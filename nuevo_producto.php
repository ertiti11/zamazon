<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ./login.php");
    exit;
}

$error = '';
$succesfull = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require './bd/con_bbdd.php'; 
    require 'clases/producto.php'; 
    $producto = new Producto($conexion);
    $nombre = sanitizeInput($_POST["nombre"]);
    $precio = sanitizeInput($_POST["precio"]);
    $descripcion = sanitizeInput($_POST["descripcion"]);
    $cantidad = sanitizeInput($_POST["cantidad"]);
    $imagen = $_FILES["imagen"];

    $producto->crearProducto($nombre, $precio, $descripcion, $cantidad, $imagen);

    if ($producto->guardarProducto()) {
        $succesfull = "Producto agregado con éxito.";
    } else {
        $error = "Error al agregar el producto.";
    }
}

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
            <?php if (!empty($error)) echo "<div class='alert alert-danger' role='alert'>$error</div>"; ?>
            <?php if (!empty($succesfull)) echo "<div class='alert alert-success' role='alert'>$succesfull</div>"; ?>
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