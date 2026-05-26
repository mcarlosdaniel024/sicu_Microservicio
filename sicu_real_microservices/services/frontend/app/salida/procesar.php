<?php
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// IMPORTANTE:
// Se eliminó el echo de depuración porque rompía los header()

require_once __DIR__ . '/../includes/db-connect.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($conn) || $conn === null) {

    $_SESSION['error'] = "Error grave de conexión a la base de datos.";

    header("Location: index.php");
    exit();
}

try {

    if (empty($_POST['codigo'])) {
        throw new Exception("Debe ingresar un código válido.");
    }

    $codigo_ingresado = trim($_POST['codigo']);

    $registro_encontrado = null;
    $tipo_persona_identificada = null;
    $id_en_tabla_principal = null;

    // ==========================
    // BUSCAR EN USUARIOS
    // ==========================
    $stmt_user = $conn->prepare("
        SELECT id, nombre, apellido, codigo
        FROM usuarios
        WHERE codigo = ?
        LIMIT 1
    ");

    $stmt_user->bind_param("s", $codigo_ingresado);
    $stmt_user->execute();

    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {

        $user_data = $result_user->fetch_assoc();

        $tipo_persona_identificada = 'usuario';
        $id_en_tabla_principal = $user_data['id'];

        $stmt_ra_user = $conn->prepare("
            SELECT id, fecha_hora_entrada, codigo_usado
            FROM registros_acceso
            WHERE usuario_id = ?
            AND fecha_hora_salida IS NULL
            ORDER BY fecha_hora_entrada DESC
            LIMIT 1
        ");

        $stmt_ra_user->bind_param("i", $id_en_tabla_principal);
        $stmt_ra_user->execute();

        $result_ra_user = $stmt_ra_user->get_result();

        if ($result_ra_user->num_rows === 0) {
            throw new Exception("No se encontró registro de entrada pendiente.");
        }

        $registro_encontrado = $result_ra_user->fetch_assoc();

        $registro_encontrado['user_nombre'] = $user_data['nombre'];
        $registro_encontrado['user_apellido'] = $user_data['apellido'];
        $registro_encontrado['user_codigo'] = $user_data['codigo'];

    } else {

        // ==========================
        // BUSCAR EN VISITANTES
        // ==========================
        $stmt_visitor = $conn->prepare("
            SELECT id, documento, codigo, motivo_visita,
                   fecha_visita, hora_entrada, hora_salida
            FROM visitantes
            WHERE codigo = ? OR documento = ?
            LIMIT 1
        ");

        $stmt_visitor->bind_param(
            "ss",
            $codigo_ingresado,
            $codigo_ingresado
        );

        $stmt_visitor->execute();

        $result_visitor = $stmt_visitor->get_result();

        if ($result_visitor->num_rows > 0) {

            $visitor_data = $result_visitor->fetch_assoc();

            $tipo_persona_identificada = 'visitante';
            $id_en_tabla_principal = $visitor_data['id'];

            if (
                !empty($visitor_data['hora_entrada']) &&
                empty($visitor_data['hora_salida'])
            ) {

                $registro_encontrado = $visitor_data;

            } else {

                throw new Exception(
                    "No se encontró registro pendiente para este visitante."
                );
            }

        } else {

            throw new Exception("Código no reconocido.");
        }
    }

    if (empty($registro_encontrado)) {
        throw new Exception(
            "No se pudo obtener la información del registro."
        );
    }

    // ==========================
    // TRANSACCIÓN
    // ==========================
    $conn->begin_transaction();

    if ($tipo_persona_identificada === 'usuario') {

        $update = $conn->prepare("
            UPDATE registros_acceso
            SET fecha_hora_salida = NOW(),
                tipo_acceso = 'salida'
            WHERE id = ?
        ");

        $update->bind_param("i", $registro_encontrado['id']);
        $update->execute();

    } elseif ($tipo_persona_identificada === 'visitante') {

        $update = $conn->prepare("
            UPDATE visitantes
            SET hora_salida = TIME(NOW())
            WHERE id = ?
        ");

        $update->bind_param("i", $id_en_tabla_principal);
        $update->execute();
    }

    $conn->commit();

    // ==========================
    // MICROSERVICIO AUDIT
    // ==========================
    $data = [
        "codigo" => $codigo_ingresado,
        "evento" => "salida",
        "tipo" => $tipo_persona_identificada
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context = stream_context_create($options);

    @file_get_contents(
        "http://sicu_audit:3000/evento",
        false,
        $context
    );

    // ==========================
    // DATOS PARA VISTA
    // ==========================
    $nombreCompleto = '';
    $motivo = '';
    $horaEntrada = '';
    $documento_info = '';

    if ($tipo_persona_identificada === 'usuario') {

        $nombreCompleto =
            $registro_encontrado['user_nombre'] . ' ' .
            $registro_encontrado['user_apellido'];

        $motivo = 'Acceso de usuario';

        $horaEntrada = date(
            'H:i:s',
            strtotime($registro_encontrado['fecha_hora_entrada'])
        );

        $documento_info = $registro_encontrado['user_codigo'];

    } else {

        $nombreCompleto = "Visitante";

        $motivo =
            $registro_encontrado['motivo_visita'] ??
            'Visita registrada';

        $horaEntrada =
            $registro_encontrado['hora_entrada'] ?? '';

        $documento_info =
            $registro_encontrado['documento'] ??
            $codigo_ingresado;
    }

    $_SESSION['registro_salida'] = [
        'nombre' => $nombreCompleto,
        'codigo' => $codigo_ingresado,
        'documento' => $documento_info,
        'motivo' => $motivo,
        'hora_entrada' => $horaEntrada,
        'hora_salida' => date('H:i:s'),
        'tipo' => $tipo_persona_identificada
    ];

    $_SESSION['mensaje_exito'] =
        "Salida registrada exitosamente.";

    header("Location: registro-exitoso.php");
    exit();

} catch (Exception $e) {

    if (isset($conn)) {
        $conn->rollback();
    }

    $_SESSION['error'] = $e->getMessage();

    header("Location: index.php");
    exit();
}
?>