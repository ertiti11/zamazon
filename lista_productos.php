<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
</head>
<body>
    <h2>Lista de Productos</h2>
    <ul>
        <?php
        // Conecta a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "123456789";
        $dbname = "amazon";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta para obtener los nombres de los productos y sus imágenes
        $sql = "SELECT nombre_producto, imagen FROM producto";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li>';
                echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre_producto"] . '" width="100" height="100">';
                echo '<span>' . $row["nombre_producto"] . '</span>';
                echo '</li>';
            }
        } else {
            echo "<li>No hay productos disponibles</li>";
        }

        $conn->close();
        ?>
    </ul>
</body>
</html>
