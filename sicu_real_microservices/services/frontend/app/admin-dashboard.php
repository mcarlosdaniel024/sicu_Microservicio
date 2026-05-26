<?php
session_start();
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

// Verificar sesión de admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: principal.php");
    exit();
}

// Consulta de visitantes pendientes
$sql_visitas = "SELECT * FROM visitantes WHERE estado = 'pendiente'";
$visitas = $conn->query($sql_visitas);

// Consulta de visitantes aprobados (últimos 7 días)
$sql_aprobados = "SELECT * FROM visitantes 
                 WHERE estado = 'aprobado' 
                 AND fecha_visita >= CURDATE() 
                 ORDER BY fecha_visita DESC 
                 LIMIT 1000";
$aprobados = $conn->query($sql_aprobados);

// Consulta de estadísticas
$sql_stats = "SELECT 
              COUNT(CASE WHEN estado = 'aprobado' THEN 1 END) as aprobadas,
              COUNT(CASE WHEN estado = 'rechazado' THEN 1 END) as rechazadas,
              COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes
              FROM visitantes";
$stats = $conn->query($sql_stats)->fetch_assoc();

// Consultas para los reportes de accesos
$sql_accesos = "SELECT 
                DATE(fecha_hora_entrada) as fecha, 
                COUNT(CASE WHEN tipo_acceso = 'entrada' THEN 1 END) as entradas,
                COUNT(CASE WHEN tipo_acceso = 'salida' THEN 1 END) as salidas
                FROM registros_acceso
                WHERE fecha_hora_entrada >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(fecha_hora_entrada)
                ORDER BY fecha DESC";
$accesos = $conn->query($sql_accesos);

$sql_accesos_rol = "SELECT 
                    u.rol, 
                    COUNT(ra.id) as total
                    FROM registros_acceso ra
                    JOIN usuarios u ON ra.usuario_id = u.id
                    WHERE ra.fecha_hora_entrada >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    GROUP BY u.rol";
$accesos_rol = $conn->query($sql_accesos_rol);

$sql_accesos_hora = "SELECT 
                     HOUR(fecha_hora_entrada) as hora, 
                     COUNT(*) as total
                     FROM registros_acceso
                     WHERE fecha_hora_entrada >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     GROUP BY HOUR(fecha_hora_entrada)
                     ORDER BY hora";
$accesos_hora = $conn->query($sql_accesos_hora);

$sql_ultimos_accesos = "SELECT ra.*, u.nombre, u.apellido 
                       FROM registros_acceso ra
                       LEFT JOIN usuarios u ON ra.usuario_id = u.id
                       ORDER BY ra.fecha_hora_entrada DESC 
                       LIMIT 10";
$ultimos_accesos = $conn->query($sql_ultimos_accesos);

// Consulta para la tabla de auditoría
$sql_auditoria = "SELECT a.*, u.nombre, u.apellido 
                 FROM auditoria a 
                 LEFT JOIN usuarios u ON a.usuario_id = u.id 
                 ORDER BY a.fecha_hora DESC 
                 LIMIT 50";
$auditoria = $conn->query($sql_auditoria);

