<?php
session_start();
require_once 'includes/db-connect.php';

// Verificar sesión de admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../principal.php");
    exit();
}

// Consulta modificada (sin JOIN innecesario si no usas usuarios)
$sql_visitas = "SELECT * FROM visitantes WHERE estado = 'pendiente'";
$visitas = $conn->query($sql_visitas);

// Consulta de estadísticas segura
$sql_stats = "SELECT 
              COUNT(CASE WHEN estado = 'aprobado' THEN 1 END) as aprobadas,
              COUNT(CASE WHEN estado = 'rechazado' THEN 1 END) as rechazadas,
              COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes
              FROM visitantes";
$stats = $conn->query($sql_stats)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - SICU</title>
    <style>
        :root {
            --primary: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #f8f9fa; 
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            color: var(--primary);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }
        .aprobadas { color: var(--success); }
        .rechazadas { color: var(--danger); }
        .pendientes { color: var(--warning); }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: var(--primary);
            color: white;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            color: white;
        }
        .badge-pendiente { background: var(--warning); color: #000; }
        .badge-aprobado { background: var(--success); }
        .badge-rechazado { background: var(--danger); }
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }
        .btn-aprobar { background: var(--success); }
        .btn-rechazar { background: var(--danger); }
        .btn-logout { background: var(--primary); }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>
            <a href="logout.php" class="btn btn-logout">Cerrar sesión</a>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Visitas Aprobadas</h3>
                <div class="stat-value aprobadas"><?= $stats['aprobadas'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Visitas Rechazadas</h3>
                <div class="stat-value rechazadas"><?= $stats['rechazadas'] ?></div>
            </div>
            <div class="stat-card">
                <h3>Pendientes de Revisión</h3>
                <div class="stat-value pendientes"><?= $stats['pendientes'] ?></div>
            </div>
        </div>

        <!-- Listado de visitas pendientes -->
        <h2>Solicitudes de Visitas Pendientes</h2>
        <?php if ($visitas->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Motivo</th>
                        <th>Fecha Visita</th>
                        <th>Hora Entrada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($visita = $visitas->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($visita['documento']) ?></td>
                        <td><?= htmlspecialchars($visita['motivo_visita']) ?></td>
                        <td><?= date('d/m/Y', strtotime($visita['fecha_visita'])) ?></td>
                        <td><?= htmlspecialchars($visita['hora_entrada'] ?? '--') ?></td>
                        <td>
                            <span class="badge badge-pendiente">Pendiente</span>
                        </td>
                        <td>
                            <!-- Cambiar los enlaces de acciones a: -->
<a href="controllers/aprobar-visita.php?id=<?= $visita['id'] ?>" class="btn btn-aprobar">Aprobar</a>
<a href="controllers/rechazar-visita.php?id=<?= $visita['id'] ?>" class="btn btn-rechazar">Rechazar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay visitas pendientes de revisión.</p>
        <?php endif; ?>
    </div>
</body>
</html>