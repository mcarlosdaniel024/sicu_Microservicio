<?php
session_start();
require_once '../../includes/db-connect.php';
require_once '../../includes/functions.php';

// Mismo sistema de verificación de sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../principal.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $admin_id = $_SESSION['admin_id'];
    
    // Registrar salida con el mismo estilo
    $stmt = $conn->prepare("UPDATE visitantes 
                          SET fecha_hora_salida = NOW(), 
                              hora_salida = TIME(NOW()),
                              usuario_registra_salida = ?
                          WHERE id = ?");
    $stmt->bind_param("ii", $admin_id, $id);
    
    if ($stmt->execute()) {
        // Mismo formato de redirección
        header("Location: ../../admin/admin-dashboard.php?success=Salida+registrada+correctamente");
    } else {
        header("Location: ../../admin/admin-dashboard.php?error=Error+al+registrar+salida");
    }
    exit();
}

// Mismo manejo de errores
header("Location: ../../admin/admin-dashboard.php?error=ID+no+proporcionado");
exit();
?>