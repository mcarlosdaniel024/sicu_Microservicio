<?php
session_start();
require_once '../../includes/db-connect.php';
require_once '../../includes/functions.php';

// Verificar sesión de admin (igual que en aprobar-visita.php)
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../principal.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $admin_id = $_SESSION['admin_id'];
    
    // Registrar entrada manteniendo consistencia con tu estructura
    $stmt = $conn->prepare("UPDATE visitantes 
                          SET fecha_hora_entrada = NOW(), 
                              hora_entrada = TIME(NOW()),
                              usuario_registra_entrada = ?
                          WHERE id = ?");
    $stmt->bind_param("ii", $admin_id, $id);
    
    if ($stmt->execute()) {
        // Redirección consistente con tu patrón existente
        header("Location: ../../admin/admin-dashboard.php?success=Entrada+registrada+correctamente");
    } else {
        header("Location: ../../admin/admin-dashboard.php?error=Error+al+registrar+entrada");
    }
    exit();
}

// Redirección en caso de error (igual que en aprobar-visita.php)
header("Location: ../../admin/admin-dashboard.php?error=ID+no+proporcionado");
exit();
?>