<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Intentando conectar a la base de datos...<br>";

try {
    require_once 'db-connect.php'; // Ajusta la ruta si 'test_db.php' no está en la misma carpeta que 'includes'
                                  // Si 'test_db.php' está en la raíz y 'db-connect.php' en 'includes', sería 'includes/db-connect.php'
                                  // Si 'test_db.php' está en 'includes', sería 'db-connect.php'

    if ($conn) {
        echo "¡Conexión a la base de datos exitosa!<br>";
        // Prueba una consulta simple para verificar
        $result = $conn->query("SELECT DATABASE()");
        if ($result) {
            $row = $result->fetch_row();
            echo "Base de datos actual: " . $row[0] . "<br>";
        } else {
            echo "Error al obtener nombre de la base de datos: " . $conn->error . "<br>";
        }
        $conn->close();
    } else {
        echo "ERROR: La variable \$conn no está definida o es nula después de incluir db-connect.php.<br>";
    }
} catch (Exception $e) {
    echo "Excepción capturada: " . $e->getMessage() . "<br>";
    echo "Error de conexión (si aplica): " . mysqli_connect_error() . "<br>";
    echo "Código de error de conexión (si aplica): " . mysqli_connect_errno() . "<br>";
}

echo "Fin del script de prueba de conexión.<br>";
?>