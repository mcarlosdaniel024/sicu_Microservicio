<?php
// login-estudiantes.php
require 'includes/login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (autenticarUsuario($email, $password, 'estudiante')) {
        header('Location: entrada/index.php');
        exit();
    } else {
        $error = "Credenciales incorrectas o no eres estudiante";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Estudiantes - SICU</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .login-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80') center/cover no-repeat;
            color: var(--white);
            padding: 4rem 0;
            text-align: center;
        }
        
        .login-card {
            max-width: 400px;
            margin: 2rem auto;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: var(--green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo-container">
                <i data-lucide="graduation-cap" class="logo-icon" width="40" height="40"></i>
                <div>
                    <div class="logo">SICU<span>CLARETIANO</span></div>
                    <div class="logo-subtitle">Acceso Estudiantes</div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="principal.php">Inicio</a></li>
                    <li><a href="registro-visitantes.php">Visitantes</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="login-hero">
        <div class="container">
            <h1>Acceso Estudiantes</h1>
            <p>Ingresa a tu portal estudiantil</p>
        </div>
    </section>

    <div class="container main-content">
        <div class="card login-card">
            <div class="login-icon">
                <i data-lucide="graduation-cap" width="40" height="40" stroke="white"></i>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i data-lucide="alert-circle" width="20" height="20"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Correo Institucional</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="estudiante@uniclaretiana.edu.co">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="••••••••">
                </div>
                <button type="submit" class="btn" style="width: 100%;">
                    <i data-lucide="log-in" width="18" height="18"></i>
                    Ingresar
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 1rem;">
                <a href="principal.php" style="color: var(--dark-gray); text-decoration: none;">
                    <i data-lucide="arrow-left" width="16" height="16"></i>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-logo">
                <img src="img/logo-1.png" alt="Uniclaretiana" style="height: 80px;">
            </div>

            <div class="footer-columns">
                <div class="footer-column">
                    <h4>OFERTA ACADÉMICA</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/pregrados/">Pregrados</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/posgrados/">Posgrados</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/tecnicas/">Programas Técnicos</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>SERVICIOS</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/centro-de-atencion-psicosocial/">Centro Psicosocial</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/biblioteca/">Sistema de Bibliotecas</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/consultorio-juridico/">Consultorio Jurídico</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>INFORMACIÓN LEGAL</h4>
                    <ul>
                        <li><a href="https://www.uniclaretiana.edu.co/wp-content/uploads/2023/02/ESTATUTO-GENERAL-2023.pdf">Estatuto General</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/wp-content/uploads/2023/02/REGLAMENTO-ESTUDIANTIL.-UNICLARETIANA.pdf">Reglamento Estudiantil</a></li>
                        <li><a href="#">Protección de Datos</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>U-VIRTUAL</h4>
                    <ul>
                        <li><a href="https://sga.claretiano.edu.br/sav/uniclaretiana">Aula Virtual</a></li>
                        <li><a href="https://iceberg-cloud.casewaresa.com/cla/iceberg-pf/">Portal Financiero</a></li>
                        <li><a href="https://www.uniclaretiana.edu.co/certificados-constancias-duplicados/">Certificados</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-links">
                    <a href="https://www.uniclaretiana.edu.co/pqrsf/">PQRS</a>
                    <a href="https://www.uniclaretiana.edu.co/contactanos/">Contacto</a>
                </div>
                <p class="footer-text">
                    Institución Educativa Superior sujeta a inspección y vigilancia del Ministerio de Educación Nacional
                </p>
                <p class="footer-text">
                    Fundación Universitaria Claretiana - Uniclaretiana
                </p>
                <p class="copyright">
                    Copyright © 2023 SICU. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>