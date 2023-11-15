<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $fechaNacimiento = $_POST["fechaNacimiento"];

    require './bd/con_bbdd.php';

    $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$passwordHash', '$fechaNacimiento')";

    $sql = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES (?, ?, ?)";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("sdsss", $this->nombreProducto, $this->precio, $this->descripcion, $this->cantidad, $imagen_guardada);

    if ($stmt->execute()) {
        $stmt->close();
        return true; // Producto guardado con éxito
    } else {
        $stmt->close();
        throw new Exception("Error al guardar el producto en la base de datos: " . $this->conexion->error);
    }

    $conexion->close();
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
            <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>" ?>
            <?php if (isset($success)) echo "<div class='alert alert-success' role='alert'>" . $success . "</div>" ?>
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