<?php
class Producto
{
    private $conexion;

    public $nombreProducto;
    public $precio;
    public $descripcion;
    public $cantidad;
    public $imagen;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearProducto($nombre, $precio, $descripcion, $cantidad, $imagen)
    {
        $this->nombreProducto = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->imagen = $imagen;
    }

    public function guardarProducto()
    {
        $file_size = $this->imagen['size']; // Obtener el tamaño del archivo desde $_FILES

        $max_file_size = 5 * 1024 * 1024; // 5 MB
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = pathinfo($this->imagen['name'], PATHINFO_EXTENSION);

        if ($file_size > $max_file_size || !in_array(strtolower($file_extension), $allowed_extensions)) {
            throw new Exception("El archivo de imagen no cumple con los requisitos.");
        }

        $imagen_guardada = "./images/" . basename($this->imagen['name']); // Ruta donde se almacenará la imagen

        if (!move_uploaded_file($this->imagen['tmp_name'], $imagen_guardada)) {
            throw new Exception("Error al guardar la imagen.");
        }

        $sql = "INSERT INTO productos (nombreProducto, precio, descripcion, cantidad, imagen) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sdsss", $this->nombreProducto, $this->precio, $this->descripcion, $this->cantidad, $imagen_guardada);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Producto guardado con éxito
        } else {
            $stmt->close();
            throw new Exception("Error al guardar el producto en la base de datos: " . $this->conexion->error);
        }
    }
}
