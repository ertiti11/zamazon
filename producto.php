<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Producto</title>
</head>
<body>
    <h2>Formulario para Agregar un Nuevo Producto</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

        <label for="nombre">Nombre del Producto (máx. 40 caracteres):</label>
        <input type="text" name="nombre" id="nombre" maxlength="40" required><br><br>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" min="0" required><br><br>

        <label for="descripcion">Descripción (máx. 255 caracteres):</label>
        <textarea name="descripcion" id="descripcion" maxlength="255"></textarea><br><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" min="0" required><br><br>

        <label for="imagen">Imagen del Producto:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*"><br><br>

        <input type="submit" name="submit" value="Agregar Producto">
    </form>

    <a href="usuarios.php" style="border-radius: 15px; padding: 1rem; background-color: green; margin:3em"> REGISTRAR USUARIO</a>

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
</body>
</html>
