<?php


function validarCadena($cadena) {
    // Expresión regular: solo letras, números y espacios, máximo 40 caracteres
    $patron = '/^[a-zA-Z0-9\sñÑ]{1,40}$/u';


    // Comprueba si la cadena coincide con el patrón
    if (preg_match($patron, $cadena)) {
        // La cadena es válida
        
    } else {
        // La cadena no es válida
        if (strlen($cadena) > 40) {
            return "La cadena excede los 40 caracteres permitidos.";
        } else {
            return "El nombre contiene caracteres no permitidos.";
        }
    }
}

function validarNumeroDecimal($numero) {
    if($numero < 0){
        return "El numero no puede ser negativo";
    }elseif($numero > 99999.99){
        return "El precio no puede ser mayor a 99999.99€";
    }
}

function validarDescripcion($cadena) {
    // Expresión regular: solo letras, números y espacios, máximo 255 caracteres
    $patron = '/^[a-zA-Z0-9\sñÑ]{1,255}$/u';

    // Comprueba si la cadena coincide con el patrón
    if (preg_match($patron, $cadena)) {
        // La cadena es válida
    } else {
        // La cadena no es válida
        if (strlen($cadena) > 255) {
            return "La descripción excede los 255 caracteres permitidos.";
        } else {
            return "La descripción contiene caracteres no permitidos.";
        }
    }
}


function validarCantidad($numero){
    //numero mayor a 0 y menor de 99999
    if($numero < 0){
        return "El numero no puede ser negativo";
    }elseif($numero > 99999){
        return "La cantidad no puede ser mayor a 99999";
    }
}



function validarNombreUsuario($nombreUsuario) {
    // Verificar la longitud del nombre de usuario
    $longitud = strlen($nombreUsuario);
    if ($longitud < 4 || $longitud > 12) {
        return "La longitud del nombre de usuario debe estar entre 4 y 12 caracteres.";
    }
    
    // Verificar caracteres permitidos (letras y barrabajas)
    if (!preg_match('/^[a-zA-Z_]+$/', $nombreUsuario)) {
        return "El nombre de usuario solo puede contener letras y barrabajas.";
    }
    
    // La validación pasó, el nombre de usuario es válido
    return;
}

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}




?>