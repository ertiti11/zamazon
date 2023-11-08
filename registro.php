<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);
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

        $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$passwordHash', '$fechaNacimiento')";

        if ($conn->query($sql) === TRUE) {
            echo "Usuario agregado con éxito.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario de Usuario</title>
    <?php require './css.php' ?>
    <link rel="stylesheet" href="signin.css">

</head>

<body>
    <main class="form-signin">
        <form class="" method="post">
            <img class="mb-4 w-100" src="./images/amazon.png" alt="">
            <h1 class="h3 mb-3 fw-normal">Registro</h1>

            <div class="form-floating">
                <input type="text" name="usuario" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Usuario</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating">
                <input type="date" name="fechaNacimiento" class="form-control" placeholder="Password" required>
                <label for="floatingPassword">Fecha Nacimiento</label>
            </div>



            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </main>


</body>

</html>