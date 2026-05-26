<?php
session_start();
if (!isset($_SESSION['mensaje_exito']) || !isset($_SESSION['registro_salida'])) {
    header("Location: index.php");
    exit();
}

$mensaje = $_SESSION['mensaje_exito'];
$registro = $_SESSION['registro_salida'];
unset($_SESSION['mensaje_exito']);
unset($_SESSION['registro_salida']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - SICU</title>
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
        }
        
        .success-container {
            background: var(--white);
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            margin: 2rem auto;
            border-left: 4px solid var(--green);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #d1fae5;
            color: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        h1 {
            color: var(--dark-gray);
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        p {
            color: var(--dark-gray);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .info-container {
            background: var(--gray);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .info-value {
            color: #6b7280;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--black);
            color: var(--white);
            padding: 0.8rem 2rem;
            border: 2px solid var(--black);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: transparent;
            color: var(--black);
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
            
            .success-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .success-icon {
                width: 60px;
                height: 60px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .info-row {
                flex-direction: column;
                gap: 0.3rem;
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
        <div class="success-container">
            <div class="success-icon">
                <i data-lucide="check" width="40" height="40"></i>
            </div>
            <h1><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></h1>
            <p>El registro de salida se completó correctamente.</p>
            
            <div class="info-container">
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Tipo:</span>
                    <span class="info-value"><?= htmlspecialchars(ucfirst($registro['tipo']), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                
                <?php if ($registro['codigo']): ?>
                <div class="info-row">
                    <span class="info-label">Código:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['codigo'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($registro['documento']): ?>
                <div class="info-row">
                    <span class="info-label">Documento:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['documento'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="info-row">
                    <span class="info-label">Motivo:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['motivo'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Hora de entrada:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['hora_entrada'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Hora de salida:</span>
                    <span class="info-value"><?= htmlspecialchars($registro['hora_salida'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
            
            <a href="index.php" class="btn">
                <i data-lucide="log-out" width="18" height="18"></i>
                Registrar otra salida
            </a>
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