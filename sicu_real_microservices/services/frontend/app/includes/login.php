<?php
session_start();
require_once 'includes/db-connect.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el tipo de usuario que está intentando loguearse
    $tipo = $_POST['tipo'] ?? '';
    $error = false;
    
    // Validar que el tipo sea uno de los permitidos
    if (!in_array($tipo, ['estudiante', 'profesor', 'personal', 'directivo'])) {
        header("Location: principal.php");
        exit();
    }

    // Procesar según el tipo de usuario
    switch ($tipo) {
        case 'estudiante':
            // Login para estudiantes
            $codigo = trim($_POST['codigo'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($codigo) || empty($password)) {
                $error = true;
                break;
            }
            
            // Buscar estudiante en la base de datos
            $stmt = $conn->prepare("SELECT u.id, u.nombre, u.apellido, u.password 
                                  FROM usuarios u
                                  JOIN estudiantes e ON u.id = e.usuario_id
                                  WHERE e.codigo_estudiante = ?");
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                // Verificar contraseña (asumiendo que está hasheada)
                if (password_verify($password, $usuario['password'])) {
                    // Login exitoso
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                    $_SESSION['user_role'] = 'estudiante';
                    
                    header("Location: estudiante/dashboard.php");
                    exit();
                }
            }
            $error = true;
            break;
            
        case 'profesor':
            // Login para profesores
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = true;
                break;
            }
            
            // Buscar profesor en la base de datos
            $stmt = $conn->prepare("SELECT u.id, u.nombre, u.apellido, u.password 
                                  FROM usuarios u
                                  JOIN profesores p ON u.id = p.usuario_id
                                  WHERE u.email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                if (password_verify($password, $usuario['password'])) {
                    // Login exitoso
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                    $_SESSION['user_role'] = 'profesor';
                    
                    header("Location: profesor/dashboard.php");
                    exit();
                }
            }
            $error = true;
            break;
            
        case 'personal':
            // Login para personal administrativo
            $codigo = trim($_POST['codigo'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($codigo) || empty($password)) {
                $error = true;
                break;
            }
            
            // Buscar personal administrativo
            $stmt = $conn->prepare("SELECT u.id, u.nombre, u.apellido, u.password 
                                  FROM usuarios u
                                  JOIN personal_administrativo pa ON u.id = pa.usuario_id
                                  WHERE pa.codigo_empleado = ?");
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                if (password_verify($password, $usuario['password'])) {
                    // Login exitoso
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                    $_SESSION['user_role'] = 'personal_administrativo';
                    
                    header("Location: personal/dashboard.php");
                    exit();
                }
            }
            $error = true;
            break;
            
        case 'directivo':
            // Login para directivos
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = true;
                break;
            }
            
            // Buscar directivo en la base de datos
            $stmt = $conn->prepare("SELECT u.id, u.nombre, u.apellido, u.password 
                                  FROM usuarios u
                                  JOIN directivos d ON u.id = d.usuario_id
                                  WHERE u.email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                if (password_verify($password, $usuario['password'])) {
                    // Login exitoso
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                    $_SESSION['user_role'] = 'directivo';
                    
                    header("Location: directivo/dashboard.php");
                    exit();
                }
            }
            $error = true;
            break;
    }
    
    // Si hubo un error, redirigir con parámetro de error
    if ($error) {
        $error_param = '';
        switch ($tipo) {
            case 'estudiante': $error_param = 'student_error'; break;
            case 'profesor': $error_param = 'teacher_error'; break;
            case 'personal': $error_param = 'staff_error'; break;
            case 'directivo': $error_param = 'director_error'; break;
        }
        
        if (!empty($error_param)) {
            header("Location: principal.php?$error_param=1");
            exit();
        }
    }
}

// Si no es POST o no se reconoce el tipo, redirigir al inicio
header("Location: principal.php");
exit();
?>