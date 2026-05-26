<?php
session_start();
require_once '../../includes/db-connect.php';
require_once '../../includes/functions.php';

// Verificar sesión de admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../principal.php");
    exit();
}

// Obtener datos para el reporte
$sql_visitas = "SELECT * FROM visitantes ORDER BY created_at DESC";
$visitas = $conn->query($sql_visitas);

$sql_accesos = "SELECT ra.*, u.nombre, u.apellido 
                FROM registros_acceso ra 
                LEFT JOIN usuarios u ON ra.usuario_id = u.id 
                ORDER BY ra.fecha_hora_entrada DESC";
$accesos = $conn->query($sql_accesos);

// Configurar headers para Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="reporte_sicu_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Generar contenido Excel
echo "<table border='1'>";
echo "<tr><th colspan='5' style='background-color: #333; color: white; font-size: 16px;'>SISTEMA INTELIGENTE DE CONTROL UNIVERSITARIO</th></tr>";
echo "<tr><th colspan='5' style='background-color: #666; color: white;'>Reporte de Actividad - " . date('d/m/Y H:i') . "</th></tr>";
echo "<tr><td colspan='5'></td></tr>";

// Visitantes
echo "<tr><th colspan='5' style='background-color: #f5f5f5;'>VISITANTES REGISTRADOS</th></tr>";
echo "<tr style='background-color: #e0e0e0;'>
        <th>Documento</th>
        <th>Motivo</th>
        <th>Fecha Visita</th>
        <th>Estado</th>
        <th>Fecha Registro</th>
      </tr>";

while ($visita = $visitas->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($visita['documento']) . "</td>
            <td>" . htmlspecialchars($visita['motivo_visita']) . "</td>
            <td>" . date('d/m/Y', strtotime($visita['fecha_visita'])) . "</td>
            <td>" . ucfirst($visita['estado']) . "</td>
            <td>" . date('d/m/Y H:i', strtotime($visita['created_at'])) . "</td>
          </tr>";
}

echo "<tr><td colspan='5'></td></tr>";

// Registros de acceso
echo "<tr><th colspan='5' style='background-color: #f5f5f5;'>REGISTROS DE ACCESO</th></tr>";
echo "<tr style='background-color: #e0e0e0;'>
        <th>Usuario</th>
        <th>Tipo Acceso</th>
        <th>Fecha/Hora Entrada</th>
        <th>Fecha/Hora Salida</th>
        <th>Código Usado</th>
      </tr>";

while ($acceso = $accesos->fetch_assoc()) {
    echo "<tr>
            <td>" . ($acceso['usuario_id'] ? htmlspecialchars($acceso['nombre'] . ' ' . $acceso['apellido']) : 'Visitante') . "</td>
            <td>" . ucfirst($acceso['tipo_acceso']) . "</td>
            <td>" . date('d/m/Y H:i', strtotime($acceso['fecha_hora_entrada'])) . "</td>
            <td>" . ($acceso['fecha_hora_salida'] ? date('d/m/Y H:i', strtotime($acceso['fecha_hora_salida'])) : '--') . "</td>
            <td>" . htmlspecialchars($acceso['codigo_usado']) . "</td>
          </tr>";
}

echo "<tr><td colspan='5'></td></tr>";
echo "<tr><td colspan='5' style='text-align: center; color: #666;'>Generado automáticamente por SICU - Uniclaretiana</td></tr>";
echo "</table>";
?>