// Estadísticas de auditoría
$sql_stats_auditoria = "SELECT 
                       COUNT(*) as total_registros,
                       COUNT(DISTINCT tabla_afectada) as tablas_afectadas,
                       COUNT(DISTINCT accion) as tipos_accion,
                       COUNT(DISTINCT usuario_id) as usuarios_involucrados
                       FROM auditoria 
                       WHERE fecha_hora >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$stats_auditoria = $conn->query($sql_stats_auditoria)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - SICU</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Librería para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .section-title {
            margin-top: 2rem;
            color: var(--black);
            border-bottom: 2px solid var(--accent);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .copy-message {
            display: none;
            color: var(--success);
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .btn-copiar {
            background: var(--info);
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .admin-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            flex: 1;
            min-width: 200px;
            text-align: center;
            padding: 1.5rem;
        }
        
        /* Estilos para la sección de reportes */
        .report-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .report-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .report-card h3 {
            margin-top: 0;
            color: var(--black);
            border-bottom: 1px solid var(--light-gray);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Estilos para botones de exportación */
        .export-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border: 2px solid;
        }

        .btn-pdf {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .btn-pdf:hover {
            background: #c82333;
            border-color: #bd2130;
        }

        .btn-excel {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .btn-excel:hover {
            background: #218838;
            border-color: #1e7e34;
        }

        /* Estilos para la tabla de auditoría */
        .auditoria-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .auditoria-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .auditoria-table th,
        .auditoria-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .auditoria-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .auditoria-table tr:hover {
            background: #f8f9fa;
        }

        .badge-auditoria {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-insert { background: #d4edda; color: #155724; }
        .badge-update { background: #fff3cd; color: #856404; }
        .badge-delete { background: #f8d7da; color: #721c24; }
        .badge-entrada { background: #d1ecf1; color: #0c5460; }
        .badge-salida { background: #e2e3e5; color: #383d41; }

        .json-data {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
        }

        .json-data:hover {
            white-space: normal;
            overflow: visible;
        }

        /* Filtros de auditoría */
        .filtros-auditoria {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filtro-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filtro-group label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
        }

        .filtro-group select,
        .filtro-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-filtrar {
            background: var(--accent);
            color: var(--black);
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            align-self: flex-end;
        }

        /* Estadísticas de auditoría */
        .stats-auditoria {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-auditoria {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid var(--accent);
        }

        .stat-auditoria h4 {
            margin: 0 0 10px 0;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .stat-auditoria .valor {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--accent);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo-container">
                <i data-lucide="shield" class="logo-icon" width="40" height="40"></i>
                <div>
                    <div class="logo">PANEL<span>ADMIN</span></div>
                    <div class="logo-subtitle">Sistema Inteligente de Control Universitario</div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="principal.php">Inicio</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container main-content">
        <!-- Encabezado -->
        <div class="header-content" style="border-bottom: 1px solid #ddd; padding-bottom: 1rem; margin-bottom: 2rem;">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>
            <a href="logout.php" class="btn btn-danger">
                <i data-lucide="log-out" width="18" height="18"></i>
                Cerrar sesión
            </a>
        </div>

        <!-- Mostrar mensajes -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i data-lucide="check-circle" width="20" height="20"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i data-lucide="alert-circle" width="20" height="20"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Botones de exportación -->
        <div class="export-buttons">
            <a href="admin/controllers/exportar-pdf.php" class="btn-export btn-pdf">
                <i data-lucide="file-text" width="16" height="16"></i>
                Exportar a PDF
            </a>
            <a href="admin/controllers/exportar-excel.php" class="btn-export btn-excel">
                <i data-lucide="file-spreadsheet" width="16" height="16"></i>
                Exportar a Excel
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card aprobadas">
                <h3>Visitas Aprobadas</h3>
                <div class="stat-value aprobadas"><?= $stats['aprobadas'] ?></div>
                <p>Total aprobadas</p>
            </div>
            <div class="stat-card rechazadas">
                <h3>Visitas Rechazadas</h3>
                <div class="stat-value rechazadas"><?= $stats['rechazadas'] ?></div>
                <p>Total rechazadas</p>
            </div>
            <div class="stat-card pendientes">
                <h3>Pendientes de Revisión</h3>
                <div class="stat-value pendientes"><?= $stats['pendientes'] ?></div>
                <p>Esperando aprobación</p>
            </div>
        </div>

        <!-- Sección de Auditoría -->
        <h2 class="section-title">Auditoría del Sistema</h2>

        <!-- Estadísticas de auditoría -->
        <div class="stats-auditoria">
            <div class="stat-auditoria">
                <h4>Registros (30 días)</h4>
                <div class="valor"><?= $stats_auditoria['total_registros'] ?></div>
            </div>
            <div class="stat-auditoria">
                <h4>Tablas Afectadas</h4>
                <div class="valor"><?= $stats_auditoria['tablas_afectadas'] ?></div>
            </div>
            <div class="stat-auditoria">
                <h4>Tipos de Acción</h4>
                <div class="valor"><?= $stats_auditoria['tipos_accion'] ?></div>
            </div>
            <div class="stat-auditoria">
                <h4>Usuarios Involucrados</h4>
                <div class="valor"><?= $stats_auditoria['usuarios_involucrados'] ?></div>
            </div>
        </div>

        <!-- Filtros de auditoría -->
        <div class="filtros-auditoria">
            <div class="filtro-group">
                <label for="filtro-tabla">Tabla:</label>
                <select id="filtro-tabla">
                    <option value="">Todas las tablas</option>
                    <option value="visitantes">Visitantes</option>
                    <option value="registros_acceso">Registros Acceso</option>
                    <option value="usuarios">Usuarios</option>
                </select>
            </div>
            <div class="filtro-group">
                <label for="filtro-accion">Acción:</label>
                <select id="filtro-accion">
                    <option value="">Todas las acciones</option>
                    <option value="INSERT">INSERT</option>
                    <option value="UPDATE">UPDATE</option>
                    <option value="DELETE">DELETE</option>
                    <option value="ENTRADA">ENTRADA</option>
                    <option value="SALIDA">SALIDA</option>
                </select>
            </div>
            <div class="filtro-group">
                <label for="filtro-fecha">Fecha:</label>
                <input type="date" id="filtro-fecha">
            </div>
            <button class="btn-filtrar" onclick="filtrarAuditoria()">
                <i data-lucide="filter" width="16" height="16"></i>
                Filtrar
            </button>
        </div>

        <!-- Tabla de auditoría -->
        <div class="auditoria-container">
            <h3>Registros de Auditoría</h3>
            <div style="max-height: 600px; overflow-y: auto;">
                <table class="auditoria-table">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Usuario</th>
                            <th>Tabla</th>
                            <th>Acción</th>
                            <th>ID Registro</th>
                            <th>Datos Anteriores</th>
                            <th>Datos Nuevos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($registro = $auditoria->fetch_assoc()): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($registro['fecha_hora'])) ?></td>
                            <td>
                                <?php if ($registro['nombre']): ?>
                                    <?= htmlspecialchars($registro['nombre'] . ' ' . $registro['apellido']) ?>
                                <?php else: ?>
                                    <span style="color: #666;">Sistema</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($registro['tabla_afectada']) ?></td>
                            <td>
                                <?php 
                                $badge_class = '';
                                switch($registro['accion']) {
                                    case 'INSERT': $badge_class = 'badge-insert'; break;
                                    case 'UPDATE': $badge_class = 'badge-update'; break;
                                    case 'DELETE': $badge_class = 'badge-delete'; break;
                                    case 'ENTRADA': $badge_class = 'badge-entrada'; break;
                                    case 'SALIDA': $badge_class = 'badge-salida'; break;
                                    default: $badge_class = 'badge-update';
                                }
                                ?>
                                <span class="badge-auditoria <?= $badge_class ?>">
                                    <?= htmlspecialchars($registro['accion']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($registro['id_registro']) ?></td>
                            <td>
                                <?php if ($registro['datos_anteriores']): ?>
                                    <div class="json-data" title="<?= htmlspecialchars($registro['datos_anteriores']) ?>">
                                        <?= htmlspecialchars(substr($registro['datos_anteriores'], 0, 50)) ?>...
                                    </div>
                                <?php else: ?>
                                    <span style="color: #666;">--</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($registro['datos_nuevos']): ?>
                                    <div class="json-data" title="<?= htmlspecialchars($registro['datos_nuevos']) ?>">
                                        <?= htmlspecialchars(substr($registro['datos_nuevos'], 0, 50)) ?>...
                                    </div>
                                <?php else: ?>
                                    <span style="color: #666;">--</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de Reportes de Accesos -->
        <h2 class="section-title">Reportes de Entradas y Salidas</h2>

        <div class="report-container">
            <!-- Gráfico de accesos por día -->
            <div class="report-card">
                <h3>Accesos por Día (últimos 30 días)</h3>
                <div class="chart-container">
                    <canvas id="accesosChart"></canvas>
                </div>
            </div>
            
            <!-- Gráfico de accesos por rol -->
            <div class="report-card">
                <h3>Accesos por Tipo de Usuario</h3>
                <div class="chart-container">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>
            
            <!-- Gráfico de accesos por hora -->
            <div class="report-card">
                <h3>Accesos por Hora del Día</h3>
                <div class="chart-container">
                    <canvas id="horasChart"></canvas>
                </div>
            </div>
            
            <!-- Tabla de últimos registros -->
            <div class="report-card">
                <h3>Últimos Registros de Acceso</h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Fecha/Hora</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($acceso = $ultimos_accesos->fetch_assoc()): ?>
                            <tr>
                                <td><?= $acceso['usuario_id'] ? htmlspecialchars($acceso['nombre'].' '.$acceso['apellido']) : 'Visitante' ?></td>
                                <td><?= $acceso['tipo_acceso'] == 'entrada' ? 'Entrada' : 'Salida' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($acceso['fecha_hora_entrada'])) ?></td>
                                <td><?= htmlspecialchars($acceso['codigo_usado']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Listado de visitas pendientes -->
        <h2 class="section-title">Solicitudes de Visitas Pendientes</h2>
        <?php if ($visitas->num_rows > 0): ?>
            <div class="table-container">
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
                                <a href="admin/controllers/aprobar-visita.php?id=<?= $visita['id'] ?>" class="btn btn-success" style="padding: 0.5rem 1rem;">
                                    <i data-lucide="check" width="16" height="16"></i>
                                    Aprobar
                                </a>
                                <a href="admin/controllers/rechazar-visita.php?id=<?= $visita['id'] ?>" class="btn btn-danger" style="padding: 0.5rem 1rem;">
                                    <i data-lucide="x" width="16" height="16"></i>
                                    Rechazar
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card">
                <p style="text-align: center; color: var(--dark-gray);">No hay visitas pendientes de revisión.</p>
            </div>
        <?php endif; ?>

        <!-- Listado de visitas aprobadas recientes -->
        <h2 class="section-title">Visitas Aprobadas Recientemente</h2>
        <?php if ($aprobados->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Motivo</th>
                            <th>Fecha Visita</th>
                            <th>Código de Acceso</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($visita = $aprobados->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($visita['documento']) ?></td>
                            <td><?= htmlspecialchars($visita['motivo_visita']) ?></td>
                            <td><?= date('d/m/Y', strtotime($visita['fecha_visita'])) ?></td>
                            <td>
                                <?php if ($visita['codigo']): ?>
                                    <span class="badge badge-codigo" id="codigo-<?= $visita['id'] ?>">
                                        <?= htmlspecialchars($visita['codigo']) ?>
                                    </span>
                                    <button class="btn-copiar" onclick="copiarCodigo('codigo-<?= $visita['id'] ?>')">
                                        <i data-lucide="copy" width="14" height="14"></i>
                                        Copiar
                                    </button>
                                    <span class="copy-message" id="copy-msg-<?= $visita['id'] ?>">¡Copiado!</span>
                                <?php else: ?>
                                    --
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-aprobado">Aprobado</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card">
                <p style="text-align: center; color: var(--dark-gray);">No hay visitas aprobadas recientemente.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-logo">
                <img src="img/logo-1.png" alt="Uniclaretiana" style="height: 80px;">
            </div>

            <div class="footer-columns">
                <div class="footer-column">
                    <h4>OFERTA ACADÉMICA</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/pregrados/">Pregrados</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/pregrados/">Pregrados</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/posgrados/">Posgrados</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/tecnicas/">Programas Técnicos</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>SERVICIOS</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/centro-de-atencion-psicosocial/">Centro Psicosocial</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/biblioteca/">Sistema de Bibliotecas</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/consultorio-juridico/">Consultorio Jurídico</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>INFORMACIÓN LEGAL</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/wp-content/uploads/2023/02/ESTATUTO-GENERAL-2023.pdf">Estatuto General</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/wp-content/uploads/2023/02/REGLAMENTO-ESTUDIANTIL.-UNICLARETIANA.pdf">Reglamento Estudiantil</a></li>
                        <li><a href="#">Protección de Datos</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>U-VIRTUAL</h4>
                    <ul>
                        <li><a href="https://sga.claretiano.edu.br/sav/uniclaretiana">Aula Virtual</a></li>
                        <li><a href="https://iceberg-cloud.casewaresa.com/cla/iceberg-pf/">Portal Financiero</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/certificados-constancias-duplicados/">Certificados</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-links">
                    <a href="https://www.uniclaretiana.edu.co/pqrsf/">PQRS</a>
                    <a href="https://www.uniclaretiana.edu.co/contactanos/">Contacto</a>
                </div>
                <p class="footer-text">
                    Institución Educativa Superior sujeta a inspección y vigilancia del Ministerio de Educación Nacional
                </p>
                <p class="footer-text">
                    Fundación Universitaria Claretiana - Uniclaretiana
                </p>
                <p class="copyright">
                    Copyright © 2023 SICU. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        
        // Función para copiar código al portapapeles
        function copiarCodigo(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                const copyMsg = document.getElementById('copy-msg-' + elementId.split('-')[1]);
                copyMsg.style.display = 'inline';
                setTimeout(() => {
                    copyMsg.style.display = 'none';
                }, 2000);
            });
        }

        // Función para filtrar la tabla de auditoría
        function filtrarAuditoria() {
            const tabla = document.getElementById('filtro-tabla').value;
            const accion = document.getElementById('filtro-accion').value;
            const fecha = document.getElementById('filtro-fecha').value;
            
            const filas = document.querySelectorAll('.auditoria-table tbody tr');
            
            filas.forEach(fila => {
                let mostrar = true;
                const celdas = fila.querySelectorAll('td');
                
                // Filtrar por tabla
                if (tabla && celdas[2].textContent.trim() !== tabla) {
                    mostrar = false;
                }
                
                // Filtrar por acción
                if (accion && !celdas[3].textContent.includes(accion)) {
                    mostrar = false;
                }
                
                // Filtrar por fecha
                if (fecha) {
                    const fechaFila = celdas[0].textContent.trim().split(' ')[0];
                    const fechaFilaFormateada = fechaFila.split('/').reverse().join('-');
                    if (fechaFilaFormateada !== fecha) {
                        mostrar = false;
                    }
                }
                
                fila.style.display = mostrar ? '' : 'none';
            });
        }

        // Datos para los gráficos
        const accesosData = {
            fechas: [<?php 
                $fechas = [];
                $entradas = [];
                $salidas = [];
                while($row = $accesos->fetch_assoc()) {
                    $fechas[] = "'".date('d/m', strtotime($row['fecha']))."'";
                    $entradas[] = $row['entradas'];
                    $salidas[] = $row['salidas'];
                }
                echo implode(', ', $fechas);
            ?>],
            entradas: [<?php echo implode(', ', $entradas); ?>],
            salidas: [<?php echo implode(', ', $salidas); ?>]
        };

        const rolesData = {
            labels: [<?php 
                $labels = [];
                $values = [];
                while($row = $accesos_rol->fetch_assoc()) {
                    $labels[] = "'".ucfirst(str_replace('_', ' ', $row['rol']))."'";
                    $values[] = $row['total'];
                }
                echo implode(', ', $labels);
            ?>],
            values: [<?php echo implode(', ', $values); ?>]
        };

        const horasData = {
            horas: [<?php 
                $horas = [];
                $totales = [];
                while($row = $accesos_hora->fetch_assoc()) {
                    $horas[] = "'".$row['hora'].":00'";
                    $totales[] = $row['total'];
                }
                echo implode(', ', $horas);
            ?>],
            totales: [<?php echo implode(', ', $totales); ?>]
        };

        // Inicializar gráficos cuando el DOM esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de accesos por día
            const accesosCtx = document.getElementById('accesosChart').getContext('2d');
            new Chart(accesosCtx, {
                type: 'bar',
                data: {
                    labels: accesosData.fechas,
                    datasets: [
                        {
                            label: 'Entradas',
                            data: accesosData.entradas,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Salidas',
                            data: accesosData.salidas,
                            backgroundColor: 'rgba(220, 53, 69, 0.7)',
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de accesos por rol
            const rolesCtx = document.getElementById('rolesChart').getContext('2d');
            new Chart(rolesCtx, {
                type: 'pie',
                data: {
                    labels: rolesData.labels,
                    datasets: [{
                        data: rolesData.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gráfico de accesos por hora
            const horasCtx = document.getElementById('horasChart').getContext('2d');
            new Chart(horasCtx, {
                type: 'line',
                data: {
                    labels: horasData.horas,
                    datasets: [{
                        label: 'Accesos por hora',
                        data: horasData.totales,
                        backgroundColor: 'rgba(23, 162, 184, 0.2)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>