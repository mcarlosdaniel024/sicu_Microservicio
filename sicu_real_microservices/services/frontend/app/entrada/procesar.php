<?php
require_once __DIR__ . '/../includes/db-connect.php';

session_start();

// Validar datos
if (!isset($_POST['codigo'])) {

    $_SESSION['error'] = "Código no proporcionado";

    header("Location: index.php");
    exit();
}

$codigo = trim($_POST['codigo']);

$usuario = null;
$tipo_usuario = null;

// Buscar en usuarios
$stmt = $conn->prepare("
    SELECT id, nombre, rol 
    FROM usuarios 
    WHERE codigo = ?
");

$stmt->bind_param("s", $codigo);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $usuario = $result->fetch_assoc();
    $tipo_usuario = 'miembro';

} else {

    // Buscar en visitantes
    $stmt = $conn->prepare("
        SELECT id, documento 
        FROM visitantes 
        WHERE codigo = ?
    ");

    $stmt->bind_param("s", $codigo);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $usuario = $result->fetch_assoc();
        $tipo_usuario = 'visitante';
    }
}

// Procesar
if ($usuario) {

    // ==========================
    // MIEMBRO
    // ==========================
    if ($tipo_usuario === 'miembro') {

        $stmt = $conn->prepare("
            INSERT INTO registros_acceso 
            (usuario_id, fecha_hora_entrada, tipo_acceso, codigo_usado) 
            VALUES (?, NOW(), 'entrada', ?)
        ");

        $stmt->bind_param("is", $usuario['id'], $codigo);

        if ($stmt->execute()) {

            // Enviar evento al microservicio
            $data = [
                "codigo" => $codigo,
                "evento" => "entrada",
                "tipo" => "usuario",
                "usuario" => $usuario['nombre']
            ];

            enviarEvento($data);

            $_SESSION['mensaje'] = "Entrada registrada: " . $usuario['nombre'];

            header("Location: registro-exitoso.php");
            exit();

        } else {

            $_SESSION['error'] = "Error al registrar acceso";

            header("Location: index.php");
            exit();
        }

    } else {

        // ==========================
        // VISITANTE
        // ==========================
        $stmt = $conn->prepare("
            UPDATE visitantes 
            SET hora_entrada = NOW()
            WHERE id = ?
        ");

        $stmt->bind_param("i", $usuario['id']);

        if ($stmt->execute()) {

            // Evento visitante
            $data = [
                "codigo" => $codigo,
                "evento" => "entrada",
                "tipo" => "visitante"
            ];

            enviarEvento($data);

            $_SESSION['mensaje'] = "Entrada visitante registrada";

            header("Location: registro-exitoso.php");
            exit();

        } else {

            $_SESSION['error'] = "Error al registrar entrada del visitante";

            header("Location: index.php");
            exit();
        }
    }

} else {

    $_SESSION['error'] = "Código no registrado: {$codigo}";

    header("Location: index.php");
    exit();
}

// ===================================
// FUNCIÓN MICROSERVICIO
// ===================================
function enviarEvento($data) {

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context = stream_context_create($options);

    // Docker: usar nombre del servicio
    @file_get_contents(
        "http://sicu_audit:3000/evento",
        false,
        $context
    );
}
?>