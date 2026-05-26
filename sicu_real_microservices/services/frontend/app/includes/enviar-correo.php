<?php
function enviarCorreo($destinatario, $asunto, $mensaje) {
    // Configuración básica (para producción usa PHPMailer o SendGrid)
    $headers = "From: no-reply@claretiano.edu\r\n";
    $headers .= "Reply-To: administracion@claretiano.edu\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Simulación - en producción descomenta:
    // return mail($destinatario, $asunto, $mensaje, $headers);
    
    // Para pruebas, guarda en un log:
    file_put_contents('correos.log', 
        "Para: $destinatario\nAsunto: $asunto\nMensaje: $mensaje\n\n", 
        FILE_APPEND
    );
    return true;
}
?>