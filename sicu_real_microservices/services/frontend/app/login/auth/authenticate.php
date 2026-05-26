<?php
session_start();
require_once __DIR__ . '/../../includes/db-connect.php';

function authenticateUser($email, $password, $rol) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, nombre, apellido, password FROM usuarios WHERE email = ? AND rol = ?");
    $stmt->bind_param("ss", $email, $rol);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
            $_SESSION['user_role'] = $rol;
            return true;
        }
    }
    return false;
}
?>