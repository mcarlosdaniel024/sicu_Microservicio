<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SICU - Sistema Inteligente de Control Universitario</title>
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
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1706016899218-ebe36844f70e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=1920') center/cover no-repeat;
            color: var(--white);
            padding: 6rem 0;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 2rem;
            color: #d1d5db;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: var(--black);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid var(--accent);
            cursor: pointer;
        }
        
        .btn:hover {
            background: transparent;
            color: var(--accent);
        }
        
        /* Access Options */
        .access-options {
            padding: 4rem 0;
            background: var(--gray);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.5rem;
            color: var(--black);
        }
        
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        
        .option-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid;
        }
        
        .option-card.blue { border-top-color: var(--blue); }
        .option-card.green { border-top-color: var(--green); }
        .option-card.purple { border-top-color: var(--purple); }
        .option-card.orange { border-top-color: var(--orange); }
        .option-card.red { border-top-color: var(--red); }
        
        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            text-align: center;
            padding: 2rem 1.5rem 1rem;
        }
        
        .card-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .card-icon.blue { background: var(--blue); }
        .card-icon.green { background: var(--green); }
        .card-icon.purple { background: var(--purple); }
        .card-icon.orange { background: var(--orange); }
        .card-icon.red { background: var(--red); }
        
        .card-header h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .card-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 0 2rem 2rem;
        }
        
        .card-body ul {
            list-style: none;
            margin-bottom: 1.5rem;
        }
        
        .card-body ul li {
            margin-bottom: 0.8rem;
            position: relative;
            padding-left: 1.8rem;
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }
        
        .card-body ul li i {
            color: var(--accent);
            flex-shrink: 0;
            margin-top: 0.2rem;
        }
        
        .btn-card {
            width: 100%;
            background: var(--black);
            color: var(--white);
            border: 2px solid var(--black);
        }
        
        .btn-card:hover {
            background: var(--accent);
            color: var(--black);
            border-color: var(--accent);
        }

        /* Estilos para botones restringidos */
        .btn-restricted {
            background: #6b7280 !important;
            color: #d1d5db !important;
            border: 2px solid #6b7280 !important;
            cursor: not-allowed !important;
            position: relative;
        }

        .btn-restricted:hover {
            background: #6b7280 !important;
            color: #d1d5db !important;
            border-color: #6b7280 !important;
            transform: none !important;
        }

        .lock-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
        }
        
        /* Control de Accesos Section */
        .control-accesos {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .control-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
        }
        
        .control-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .control-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }
        
        .control-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
        
        .control-card.entrada { border-left-color: var(--green); }
        .control-card.salida { border-left-color: var(--red); }
        
        .control-card-header {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .control-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .control-icon.entrada { background: #d1fae5; color: var(--green); }
        .control-icon.salida { background: #fee2e2; color: var(--red); }
        
        .btn-entrada {
            background: var(--green);
            color: var(--white);
            border: 2px solid var(--green);
        }
        
        .btn-entrada:hover {
            background: transparent;
            color: var(--green);
        }
        
        .btn-salida {
            background: var(--red);
            color: var(--white);
            border: 2px solid var(--red);
        }
        
        .btn-salida:hover {
            background: transparent;
            color: var(--red);
        }
        
        /* Stats */
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 3rem;
            padding: 1.5rem;
            background: var(--gray);
            border-radius: 50px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--black);
        }
        
        .stat-divider {
            width: 1px;
            background: #d1d5db;
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
        
        /* Floating Admin Button */
        .admin-login {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--black);
            color: var(--white);
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 1000;
        }
        
        .admin-login:hover {
            background: var(--accent);
            color: var(--black);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: var(--white);
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            overflow: hidden;
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            background: var(--black);
            color: var(--white);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .close-modal:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
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
        
        .form-group input {
            width: 100%;
            padding: 0.8rem 0.8rem 0.8rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
        }
        
        .login-error {
            display: none;
            background: #fee2e2;
            color: #991b1b;
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        
        .login-error.active {
            display: block;
        }
        
        .modal-footer {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        
        .btn-submit {
            flex: 1;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background: var(--accent);
            color: var(--black);
        }
        
        .btn-cancel {
            padding: 0.8rem 1.5rem;
            background: transparent;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: var(--gray);
        }
        
        .demo-credentials {
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 1rem;
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
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .options-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .stat-divider {
                display: none;
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
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#acceso">Accesos</a></li>
                    <li><a href="https://www.uniclaretiana.edu.co/contactanos/">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container">
            <h1>Sistema Inteligente de Control Universitario</h1>
            <p>Gestión segura y eficiente del acceso a las instalaciones universitarias para toda la comunidad educativa</p>
            <a href="#acceso" class="btn">
                Ver opciones de acceso
                <i data-lucide="arrow-right" width="20" height="20"></i>
            </a>
        </div>
    </section>
    
    <!-- Access Options -->
    <section class="access-options" id="acceso">
        <div class="container">
            <h2 class="section-title">Seleccione su perfil</h2>
            
            <div class="options-grid">
                <!-- Visitantes -->
                <div class="option-card blue">
                    <div class="card-header">
                        <div class="card-icon blue">
                            <i data-lucide="users" width="32" height="32" stroke="white"></i>
                        </div>
                        <h3>Visitantes</h3>
                        <p class="card-description">Acceso para visitantes externos</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Agendar cita previa</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Registro de datos personales</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Generación de pase temporal</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Notificación automática</span>
                            </li>
                        </ul>
                        <a href="registro-visitantes.php" class="btn btn-card">
                            <i data-lucide="log-in" width="18" height="18"></i>
                            Acceder
                        </a>
                    </div>
                </div>
                
                <!-- Estudiantes -->
                <div class="option-card green">
                    <div class="card-header">
                        <div class="card-icon green">
                            <i data-lucide="graduation-cap" width="32" height="32" stroke="white"></i>
                        </div>
                        <h3>Estudiantes</h3>
                        <p class="card-description">Portal para estudiantes activos</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Acceso con documento de identidad</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Consulta de horarios</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Registro de entradas/salidas</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Historial de accesos</span>
                            </li>
                        </ul>
                        <button class="btn btn-card btn-restricted" disabled>
                            <i data-lucide="log-in" width="18" height="18"></i>
                            Acceder
                            <i data-lucide="lock" width="16" height="16" class="lock-icon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Profesores -->
                <div class="option-card purple">
                    <div class="card-header">
                        <div class="card-icon purple">
                            <i data-lucide="briefcase" width="32" height="32" stroke="white"></i>
                        </div>
                        <h3>Profesores</h3>
                        <p class="card-description">Acceso para docentes</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Acceso prioritario</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Gestión de visitantes académicos</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Control de asistencia</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Reserva de espacios</span>
                            </li>
                        </ul>
                        <button class="btn btn-card btn-restricted" disabled>
                            <i data-lucide="log-in" width="18" height="18"></i>
                            Acceder
                            <i data-lucide="lock" width="16" height="16" class="lock-icon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Personal Administrativo -->
                <div class="option-card orange">
                    <div class="card-header">
                        <div class="card-icon orange">
                            <i data-lucide="user-cog" width="32" height="32" stroke="white"></i>
                        </div>
                        <h3>Personal Administrativo</h3>
                        <p class="card-description">Portal administrativo</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Acceso a áreas restringidas</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Registro de proveedores</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Gestión de eventos</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Reportes de actividad</span>
                            </li>
                        </ul>
                        <button class="btn btn-card btn-restricted" disabled>
                            <i data-lucide="log-in" width="18" height="18"></i>
                            Acceder
                            <i data-lucide="lock" width="16" height="16" class="lock-icon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Directivos -->
                <div class="option-card red">
                    <div class="card-header">
                        <div class="card-icon red">
                            <i data-lucide="shield" width="32" height="32" stroke="white"></i>
                        </div>
                        <h3>Directivos</h3>
                        <p class="card-description">Acceso nivel ejecutivo</p>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Acceso completo a instalaciones</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Reportes de seguridad</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Gestión de permisos especiales</span>
                            </li>
                            <li>
                                <i data-lucide="arrow-right" width="20" height="20"></i>
                                <span>Dashboard ejecutivo</span>
                            </li>
                        </ul>
                        <button class="btn btn-card btn-restricted" disabled>
                            <i data-lucide="log-in" width="18" height="18"></i>
                            Acceder
                            <i data-lucide="lock" width="16" height="16" class="lock-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Control de Accesos -->
            <div class="control-accesos">
                <h2 class="control-title">Control de Accesos</h2>
                
                <div class="control-grid">
                    <!-- Entrada -->
                    <div class="control-card entrada">
                        <div class="control-card-header">
                            <div class="control-icon entrada">
                                <i data-lucide="log-in" width="32" height="32"></i>
                            </div>
                            <h3>Registro de Entrada</h3>
                            <p class="card-description">Registra tu hora de ingreso a las instalaciones universitarias</p>
                        </div>
                        <div style="text-align: center;">
                            <a href="entrada/index.php" class="btn btn-entrada">
                                <i data-lucide="clock" width="18" height="18"></i>
                                Ingresar
                            </a>
                        </div>
                    </div>
                    
                    <!-- Salida -->
                    <div class="control-card salida">
                        <div class="control-card-header">
                            <div class="control-icon salida">
                                <i data-lucide="log-out" width="32" height="32"></i>
                            </div>
                            <h3>Registro de Salida</h3>
                            <p class="card-description">Registra tu hora de salida al finalizar tus actividades</p>
                        </div>
                        <div style="text-align: center;">
                            <a href="salida/index.php" class="btn btn-salida">
                                <i data-lucide="clock" width="18" height="18"></i>
                                Salir
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-header">
                            <i data-lucide="trending-up" width="20" height="20" style="color: var(--green);"></i>
                            <span class="stat-label">Entradas hoy</span>
                        </div>
                        <div class="stat-value" id="entradas-hoy">127</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-header">
                            <i data-lucide="trending-down" width="20" height="20" style="color: var(--red);"></i>
                            <span class="stat-label">Salidas hoy</span>
                        </div>
                        <div class="stat-value" id="salidas-hoy">98</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
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
    
    <!-- Floating Admin Button -->
    <a href="#" class="admin-login" id="openAdminModal">
        <i data-lucide="shield" width="20" height="20"></i>
        Acceso Administrador
    </a>
    
    <!-- Modal Admin -->
    <div class="modal" id="adminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Acceso Administrador</h3>
                <button class="close-modal" data-modal="adminModal">
                    <i data-lucide="x" width="24" height="24"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="admin-login.php" method="POST">
                    <div class="form-group">
                        <label for="admin-email">Email:</label>
                        <div class="input-wrapper">
                            <i data-lucide="mail" width="18" height="18" class="input-icon"></i>
                            <input type="email" id="admin-email" name="email" required placeholder="admin@uniclaretiana.edu.co">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="admin-pass">Contraseña:</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock" width="18" height="18" class="input-icon"></i>
                            <input type="password" id="admin-pass" name="contrasena" required placeholder="••••••••">
                        </div>
                    </div>
                    <div class="login-error" id="adminError">
                        Credenciales incorrectas. Por favor intente nuevamente.
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-submit">
                            <i data-lucide="key" width="18" height="18" style="display: inline; vertical-align: middle; margin-right: 8px;"></i>
                            Ingresar
                        </button>
                        <button type="button" class="btn-cancel" data-modal="adminModal">Cancelar</button>
                    </div>
                    <div class="demo-credentials">
                        Demo: admin@uniclaretiana.edu.co / admin123
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Simulación de actualización de estadísticas
        let entradas = 127;
        let salidas = 98;
        
        setInterval(() => {
            entradas += Math.floor(Math.random() * 3);
            salidas += Math.floor(Math.random() * 2);
            document.getElementById('entradas-hoy').textContent = entradas;
            document.getElementById('salidas-hoy').textContent = salidas;
        }, 5000);
        
        // Modal handlers
        document.getElementById('openAdminModal').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('adminModal').classList.add('active');
        });
        
        // Close modals
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('active');
            });
        });
        
        document.querySelectorAll('.btn-cancel').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('active');
            });
        });
        
        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });
        
        // Check for errors in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('admin_error')) {
            document.getElementById('adminModal').classList.add('active');
            document.getElementById('adminError').classList.add('active');
        }
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#' || href.startsWith('#open')) return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>