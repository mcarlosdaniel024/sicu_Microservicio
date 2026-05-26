<?php
session_start();

// 1. Conexión a la base de datos
$conn = new mysqli("mysql", "root", "root", "sicu_db");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 2. Obtener datos del formulario
$email = $_POST['email'];
$password = $_POST['contrasena'];

// 3. Consulta segura preparada
$sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = ? AND rol = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// 4. Verificación de credenciales
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Comparación con SHA-256
    if (hash('sha256', $password) === $user['password']) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        header("Location: admin-dashboard.php");
        exit();
    }
}

// 5. Redirección si falla - CORREGIDO
header("Location: principal.php?admin_error=1");
exit();
?>