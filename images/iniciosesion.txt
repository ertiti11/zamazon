<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require 'bbdd_Amazon.php' ?>
</head>
<body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $usuario =($_POST["usuario"]);
            $contrasena =($_POST["contrasena"]);

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $conexion -> query($sql);

            if($resultado -> num_rows === 0){
                echo "El usuario no existe";
            }else{

                while($fila = $resultado -> fetch_assoc()){ //coje una tabla y cada fila la transforma en una array
                    $contrasena_cifrada = $fila["contrasena"];
                }
    
                $acceso_valido = password_verify($contrasena, $contrasena_cifrada);
    
                if($acceso_valido){
                    session_start();
                    $_SESSION["usuario"] = $usuario;
                    //$_SESSION["loksea"] = $lokesea;
                    header('location: index.php');
                }else{
                    echo "contraseña incorrecta";
                }
            }
        }

            
    ?>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="from-label">Usuario</label>
                <input class="from-control" type="text" name="usuario">
            </div>

            <div class="mb-3">
                <label class="from-label">Contraseña</label>
                <input class="from-control" type="password" name="contrasena">
            </div>
            
            <input class="btn btn-primary" type="submit" value="Log In">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
</body>
</html>