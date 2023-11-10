<?php
session_start();


//comprobar si ha iniciado sesion y si no pues le pondremos la sesion con valor a invitado y un boton de login y si ha iniciado le pondremos un boton de logout
if (isset($_SESSION["usuario"])) {
    $logout =  '<a class="btn btn-primary" href="/logout">Logout</a>';
    $producto = '<li class="active"><a class="nav-link active" href="/producto">añadir producto</a></li>';
} else {
    $login = '<a class="btn btn-primary" href="/login">Login</a>';
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../images/amazon.png" alt="" width="128" height="64" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/lista_productos">lista de productos</a>
                </li>
                <?php if (isset($producto)) echo $producto ?>

            </ul>
            <div style="margin-right:3em;">
                <?php if (isset($login)) echo $login ?>
                <?php if (isset($logout)) echo $logout ?>
            </div>
        </div>
    </div>
</nav>