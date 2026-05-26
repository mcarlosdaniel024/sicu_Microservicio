<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Intentando incluir functions.php...<br>";

try {
    require_once '../includes/functions.php'; // Ajusta la ruta según la ubicación de 'test_functions.php'

    echo "¡functions.php incluido exitosamente!<br>";

    // Si tienes alguna función simple en functions.php que no requiera DB, puedes probarla aquí.
    // Por ejemplo, si tienes function saludar($nombre) { return "Hola $nombre"; }
    // echo saludar("Mundo") . "<br>";

} catch (Exception $e) {
    echo "Excepción capturada al incluir functions.php: " . $e->getMessage() . "<br>";
}

echo "Fin del script de prueba de funciones.<br>";
?>