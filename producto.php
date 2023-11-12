<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ./login.php");
    exit;
}

$error = '';
$succesfull = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = sanitizeInput($_POST["nombre"]);
    $precio = sanitizeInput($_POST["precio"]);
    $descripcion = sanitizeInput($_POST["descripcion"]);
    $cantidad = sanitizeInput($_POST["cantidad"]);

    $imagen = $_FILES["imagen"];
    $imagen_nombre = $imagen["name"];
    $imagen_temp = $imagen["tmp_name"];
    $imagen_guardada = "./images/" . $imagen_nombre;

    $file_extension = pathinfo($imagen_nombre, PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $file_size = $imagen["size"];
    $max_file_size = 5 * 1024 * 1024; // Tamaño máximo permitido (5 MB)

    if ($file_size > $max_file_size) {
        $error = "El tamaño del archivo excede el límite permitido (5 MB).";
    } elseif (!in_array(strtolower($file_extension), $allowed_extensions)) {
        $error = "Solo se permiten archivos JPG, JPEG y PNG.";
    } else if (move_uploaded_file($imagen_temp, $imagen_guardada)) {
        require './con_bbdd.php';

        $sql = "INSERT INTO producto (nombre_producto, precio, descripcion, cantidad, imagen) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sdsss", $nombre, $precio, $descripcion, $cantidad, $imagen_guardada);

        if ($stmt->execute()) {
            $succesfull= "Producto agregado con éxito.";
        } else {
            $error = "Error: " . $sql . "<br>" . $conexion->error;
        }

        $stmt->close();
        $conexion->close();
    } else {
        $error = "Error al subir la imagen.";
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
