<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require './css.php' ?>
  <link rel="stylesheet" href="./signin.css">
  <title>Login</title>
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
<?php
session_start();

if (isset($_SESSION["usuario"])) {
  header("location: /lista_productos.php");
} 

require './bd/con_bbdd.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usuario = ($_POST["usuario"]);
  $password = ($_POST["password"]);
  $error;
  $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
  $resultado = $conexion->query($sql);

  if ($resultado->num_rows === 0) {
    $error = "El usuario/contraseña son incorrectos";
  } else {

    while ($fila = $resultado->fetch_assoc()) { //coje una tabla y cada fila la transforma en una array
      $password_cifrada = $fila["contrasena"];
    }

    $acceso_valido = password_verify($password, $password_cifrada);

    if ($acceso_valido) {
      $_SESSION["usuario"] = $usuario;
      $usuario = $_SESSION["usuario"];

      // Verificar si el usuario ya tiene una cesta
      $sql_check_cesta = "SELECT * FROM cestas WHERE usuario = '$usuario'";
      $result_check_cesta = $conexion->query($sql_check_cesta);
  
      if ($result_check_cesta->num_rows == 0) {
          // El usuario no tiene una cesta, por lo tanto, se crea una cesta vacía
          $sql_crear_cesta = "INSERT INTO cestas (usuario) VALUES ('$usuario')";
          if ($conexion->query($sql_crear_cesta) === TRUE) {
              echo "Se ha adjuntado una cesta vacía al iniciar sesión.";
          } else {
              echo "Error al crear la cesta: " . $conexion->error;
          }
      }
      header('location: lista_productos.php');
    } else {
      $error = "El usuario/contraseña son incorrectos";
    }
  }
}


?>

<main class="form-signin">
  <form class="" method="post">
    <img class="mb-4 w-100" src="./images/amazon.png" alt="">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>" ?>
    <div class="form-floating">
      <input type="text" name="usuario" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Usuario</label>
    </div>
    <div class="form-floating">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
  </form>
</main>

<body>

</body>

</html>