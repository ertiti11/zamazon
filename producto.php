<?php
session_start();

//comprobar si ha iniciado sesion y si no pues le pondremos la sesion con valor a invitado y un boton de login y si ha iniciado le pondremos un boton de logout
if (!isset($_SESSION["usuario"])) {
    header("Location: ./login.php");
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $cantidad = $_POST["cantidad"];

    // Procesamiento de la imagen
    $imagen = $_FILES["imagen"]["name"];
    $imagen_temp = $_FILES["imagen"]["tmp_name"];

    $imagen_guardada = "./images/" . $imagen; // Ruta de destino de la imagen

    // Mueve la imagen desde la ubicación temporal a la carpeta de destino
    move_uploaded_file($imagen_temp, $imagen_guardada);

    // Conecta a la base de datos y realiza la inserción de datos en la tabla Producto
    $servername = "localhost";
    $username = "root";
    $password = "123456789";
    $dbname = "amazon";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "INSERT INTO Producto (nombre_producto, precio, descripcion, cantidad, imagen) VALUES ('$nombre', $precio, '$descripcion', $cantidad, '$imagen_guardada')";

    if ($conn->query($sql) === TRUE) {
        echo "Producto agregado con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario de Producto</title>
    <?php require './css.php' ?>
    <link rel="stylesheet" href="./signin.css">
</head>
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
</style>

<body>
    <header>
        <?php require './components/navbar.php' ?>
    </header>

    <main class="form-signin">
        <form class="" method="post">
            <h1 class="h3 mb-3 fw-normal">Nuevo producto</h1>
            <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>" ?>
            <div class="form-floating">
                <input type="text" name="nombre" class="form-control" id="floatingInput" placeholder="Nombre de producto" required>
                <label for="floatingInput">nombre de producto</label>
            </div>
            <div class="form-floating">
                <input type="text" name="precio" class="form-control" id="floatingPassword" placeholder="precio" required>
                <label for="floatingPassword">Precio</label>
            </div>
            <div class="form-floating">
                <input type="text" name="descripcion" class="form-control" id="floatingPassword" placeholder="Descripcion" required>
                <label for="floatingPassword">Descripcion</label>
            </div>
            <div class="form-floating">
                <input type="number" name="cantidad" class="form-control" id="floatingPassword" placeholder="Descripcion" required>
                <label for="floatingPassword">Cantidad</label>
            </div>
            <div class="form-floating">
                <input type="file" name="cantidad" class="form-control" id="floatingPassword" placeholder="Descripcion" required>
                <label for="floatingPassword">Imagen</label>
            </div>

           
            <button class="w-100 btn btn-lg btn-primary" type="submit">Añadir producto</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
        </form>
    </main>



</body>

</html>