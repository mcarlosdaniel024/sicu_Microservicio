<?php
// login-profesores.php
require 'includes/login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (autenticarUsuario($email, $password, 'profesor')) {
        header('Location: entrada/index.php');
        exit();
    } else {
        $error = "Credenciales incorrectas o no eres profesor";
    }
}
?>

<!-- Mantenemos la misma estructura HTML pero cambiamos los textos -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Profesores - SICU</title>
    <!-- ... mismo estilo que login-estudiantes ... -->
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="login-container">
        <h1 class="login-title">Acceso Profesores</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- ... mismos campos que login-estudiantes ... -->
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>