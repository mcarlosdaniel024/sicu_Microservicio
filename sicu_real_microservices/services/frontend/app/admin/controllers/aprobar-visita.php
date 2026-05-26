<?php
session_start();
require_once '../../includes/db-connect.php';
require_once '../../includes/functions.php';

// Verificar sesión de admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../principal.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Primero obtener los datos del visitante
    $stmt = $conn->prepare("SELECT documento FROM visitantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $visita = $result->fetch_assoc();
        $codigo = generarCodigoVisitante($visita['documento']);
        
        // Actualizar estado y asignar código
        $stmt = $conn->prepare("UPDATE visitantes SET estado = 'aprobado', codigo = ? WHERE id = ?");
        $stmt->bind_param("si", $codigo, $id);
        
        if ($stmt->execute()) {
            // Opcional: Enviar correo con el código
            // enviarCorreoAprobacion($visita['email'], $codigo);
            
            header("Location: ../../admin-dashboard.php?success=Visita aprobada. Código: $codigo");
        } else {
            header("Location: ../../admin-dashboard.php?error=Error al aprobar la visita");
        }
    } else {
        header("Location: ../../admin-dashboard.php?error=Visitante no encontrado");
    }
    exit();
} else {
    header("Location: ../../admin-dashboard.php?error=ID no proporcionado");
    exit();
}
?>