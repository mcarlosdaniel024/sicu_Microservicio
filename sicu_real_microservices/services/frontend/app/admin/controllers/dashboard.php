<?php
session_start();
require_once __DIR__ . '/../../includes/db-connect.php';

// Verificar si es admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: /admin-login.php");
    exit;
}

// Obtener visitas pendientes
$stmt = $conn->prepare("
    SELECT v.*, u.nombre as nombre_usuario 
    FROM visitantes v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE v.estado = 'pendiente'
    ORDER BY v.created_at DESC
");
$stmt->execute();
$visitas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Visitas Pendientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos mejorados */
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #f5f5f5; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        h1 {
            background: #000;
            color: white;
            padding: 20px;
            margin: 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse;
        }
        th, td { 
            padding: 12px 15px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0;
        }
        th { 
            background: #f8f9fa; 
            color: #333;
            font-weight: 600;
        }
        tr:hover { 
            background: #f8f9fa; 
        }
        .acciones {
            white-space: nowrap;
        }
        .btn { 
            padding: 8px 12px; 
            border-radius: 4px; 
            color: white; 
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-aprobar { 
            background: #28a745; 
        }
        .btn-rechazar { 
            background: #dc3545; 
        }
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .sin-visitas {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user-clock"></i> Visitas Pendientes de Aprobación</h1>
        
        <?php if (count($visitas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Motivo</th>
                    <th>Fecha Visita</th>
                    <th>Hora</th>
                    <th class="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitas as $visita): ?>
                <tr>
                    <td><?= htmlspecialchars($visita['documento']) ?></td>
                    <td><?= htmlspecialchars($visita['nombre_usuario'] ?? 'Visitante externo') ?></td>
                    <td><?= htmlspecialchars($visita['motivo_visita']) ?></td>
                    <td><?= date('d/m/Y', strtotime($visita['fecha_visita'])) ?></td>
                    <td><?= substr($visita['hora_entrada'], 0, 5) ?></td>
                    <td class="acciones">
                        <!-- Cambia los botones a: -->
                    <a href="aprobar-visita.php" class="btn btn-aprobar">
                        <i class="fas fa-check"></i> Aprobar
                    </a>
                    <a href="rechazar-visita.php?id=<?= $visita['id'] ?>" class="btn btn-rechazar">
                        <i class="fas fa-times"></i> Rechazar
                    </a>
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="sin-visitas">
            <p><i class="fas fa-check-circle" style="font-size: 2rem; color: #28a745;"></i></p>
            <p>No hay visitas pendientes de aprobación</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>