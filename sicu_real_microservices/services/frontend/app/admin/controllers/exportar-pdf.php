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

// Crear contenido HTML para PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte SICU - ' . date('d/m/Y') . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        h2 { color: #666; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: center; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema Inteligente de Control Universitario</h1>
        <h2>Reporte de Actividad - ' . date('d/m/Y H:i') . '</h2>
    </div>

    <h2>Visitantes Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Motivo</th>
                <th>Fecha Visita</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>';

while ($visita = $visitas->fetch_assoc()) {
    $html .= '
            <tr>
                <td>' . htmlspecialchars($visita['documento']) . '</td>
                <td>' . htmlspecialchars($visita['motivo_visita']) . '</td>
                <td>' . date('d/m/Y', strtotime($visita['fecha_visita'])) . '</td>
                <td>' . ucfirst($visita['estado']) . '</td>
                <td>' . date('d/m/Y H:i', strtotime($visita['created_at'])) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>

    <h2>Registros de Acceso</h2>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Tipo Acceso</th>
                <th>Fecha/Hora Entrada</th>
                <th>Fecha/Hora Salida</th>
                <th>Código Usado</th>
            </tr>
        </thead>
        <tbody>';

while ($acceso = $accesos->fetch_assoc()) {
    $html .= '
            <tr>
                <td>' . ($acceso['usuario_id'] ? htmlspecialchars($acceso['nombre'] . ' ' . $acceso['apellido']) : 'Visitante') . '</td>
                <td>' . ucfirst($acceso['tipo_acceso']) . '</td>
                <td>' . date('d/m/Y H:i', strtotime($acceso['fecha_hora_entrada'])) . '</td>
                <td>' . ($acceso['fecha_hora_salida'] ? date('d/m/Y H:i', strtotime($acceso['fecha_hora_salida'])) : '--') . '</td>
                <td>' . htmlspecialchars($acceso['codigo_usado']) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>

    <div class="footer">
        <p>Generado automáticamente por SICU - Uniclaretiana</p>
    </div>
</body>
</html>';

// Configurar headers para PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="reporte_sicu_' . date('Y-m-d') . '.pdf"');

// Para generar PDF real necesitarías una librería como TCPDF o Dompdf
// Por ahora solo mostramos el HTML
echo $html;
?>