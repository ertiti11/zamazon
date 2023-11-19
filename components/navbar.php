<?php

require './bd/con_bbdd.php';
//comprobar si ha iniciado sesion y si no pues le pondremos la sesion con valor a invitado y un boton de login y si ha iniciado le pondremos un boton de logout
if (isset($_SESSION["usuario"]) && $_SESSION["rol"] == "cliente") {
    $logout =  '<a class="btn btn-primary" href="/logout">Logout</a>';
} elseif ($_SESSION["rol"] == "admin") {
    $logout =  '<a class="btn btn-primary" href="/logout">Logout</a>';
    $producto = '<li class="active"><a class="nav-link active" href="/nuevo_producto">añadir producto</a></li>';
} else {
    $login = '<a class="btn btn-primary" href="/login">Login</a>';
}

$numProductosEnCesta = obtenerNumeroProductosEnCesta();


//crea la funcion obtenerNumeroProductosEnCesta

function obtenerNumeroProductosEnCesta()
{
    global $conexion;

    if (!isset($_SESSION["IdCesta"])) {
        // Si no hay IdCesta en la sesión, puede que el usuario no haya iniciado sesión o no tenga una cesta asignada
        return 0;
    }

    $idCesta = $_SESSION["IdCesta"];

    $sql = "SELECT SUM(cantidad) AS numProductosEnCesta
            FROM productoscestas
            WHERE IdCesta = ?";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        // Imprimir el error si la preparación de la consulta falla
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $idCesta);
    $stmt->execute();
    $stmt->bind_result($numProductosEnCesta);
    $stmt->fetch();
    $stmt->close();

    return $numProductosEnCesta;
}

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

            <button style="display: inline-flex; align-items: center; justify-content: center; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition-property: color, background-color, border-color, box-shadow; outline-offset: 2px; outline-style: none; outline-width: 2px; box-shadow: 0 0 0 2px transparent; pointer-events: auto; opacity: 1; border-width: 1px; background-color: #f8f9fa; color: #000; transition-duration: 75ms; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); height: 2.5rem; padding-left: 1rem; padding-right: 1rem; position: relative;" class="relative text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 pointer-events-auto opacity-100 border-width-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" h-5 w-5">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <span style="position: absolute; top: 0; right: 0; display: inline-flex; align-items: center; justify-content: center; padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.25rem; padding-bottom: 0.25rem; font-size: 0.75rem; font-weight: 700; line-height: 1; color: #fff; background-color: #e3342f; border-radius: 9999px;" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                    <?php echo $numProductosEnCesta; ?>
                </span>
            </button>
        </div>
    </div>
</nav>