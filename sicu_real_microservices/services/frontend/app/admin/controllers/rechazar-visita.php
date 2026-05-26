<?php
session_start();
require_once '../../includes/db-connect.php';

// Verificar sesión de admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../principal.php");
    exit();
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Actualizar estado de la visita
    $stmt = $conn->prepare("UPDATE visitantes SET estado = 'rechazado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header("Location: ../../admin-dashboard.php?success=Visita rechazada correctamente");
    } else {
        // Redirigir con mensaje de error
        header("Location: ../../admin-dashboard.php?error=Error al rechazar la visita");
    }
    exit();
} else {
    header("Location: ../../admin-dashboard.php?error=ID no proporcionado");
    exit();
}
?>