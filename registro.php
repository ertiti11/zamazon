<?php
require './bd/con_bbdd.php'; // Incluir el archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require './utils/validacion.php';
    $usuario = $_POST["usuario"];
    $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $fechaNacimiento = $_POST["fechaNacimiento"];


    $errorUsuario = validarNombreUsuario($usuario);

    // Utilizar la conexión del archivo de conexión
    $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    // Verificar si la preparación tuvo éxito
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincular los parámetros y sus tipos
    $stmt->bind_param("sss", $usuario, $passwordHash, $fechaNacimiento);
    if ($errorUsuario == "") {
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $stmt->close();
            $success = "Usuario registrado con éxito";
        } else {
            $stmt->close();
            $error = "Error al registrar el usuario en la base de datos: " . $conexion->error;
        }
    } else {
        $stmt->close();
    }
}

// Cerrar la conexión fuera del bloque condicional
$conexion->close();
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

            <!-- <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>" ?> -->
            <?php if (isset($success)) echo "<div class='alert alert-success' role='alert'>" . $success . "</div>" ?>
            <?php if (isset($errorUsuario)) echo "<div class='alert alert-danger' role='alert'>" . $errorUsuario . "</div>" ?>

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
                    <a href="/login"> ¿Ya tienes cuenta? Inicia sesión</a>
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </main>
</body>

</html>