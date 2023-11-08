<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Usuario</title>
</head>
<body>
    <h2>Formulario para Agregar un Nuevo Usuario</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="usuario">Usuario (4-12 caracteres):</label>
        <input type="text" name="usuario" id="usuario" maxlength="12" required><br><br>

        <label for="contrasena">Contraseña (máx. 255 caracteres):</label>
        <input type="password" name="contrasena" id="contrasena" maxlength="255" required><br><br>

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fechaNacimiento" id="fechaNacimiento" required><br><br>

        <input type="submit" name="submit" value="Agregar Usuario">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
        $fechaNacimiento = $_POST["fechaNacimiento"];

        // Conecta a la base de datos y realiza la inserción de datos en la tabla Usuario
        $servername = "localhost";
        $username = "root";
        $password = "123456789";
        $dbname = "amazon";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$contrasena', '$fechaNacimiento')";

        if ($conn->query($sql) === TRUE) {
            echo "Usuario agregado con éxito.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
