<?php
require_once 'db-connect.php';

header('Content-Type: application/json');

// Consulta para obtener entradas de hoy
$sql_entradas = "SELECT COUNT(*) as total FROM registros_acceso 
                 WHERE tipo_access = 'entrada' 
                 AND DATE(fecha_hora_entrada) = CURDATE()";
$result_entradas = $conn->query($sql_entradas);
$entradas_hoy = $result_entradas ? $result_entradas->fetch_assoc()['total'] : 0;

// Consulta para obtener salidas de hoy
$sql_salidas = "SELECT COUNT(*) as total FROM registros_acceso 
                WHERE tipo_access = 'salida' 
                AND DATE(fecha_hora_entrada) = CURDATE()";
$result_salidas = $conn->query($sql_salidas);
$salidas_hoy = $result_salidas ? $result_salidas->fetch_assoc()['total'] : 0;

echo json_encode([
    'entradas' => $entradas_hoy,
    'salidas' => $salidas_hoy
]);

$conn->close();
?>