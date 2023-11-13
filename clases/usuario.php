<?php
class Usuario
{
    private $conexion;

    public $usuario;
    public $contrasena;
    public $fechaNacimiento;


    public function __construct($conexion, $usuario, $contrasena, $fechaNacimiento)
    {
        $this->conexion = $conexion;
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
        $this->fechaNacimiento = $fechaNacimiento;
    }
}
