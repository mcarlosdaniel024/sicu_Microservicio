<?php
function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function registrarAccion($usuario_id, $accion, $referencia_id = null) {
    global $conn;
    
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, accion, referencia_id) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("isi", $usuario_id, $accion, $referencia_id);
    $stmt->execute();
}

function enviarNotificacionVisita($visita_id, $estado) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT v.*, u.email, u.nombre 
        FROM visitantes v
        LEFT JOIN usuarios u ON v.usuario_id = u.id
        WHERE v.id = ?
    ");
    $stmt->bind_param("i", $visita_id);
    $stmt->execute();
    $visita = $stmt->get_result()->fetch_assoc();

    if ($visita && filter_var($visita['email'], FILTER_VALIDATE_EMAIL)) {
        $asunto = ($estado === 'aprobada') ? 
            "Visita Aprobada - SICU" : "Visita Rechazada - SICU";
        
        $mensaje = ($estado === 'aprobada') ?
            "Su visita ha sido aprobada para el {$visita['fecha_visita']}." :
            "Lamentamos informarle que su visita ha sido rechazada.";
        
        // Enviar correo (implementar según tu sistema)
        // enviarCorreo($visita['email'], $asunto, $mensaje);
    }
}

function generarCodigoVisitante($documento) {
    // Formato: VIS + 5 dígitos aleatorios + 3 últimos dígitos del documento
    $digitosAleatorios = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $ultimosDigitos = substr($documento, -3);
    return 'VIS' . $digitosAleatorios . $ultimosDigitos;
}
?>