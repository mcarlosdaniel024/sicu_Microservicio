<?php
session_start();
require_once __DIR__ . '/../includes/db-connect.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Entrada - SICU</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --black: #000000;
            --white: #ffffff;
            --gray: #f5f5f5;
            --dark-gray: #333333;
            --accent: #EED12C;
            --blue: #3b82f6;
            --green: #10b981;
            --purple: #8b5cf6;
            --orange: #f59e0b;
            --red: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--white);
            color: var(--black);
            line-height: 1.6;
        }
        
        /* Header */
        header {
            background: var(--black);
            color: var(--white);
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-icon {
            color: var(--accent);
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -1px;
            color: var(--white);
        }
        
        .logo span {
            color: var(--accent);
        }
        
        .logo-subtitle {
            font-size: 0.875rem;
            color: #9ca3af;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 2rem;
        }
        
        nav ul li a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: var(--accent);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 0;
            position: relative;
        }
        
        .form-container {
            background: var(--white);
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin: 2rem auto;
            position: relative;
        }
        
        h1 {
            color: var(--dark-gray);
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group p {
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
            color: var(--dark-gray);
            text-align: center;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 0.8rem 0.8rem 0.8rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--black);
            color: var(--white);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid var(--black);
            cursor: pointer;
        }
        
        .btn:hover {
            background: transparent;
            color: var(--black);
        }
        
        .btn-visitante {
            background: var(--blue);
            border-color: var(--blue);
        }
        
        .btn-visitante:hover {
            background: transparent;
            color: var(--blue);
        }
        
        .error {
            color: var(--red);
            margin: 1rem 0;
            padding: 0.8rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 5px;
            border-left: 4px solid var(--red);
        }
        
        /* Icono de Home */
        .home-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 1.5rem;
            color: var(--primary);
            transition: all 0.3s;
            z-index: 10;
            background: rgba(255,255,255,0.8);
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .home-icon:hover {
            color: #000;
            transform: scale(1.1);
            background: rgba(255,255,255,0.9);
        }
        
        /* Footer */
        footer {
            background: var(--black);
            color: var(--white);
            padding: 3rem 0 1rem;
        }
        
        .footer-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .footer-logo-text {
            font-size: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .footer-columns {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-column h4 {
            color: var(--white);
            border-bottom: 2px solid var(--accent);
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .footer-column ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-column li {
            margin-bottom: 8px;
        }
        
        .footer-column a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column a:hover {
            color: var(--accent);
        }
        
        .footer-bottom {
            padding: 1.5rem 0;
            border-top: 1px solid #374151;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 1rem;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: 600;
        }
        
        .footer-links a:hover {
            color: var(--accent);
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            nav ul {
                margin-top: 0;
            }
            
            nav ul li {
                margin: 0 0.5rem;
            }
            
            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .button-group {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .btn {
                width: 100%;
                margin: 0;
            }
            
            .home-icon {
                top: 15px;
                left: 15px;
                font-size: 1.2rem;
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo-container">
                <i data-lucide="building-2" class="logo-icon" width="40" height="40"></i>
                <div>
                    <div class="logo">SICU<span>CLARETIANO</span></div>
                    <div class="logo-subtitle">Sistema Inteligente de Control Universitario</div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="../principal.php">Inicio</a></li>
                    <li><a href="#acceso">Accesos</a></li>
                    <li><a href="https://www.uniclaretiana.edu.co/contactanos/">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="main-content">
        <!-- Icono de Home -->
        <a href="../principal.php" class="home-icon" aria-label="Volver al inicio" title="Inicio">
            <i data-lucide="home" width="20" height="20"></i>
        </a>
        
        <div class="form-container">
            <h1>Registro de Entrada</h1>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="procesar.php" method="POST">
                <div class="form-group">
                    <p>¿Eres parte de la familia UNICLARETIANA?</p>
                    <div class="input-wrapper">
                        <i data-lucide="user" width="18" height="18" class="input-icon"></i>
                        <input type="text" name="codigo" placeholder="Ingresa tu código de acceso" required>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="tipo" value="miembro" class="btn">
                        <i data-lucide="user-check" width="18" height="18"></i>
                        Soy Miembro
                    </button>
                    <button type="submit" name="tipo" value="visitante" class="btn btn-visitante">
                        <i data-lucide="users" width="18" height="18"></i>
                        Soy Visitante
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-logo">
                <span class="footer-logo-text">
                    <i data-lucide="building-2" width="48" height="48" style="color: var(--accent);"></i>
                    UNICLARETIANA
                </span>
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
                <p class="footer-text" style="color: #4b5563;">
                    Copyright © 2023 SICU. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>