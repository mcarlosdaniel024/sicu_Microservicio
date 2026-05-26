<?php
session_start();

// Conexión a Railway MySQL
$conn = new mysqli(
    "zephyr.proxy.rlwy.net",
    "root",
    "TMmfQayGQwaiFxvytmawtQNQDsTjekzJ",
    "railway",
    51090
);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$email = $_POST['email'];
$password = $_POST['contrasena'];

// Consulta segura preparada
$sql = "SELECT id, nombre, password, rol 
        FROM usuarios 
        WHERE email = ? AND rol = 'admin'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

// Verificación de credenciales
if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    // Comparación SHA-256
    if (hash('sha256', $password) === $user['password']) {

        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        header("Location: admin-dashboard.php");
        exit();
    }
}

// Si falla login
header("Location: principal.php?admin_error=1");
exit();
?>
