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
  // Utilizar una consulta preparada para evitar inyección SQL
  $stmt = $conexion->prepare("SELECT usuario, contrasena,rol FROM usuarios WHERE usuario = ?");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    $error = "El usuario/contraseña son incorrectos";
  } else {
    while ($fila = $result->fetch_assoc()) {
      $password_cifrada = $fila["contrasena"];
      $rol = $fila["rol"];
    }

    $acceso_valido = password_verify($password, $password_cifrada);

    if ($acceso_valido) {
      $_SESSION["usuario"] = $usuario;
      $usuario = $_SESSION["usuario"];
      $_SESSION["rol"] = $rol;
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
        <a href="/registro"> ¿Todavía no tienes cuenta? Regístrate</a>
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
  </form>

  <script>
    // Función para enviar datos de analítica al servidor (puedes cambiar la URL)
    function enviarDatosAnalytics(datos) {
      // Simula el envío de datos al servidor, puedes reemplazarlo con una llamada AJAX real
      console.log('Enviando datos de analítica:', datos);
    }

    // Función para obtener el tiempo de permanencia en la página
    function obtenerTiempoPermanencia() {
      var tiempoActual = new Date().getTime();
      var tiempoEnPagina = tiempoActual - tiempoInicio;
      return tiempoEnPagina;
    }

    // Función para obtener datos almacenados en el localStorage
    function obtenerDatosLocalStorage() {
      var datosAlmacenados = localStorage.getItem('datos_analiticos');
      return datosAlmacenados ? JSON.parse(datosAlmacenados) : [];
    }

    // Función para guardar datos en el localStorage
    function guardarDatosLocalStorage(datos) {
      var datosAlmacenados = obtenerDatosLocalStorage();
      datosAlmacenados.push(datos);
      localStorage.setItem('datos_analiticos', JSON.stringify(datosAlmacenados));
    }

    // Registra el tiempo de inicio al cargar la página
    var tiempoInicio = new Date().getTime();

    // Registra la página vista
    var datosPaginaVista = {
      tipo: 'pagina_vista',
      url: window.location.href,
      timestamp: new Date().toISOString()
    };
    enviarDatosAnalytics(datosPaginaVista);
    guardarDatosLocalStorage(datosPaginaVista);

    // Registra el tiempo de permanencia cuando el usuario abandona la página
    window.addEventListener('beforeunload', function() {
      var tiempoEnPagina = obtenerTiempoPermanencia();
      var datosTiempoPermanencia = {
        tipo: 'tiempo_permanencia',
        tiempo: tiempoEnPagina,
        url: window.location.href,
        timestamp: new Date().toISOString()
      };
      enviarDatosAnalytics(datosTiempoPermanencia);
      guardarDatosLocalStorage(datosTiempoPermanencia);
    });

    // Agrega el evento de clic al botón (asegúrate de que exista un elemento con el id 'clicBoton')
    var boton = document.querySelector('.btn-primary');
    boton.addEventListener('click', function() {
      var datosClicBoton = {
        tipo: 'clic_boton',
        url: window.location.href,
        timestamp: new Date().toISOString()
      };
      enviarDatosAnalytics(datosClicBoton);
      guardarDatosLocalStorage(datosClicBoton);
    });

    // Registra eventos de clic en enlaces
    document.addEventListener('click', function(event) {
      if (event.target.tagName === 'A') {
        var datosClicEnlace = {
          tipo: 'clic_enlace',
          url: event.target.href,
          timestamp: new Date().toISOString()
        };
        enviarDatosAnalytics(datosClicEnlace);
        guardarDatosLocalStorage(datosClicEnlace);
      }
    });

    // Registra eventos de cambio de página
    var paginaActual = window.location.href;
    setInterval(function() {
      if (paginaActual !== window.location.href) {
        var datosCambioPagina = {
          tipo: 'cambio_pagina',
          url_anterior: paginaActual,
          url_nueva: window.location.href,
          timestamp: new Date().toISOString()
        };
        enviarDatosAnalytics(datosCambioPagina);
        guardarDatosLocalStorage(datosCambioPagina);
        paginaActual = window.location.href;
      }
    }, 1000); // Verifica el cambio de página cada segundo
  </script>
</main>

<body>

</body>

</html>