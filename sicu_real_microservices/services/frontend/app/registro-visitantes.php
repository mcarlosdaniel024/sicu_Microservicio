<?php
session_start();
require_once 'includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Procesar formulario
    $nombre = $_POST['nombre'] ?? '';
    $documento = $_POST['documento'] ?? '';
    $email = $_POST['email'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $fecha_visita = $_POST['fecha_visita'] ?? '';
    $hora_entrada = $_POST['hora_entrada'] ?? '';

    // Validar datos
    if (
        empty($nombre) ||
        empty($documento) ||
        empty($email) ||
        empty($motivo)
    ) {

        $error = "¡Faltan datos obligatorios!";

    } else {

        // Insertar en BD
        $stmt = $conn->prepare("
            INSERT INTO visitantes 
            (documento, motivo_visita, fecha_visita, hora_entrada, usuario_id, estado) 
            VALUES (?, ?, ?, ?, ?, 'pendiente')
        ");

        // usuario temporal
        $usuario_id = null;

        $stmt->bind_param(
            "ssssi",
            $documento,
            $motivo,
            $fecha_visita,
            $hora_entrada,
            $usuario_id
        );

        if ($stmt->execute()) {

            $_SESSION['registro_exitoso'] = true;

            header("Location: registro-exitoso.php");
            exit();

        } else {

            $error = "Error al registrar: " . $conn->error;

        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Visitantes - SICU</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .form-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80') center/cover no-repeat;
            color: var(--white);
            padding: 3rem 0;
            text-align: center;
        }
        
        .form-container {
            max-width: 600px;
        }
        
        .form-icon {
            width: 80px;
            height: 80px;
            background: var(--blue);
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
                <i data-lucide="users" class="logo-icon" width="40" height="40"></i>
                <div>
                    <div class="logo">SICU<span>CLARETIANO</span></div>
                    <div class="logo-subtitle">Registro de Visitantes</div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="principal.php">Inicio</a></li>
                    <li><a href="login-estudiantes.php">Estudiantes</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="form-hero">
        <div class="container">
            <h1>Registro de Visitantes</h1>
            <p>Complete el formulario para solicitar su visita</p>
        </div>
    </section>

    <div class="container main-content">
        <div class="card form-container">
            <div class="form-icon">
                <i data-lucide="user-plus" width="40" height="40" stroke="white"></i>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i data-lucide="alert-circle" width="20" height="20"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="registro-visitantes.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre completo*</label>
                    <input type="text" id="nombre" name="nombre" required 
                           placeholder="Ingrese su nombre completo">
                </div>

                <div class="form-group">
                    <label for="documento">Número de documento*</label>
                    <input type="text" id="documento" name="documento" required 
                           placeholder="Ingrese su número de documento">
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico*</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="correo@ejemplo.com">
                </div>

                <div class="form-group">
                    <label for="motivo">Motivo de la visita*</label>
                    <select id="motivo" name="motivo" required>
                        <option value="">Seleccione el motivo...</option>
                        <option value="Académico">Asunto académico</option>
                        <option value="Administrativo">Trámite administrativo</option>
                        <option value="Personal">Visita personal</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_visita">Fecha de visita*</label>
                    <input type="date" id="fecha_visita" name="fecha_visita" required 
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="hora_entrada">Hora estimada de llegada*</label>
                    <input type="time" id="hora_entrada" name="hora_entrada" required>
                </div>

                <button type="submit" class="btn" style="width: 100%;">
                    <i data-lucide="send" width="18" height="18"></i>
                    Registrar Visita
                </button>
            </form>
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