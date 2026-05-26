-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2025 a las 00:49:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sicu_db`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_entrada_visitante` (IN `p_visitante_id` INT, IN `p_usuario_id` INT)   BEGIN
    UPDATE visitantes 
    SET fecha_hora_entrada = NOW(),
        hora_entrada = TIME(NOW()),
        usuario_registra_entrada = p_usuario_id,
        estado = 'aprobado'
    WHERE id = p_visitante_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_salida_visitante` (IN `p_visitante_id` INT, IN `p_usuario_id` INT)   BEGIN
    UPDATE visitantes 
    SET fecha_hora_salida = NOW(),
        hora_salida = TIME(NOW()),
        usuario_registra_salida = p_usuario_id
    WHERE id = p_visitante_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda_visitantes`
--

CREATE TABLE `agenda_visitantes` (
  `id` int(11) NOT NULL,
  `visitante_id` int(11) DEFAULT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_eventos`
--

CREATE TABLE `asistencia_eventos` (
  `id` int(11) NOT NULL,
  `evento_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_hora_registro` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `tabla_afectada` varchar(50) NOT NULL,
  `accion` varchar(20) NOT NULL COMMENT 'INSERT, UPDATE, DELETE, ENTRADA, SALIDA',
  `id_registro` int(11) NOT NULL,
  `datos_anteriores` text DEFAULT NULL COMMENT 'JSON con los datos antes del cambio',
  `datos_nuevos` text DEFAULT NULL COMMENT 'JSON con los datos después del cambio',
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Usuario que realizó el cambio',
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `tabla_afectada`, `accion`, `id_registro`, `datos_anteriores`, `datos_nuevos`, `usuario_id`, `fecha_hora`) VALUES
(1, 'registros_acceso', 'INSERT', 27, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-05-31 00:35:05\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-05-31 00:35:05'),
(2, 'visitantes', 'INSERT', 21, NULL, '{\"documento\": \"1078007387\", \"codigo\": null, \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-06-20\", \"estado\": \"pendiente\"}', NULL, '2025-05-31 00:41:10'),
(3, 'visitantes', 'UPDATE', 21, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-06-20\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-06-20\"}', NULL, '2025-05-31 00:52:32'),
(4, 'registros_acceso', 'INSERT', 28, NULL, '{\"usuario_id\": 12, \"fecha_hora_entrada\": \"2025-05-31 00:57:09\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00011\"}', 12, '2025-05-31 00:57:09'),
(5, 'registros_acceso', 'INSERT', 29, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-10-24 23:11:31\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-10-24 23:11:31'),
(6, 'registros_acceso', 'INSERT', 30, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-10-24 23:19:30\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-10-24 23:19:30'),
(7, 'visitantes', 'INSERT', 22, NULL, '{\"documento\": \"168415865\", \"codigo\": null, \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-10-26\", \"estado\": \"pendiente\"}', NULL, '2025-10-24 23:21:57'),
(8, 'registros_acceso', 'INSERT', 31, NULL, '{\"usuario_id\": 12, \"fecha_hora_entrada\": \"2025-10-24 23:23:17\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00011\"}', 12, '2025-10-24 23:23:17'),
(9, 'visitantes', 'UPDATE', 22, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-10-26\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-10-26\"}', NULL, '2025-11-06 07:32:12'),
(10, 'registros_acceso', 'INSERT', 32, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-11-06 07:37:18\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-11-06 07:37:18'),
(11, 'visitantes', 'INSERT', 23, NULL, '{\"documento\": \"846884\", \"codigo\": null, \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-07\", \"estado\": \"pendiente\"}', NULL, '2025-11-06 07:51:20'),
(12, 'visitantes', 'UPDATE', 23, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-07\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-07\"}', NULL, '2025-11-11 11:28:05'),
(13, 'registros_acceso', 'INSERT', 33, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-11-12 21:20:32\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-11-12 21:20:32'),
(14, 'visitantes', 'INSERT', 24, NULL, '{\"documento\": \"123456789\", \"codigo\": null, \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-20\", \"estado\": \"pendiente\"}', NULL, '2025-11-12 21:45:54'),
(15, 'visitantes', 'UPDATE', 24, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-20\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Personal\", \"fecha_visita\": \"2025-11-20\"}', NULL, '2025-11-12 21:46:41'),
(16, 'visitantes', 'INSERT', 25, NULL, '{\"documento\": \"76548\", \"codigo\": null, \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-11-16\", \"estado\": \"pendiente\"}', NULL, '2025-11-12 22:41:22'),
(17, 'visitantes', 'UPDATE', 25, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-11-16\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Administrativo\", \"fecha_visita\": \"2025-11-16\"}', NULL, '2025-11-12 22:41:40'),
(18, 'visitantes', 'INSERT', 26, NULL, '{\"documento\": \"44851528\", \"codigo\": null, \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-13\", \"estado\": \"pendiente\"}', NULL, '2025-11-12 22:54:56'),
(19, 'visitantes', 'UPDATE', 26, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-13\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-13\"}', NULL, '2025-11-12 22:55:18'),
(20, 'registros_acceso', 'INSERT', 34, NULL, '{\"usuario_id\": 12, \"fecha_hora_entrada\": \"2025-11-12 22:56:07\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00011\"}', 12, '2025-11-12 22:56:07'),
(21, 'registros_acceso', 'INSERT', 35, NULL, '{\"usuario_id\": 7, \"fecha_hora_entrada\": \"2025-11-13 08:01:00\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00007\"}', 7, '2025-11-13 08:01:00'),
(22, 'visitantes', 'INSERT', 27, NULL, '{\"documento\": \"1516182\", \"codigo\": null, \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-14\", \"estado\": \"pendiente\"}', NULL, '2025-11-13 09:00:04'),
(23, 'visitantes', 'UPDATE', 27, '{\"estado\": \"pendiente\", \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-14\"}', '{\"estado\": \"aprobado\", \"motivo_visita\": \"Académico\", \"fecha_visita\": \"2025-11-14\"}', NULL, '2025-11-13 09:00:28'),
(24, 'registros_acceso', 'INSERT', 36, NULL, '{\"usuario_id\": 12, \"fecha_hora_entrada\": \"2025-11-13 09:16:47\", \"fecha_hora_salida\": null, \"tipo_acceso\": \"entrada\", \"codigo_usado\": \"USR00011\"}', 12, '2025-11-13 09:16:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directivos`
--

CREATE TABLE `directivos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo_empleado` varchar(20) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `nivel_acceso` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo_estudiante` varchar(20) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `semestre` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `usuario_id`, `codigo_estudiante`, `carrera`, `semestre`, `created_at`, `updated_at`) VALUES
(2, 7, '202310001', 'Ingeniería de Sistemas', 5, '2025-05-27 05:26:16', '2025-05-27 05:26:16'),
(3, 8, '202310002', 'Ingeniería Industrial', 3, '2025-05-27 16:38:36', '2025-05-27 16:38:36'),
(4, 10, '202310003', 'Ingeniería de Sistemas', 1, '2025-05-30 04:08:03', '2025-05-30 04:08:03'),
(5, 13, '202310004', 'Psicología', 2, '2025-05-30 07:02:03', '2025-05-30 07:02:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `organizador` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_especiales`
--

CREATE TABLE `permisos_especiales` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `estado` enum('aprobado','rechazado','pendiente') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_administrativo`
--

CREATE TABLE `personal_administrativo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo_empleado` varchar(20) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal_administrativo`
--

INSERT INTO `personal_administrativo` (`id`, `usuario_id`, `codigo_empleado`, `departamento`, `cargo`, `created_at`, `updated_at`) VALUES
(2, 5, 'EMP-ADMIN-001', 'Administración', 'Administrador Principal', '2025-05-27 04:19:23', '2025-05-27 04:19:23'),
(3, 6, '1077997669', 'Administración', 'Administrador Principal', '2025-05-27 04:29:56', '2025-05-27 04:29:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo_empleado` varchar(20) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `usuario_id`, `codigo_empleado`, `departamento`, `titulo`, `created_at`, `updated_at`) VALUES
(2, 11, 'EMP-PROF-002', 'Ingeniería de Sistemas', 'Magíster en Educación', '2025-05-30 05:12:31', '2025-05-30 05:12:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_acceso`
--

CREATE TABLE `registros_acceso` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_hora_entrada` datetime NOT NULL,
  `fecha_hora_salida` datetime DEFAULT NULL,
  `tipo_acceso` enum('entrada','salida') NOT NULL,
  `codigo_usado` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros_acceso`
--

INSERT INTO `registros_acceso` (`id`, `usuario_id`, `fecha_hora_entrada`, `fecha_hora_salida`, `tipo_acceso`, `codigo_usado`, `created_at`, `updated_at`) VALUES
(1, 7, '2025-05-27 01:50:47', '2025-05-27 02:21:50', 'entrada', 'USR00007', '2025-05-27 06:50:47', '2025-05-27 07:21:50'),
(2, 7, '2025-05-27 01:50:58', '2025-05-27 02:21:05', 'entrada', 'USR00007', '2025-05-27 06:50:58', '2025-05-27 07:21:05'),
(3, 7, '2025-05-27 11:02:49', '2025-05-27 11:07:15', 'entrada', 'USR00007', '2025-05-27 16:02:49', '2025-05-27 16:07:15'),
(4, 8, '2025-05-27 11:38:47', '2025-05-27 11:39:15', 'entrada', 'USR00008', '2025-05-27 16:38:47', '2025-05-27 16:39:15'),
(5, 7, '2025-05-27 11:40:44', '2025-05-27 11:41:21', 'entrada', 'USR00007', '2025-05-27 16:40:44', '2025-05-27 16:41:21'),
(6, 8, '2025-05-27 11:42:16', '2025-05-27 11:42:34', 'entrada', 'USR00008', '2025-05-27 16:42:16', '2025-05-27 16:42:34'),
(7, 7, '2025-05-27 19:48:10', '2025-05-29 23:27:04', 'salida', 'USR00007', '2025-05-28 00:48:10', '2025-05-30 04:27:04'),
(8, 8, '2025-05-27 19:49:41', '2025-05-29 12:23:49', 'entrada', 'USR00008', '2025-05-28 00:49:41', '2025-05-29 17:23:49'),
(9, 7, '2025-05-27 19:50:07', '2025-05-29 23:15:01', 'salida', 'USR00007', '2025-05-28 00:50:07', '2025-05-30 04:15:01'),
(10, 7, '2025-05-27 19:51:51', '2025-05-28 16:49:36', 'entrada', 'USR00007', '2025-05-28 00:51:51', '2025-05-28 21:49:36'),
(11, 7, '2025-05-28 16:29:24', '2025-05-28 16:30:21', 'entrada', 'USR00007', '2025-05-28 21:29:24', '2025-05-28 21:30:21'),
(12, 7, '2025-05-28 22:40:53', '2025-05-29 23:12:34', 'salida', 'USR00007', '2025-05-29 03:40:53', '2025-05-30 04:12:34'),
(13, 9, '2025-05-29 21:59:03', '2025-05-29 21:59:11', 'entrada', '656865', '2025-05-30 02:59:03', '2025-05-30 02:59:11'),
(14, NULL, '2025-05-30 14:00:00', '2025-05-29 22:14:59', 'salida', 'VIS86711uez', '2025-05-30 03:14:59', '2025-05-30 03:14:59'),
(15, 10, '2025-05-29 22:20:39', '2025-05-29 23:04:21', 'salida', '324356343', '2025-05-30 03:20:39', '2025-05-30 04:04:21'),
(16, 11, '2025-05-29 23:08:59', '2025-05-29 23:09:36', 'salida', 'USR00010', '2025-05-30 04:08:59', '2025-05-30 04:09:36'),
(17, 6, '2025-05-29 23:34:31', '2025-05-29 23:34:49', 'salida', 'USR00006', '2025-05-30 04:34:31', '2025-05-30 04:34:49'),
(18, 12, '2025-05-30 00:13:12', '2025-05-30 00:13:36', 'salida', 'USR00011', '2025-05-30 05:13:12', '2025-05-30 05:13:36'),
(19, 7, '2025-05-30 00:51:34', '2025-05-30 00:52:16', 'salida', 'USR00007', '2025-05-30 05:51:34', '2025-05-30 05:52:16'),
(20, 12, '2025-05-30 01:52:07', '2025-05-30 01:55:54', 'salida', 'USR00011', '2025-05-30 06:52:07', '2025-05-30 06:55:54'),
(21, 12, '2025-05-30 01:53:16', '2025-05-30 01:53:23', 'salida', 'USR00011', '2025-05-30 06:53:16', '2025-05-30 06:53:23'),
(22, 13, '2025-05-30 02:02:34', '2025-05-30 02:02:43', 'salida', 'USR00012', '2025-05-30 07:02:34', '2025-05-30 07:02:43'),
(23, 7, '2025-05-30 03:15:17', '2025-05-30 03:16:05', 'salida', 'USR00007', '2025-05-30 08:15:17', '2025-05-30 08:16:05'),
(24, 12, '2025-05-30 20:56:30', '2025-05-30 20:58:38', 'salida', 'USR00011', '2025-05-31 01:56:30', '2025-05-31 01:58:38'),
(25, 7, '2025-05-30 23:20:51', '2025-05-30 23:23:44', 'salida', 'USR00007', '2025-05-31 04:20:51', '2025-05-31 04:23:44'),
(26, 7, '2025-05-30 23:44:31', '2025-05-30 23:44:43', 'salida', 'USR00007', '2025-05-31 04:44:31', '2025-05-31 04:44:43'),
(27, 7, '2025-05-31 00:35:05', '2025-05-31 00:35:41', 'salida', 'USR00007', '2025-05-31 05:35:05', '2025-05-31 05:35:41'),
(28, 12, '2025-05-31 00:57:09', '2025-05-31 01:01:15', 'salida', 'USR00011', '2025-05-31 05:57:09', '2025-05-31 06:01:15'),
(29, 7, '2025-10-24 23:11:31', '2025-10-24 23:14:56', 'salida', 'USR00007', '2025-10-25 04:11:31', '2025-10-25 04:14:56'),
(30, 7, '2025-10-24 23:19:30', '2025-10-24 23:19:38', 'salida', 'USR00007', '2025-10-25 04:19:30', '2025-10-25 04:19:38'),
(31, 12, '2025-10-24 23:23:17', '2025-10-24 23:24:18', 'salida', 'USR00011', '2025-10-25 04:23:17', '2025-10-25 04:24:18'),
(32, 7, '2025-11-06 07:37:18', '2025-11-06 07:37:29', 'salida', 'USR00007', '2025-11-06 12:37:18', '2025-11-06 12:37:29'),
(33, 7, '2025-11-12 21:20:32', '2025-11-12 21:20:39', 'salida', 'USR00007', '2025-11-13 02:20:32', '2025-11-13 02:20:39'),
(34, 12, '2025-11-12 22:56:07', '2025-11-12 22:56:15', 'salida', 'USR00011', '2025-11-13 03:56:07', '2025-11-13 03:56:15'),
(35, 7, '2025-11-13 08:01:00', '2025-11-13 08:01:06', 'salida', 'USR00007', '2025-11-13 13:01:00', '2025-11-13 13:01:06'),
(36, 12, '2025-11-13 09:16:47', '2025-11-13 09:19:57', 'salida', 'USR00011', '2025-11-13 14:16:47', '2025-11-13 14:19:57');

--
-- Disparadores `registros_acceso`
--
DELIMITER $$
CREATE TRIGGER `registros_acceso_after_insert` AFTER INSERT ON `registros_acceso` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla_afectada, accion, id_registro, datos_nuevos, usuario_id)
    VALUES ('registros_acceso', 'INSERT', NEW.id, 
           JSON_OBJECT(
               'usuario_id', NEW.usuario_id,
               'fecha_hora_entrada', NEW.fecha_hora_entrada,
               'fecha_hora_salida', NEW.fecha_hora_salida,
               'tipo_acceso', NEW.tipo_acceso,
               'codigo_usado', NEW.codigo_usado
           ), NEW.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('estudiante','profesor','visitante','admin','directivo','personal_administrativo') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `codigo`, `password`, `rol`, `created_at`, `updated_at`) VALUES
(5, 'AdminSeguro', 'Apellido', 'admin@sicu.com', 'USR00005', 'dac0db5c2ff486a70b0d767663baac29b3a3ebd7caf4539deaeaf7ca7cf6d2b0', 'admin', '2025-05-27 04:19:23', '2025-05-27 05:58:04'),
(6, 'Admin', 'Admin', 'admin', 'USR00006', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin', '2025-05-27 04:29:56', '2025-05-29 04:31:38'),
(7, 'Diego', 'Peralta', 'diego@uniclaretiana.edu.co', 'USR00007', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'estudiante', '2025-05-27 05:26:16', '2025-05-27 05:58:04'),
(8, 'Laura', 'Gómez', 'laura.gomez@correo.com', 'USR00008', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'estudiante', '2025-05-27 16:38:36', '2025-05-27 16:38:36'),
(9, 'Diego', 'Gomez', 'diego@gmail.com', '656865', '1234\r\n', '', '2025-05-30 02:53:58', '2025-05-30 02:57:32'),
(10, 'camilo', 'lopez', 'camilo@gmail.com', '324356343', '1234', 'estudiante', '2025-05-30 03:20:07', '2025-05-30 03:20:07'),
(11, 'Carlos', 'Ramírez', 'carlos.ramirez@correo.com', 'USR00010', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'estudiante', '2025-05-30 04:08:03', '2025-05-30 04:08:03'),
(12, 'Luis', 'Martínez', 'luis.martinez@uniclaretiana.edu.co', 'USR00011', 'cffa965d9faa1d453f2d336294b029a7f84f485f75ce2a2c723065453b12b03b', 'profesor', '2025-05-30 05:12:31', '2025-05-30 05:12:31'),
(13, 'Valentina', 'Torres', 'valentina.torres@correo.com', 'USR00012', '2e63a1090735f47213fea3b974418e3e42437325f313b3d3d2f6238cc22298f9', 'estudiante', '2025-05-30 07:02:03', '2025-05-30 07:02:03'),
(80, 'admin80', '', 'admin80@gmail.com', 'admin80', '1234', 'admin', '2025-10-25 04:29:21', '2025-10-25 04:30:04'),
(81, 'Admin', 'Principal', 'admin@universidad.edu.co', 'ADMIN001', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin', '2025-10-25 04:32:48', '2025-10-25 04:32:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitantes`
--

CREATE TABLE `visitantes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `motivo_visita` varchar(255) NOT NULL,
  `fecha_visita` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha_hora_entrada` datetime DEFAULT NULL,
  `fecha_hora_salida` datetime DEFAULT NULL,
  `usuario_registra_entrada` int(11) DEFAULT NULL,
  `usuario_registra_salida` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `visitantes`
--

INSERT INTO `visitantes` (`id`, `usuario_id`, `documento`, `codigo`, `motivo_visita`, `fecha_visita`, `hora_entrada`, `hora_salida`, `created_at`, `updated_at`, `estado`, `fecha_hora_entrada`, `fecha_hora_salida`, `usuario_registra_entrada`, `usuario_registra_salida`) VALUES
(1, NULL, '1088736863', 'VIS00001', 'Administrativo', '2026-05-05', '21:21:39', '21:21:47', '2025-05-27 04:13:23', '2025-11-13 02:21:47', 'aprobado', NULL, NULL, NULL, NULL),
(2, NULL, '68481968681', NULL, 'Administrativo', '2025-05-28', '09:00:00', NULL, '2025-05-28 01:29:05', '2025-05-28 21:00:42', 'aprobado', NULL, NULL, NULL, NULL),
(3, NULL, '4845188881', NULL, 'Personal', '2025-06-05', '10:00:00', NULL, '2025-05-28 21:02:13', '2025-05-28 21:02:27', 'rechazado', NULL, NULL, NULL, NULL),
(4, NULL, 'Rodriguez', 'VIS86711uez', 'Académico', '2025-05-30', '00:36:47', '00:38:38', '2025-05-28 21:13:43', '2025-05-31 05:38:38', 'aprobado', NULL, NULL, NULL, NULL),
(5, NULL, '148748751', NULL, 'Personal', '2025-05-28', '19:00:00', NULL, '2025-05-28 21:19:09', '2025-05-28 21:19:35', 'rechazado', NULL, NULL, NULL, NULL),
(6, NULL, '846531846', 'VIS66387846', 'Académico', '2025-05-29', '23:26:03', NULL, '2025-05-28 21:58:32', '2025-05-30 08:04:01', 'aprobado', NULL, NULL, NULL, NULL),
(7, NULL, '164494157', NULL, 'Administrativo', '2025-05-29', '11:00:00', NULL, '2025-05-29 03:40:06', '2025-05-29 03:40:19', 'rechazado', NULL, NULL, NULL, NULL),
(8, NULL, '131653816', 'VIS85057816', 'Académico', '2025-06-08', '21:45:46', NULL, '2025-05-30 02:41:39', '2025-05-30 08:04:39', 'aprobado', NULL, NULL, NULL, NULL),
(9, NULL, '7848686468', 'VIS59353468', 'Personal', '2025-06-05', '21:51:44', '03:13:43', '2025-05-30 02:47:40', '2025-05-30 08:13:43', 'aprobado', NULL, NULL, NULL, NULL),
(10, NULL, '123587594', 'VIS97868594', 'Personal', '2025-05-30', '23:14:25', NULL, '2025-05-30 04:11:51', '2025-05-30 08:04:29', 'aprobado', NULL, NULL, NULL, NULL),
(11, NULL, '261851518', 'VIS72895518', 'Administrativo', '2025-05-30', '23:46:52', NULL, '2025-05-30 04:45:28', '2025-05-30 08:04:24', 'aprobado', NULL, NULL, NULL, NULL),
(12, NULL, '168415386', 'VIS88807386', 'Administrativo', '2025-05-31', '00:54:56', NULL, '2025-05-30 05:15:00', '2025-05-30 05:54:56', 'aprobado', NULL, NULL, NULL, NULL),
(13, NULL, '516848648', 'VIS62515648', 'Personal', '2025-05-30', '21:44:34', '20:55:39', '2025-05-30 05:59:23', '2025-11-13 02:44:34', 'aprobado', NULL, NULL, NULL, NULL),
(14, NULL, '25180581', 'VIS81861581', 'Administrativo', '2025-06-20', '01:55:46', NULL, '2025-05-30 06:55:02', '2025-05-30 08:04:35', 'aprobado', NULL, NULL, NULL, NULL),
(15, NULL, '5445841846', 'VIS57642846', 'Personal', '2025-06-05', '01:58:36', NULL, '2025-05-30 06:57:48', '2025-05-30 06:58:36', 'aprobado', NULL, NULL, NULL, NULL),
(16, NULL, '548686565', 'VIS98964565', 'Administrativo', '2025-06-06', '02:08:42', NULL, '2025-05-30 07:00:02', '2025-05-30 07:08:42', 'aprobado', NULL, NULL, NULL, NULL),
(17, NULL, '62548408', 'VIS91969408', 'Personal', '2025-06-04', '02:13:31', NULL, '2025-05-30 07:13:00', '2025-05-30 07:13:31', 'aprobado', NULL, NULL, NULL, NULL),
(18, NULL, '515183513156', 'VIS67281156', 'Personal', '2025-05-30', '08:04:39', '03:18:53', '2025-05-30 08:17:15', '2025-05-30 13:04:39', 'aprobado', NULL, NULL, NULL, NULL),
(19, NULL, '86844858', 'VIS15875858', 'Personal', '2025-06-20', '17:00:00', NULL, '2025-05-30 12:59:42', '2025-05-30 23:20:07', 'aprobado', NULL, NULL, NULL, NULL),
(20, NULL, '5851848515', 'VIS99035515', 'Administrativo', '2025-06-30', '08:12:17', '08:12:34', '2025-05-30 13:11:45', '2025-05-30 13:12:34', 'aprobado', NULL, NULL, NULL, NULL),
(21, NULL, '1078007387', 'VIS79308387', 'Administrativo', '2025-06-20', '00:55:47', '00:56:31', '2025-05-31 05:41:10', '2025-05-31 05:56:31', 'aprobado', NULL, NULL, NULL, NULL),
(22, NULL, '168415865', 'VIS72027865', 'Administrativo', '2025-10-26', '08:00:00', NULL, '2025-10-25 04:21:57', '2025-11-06 12:32:12', 'aprobado', NULL, NULL, NULL, NULL),
(23, NULL, '846884', 'VIS57542884', 'Personal', '2025-11-07', '11:28:23', '11:28:33', '2025-11-06 12:51:20', '2025-11-11 16:28:33', 'aprobado', NULL, NULL, NULL, NULL),
(24, NULL, '123456789', 'VIS88632789', 'Personal', '2025-11-20', '21:47:20', '21:47:28', '2025-11-13 02:45:54', '2025-11-13 02:47:28', 'aprobado', NULL, NULL, NULL, NULL),
(25, NULL, '76548', 'VIS51728548', 'Administrativo', '2025-11-16', '22:42:09', '22:42:58', '2025-11-13 03:41:22', '2025-11-13 03:42:58', 'aprobado', NULL, NULL, NULL, NULL),
(26, NULL, '44851528', 'VIS37478528', 'Académico', '2025-11-13', '22:55:36', '22:55:46', '2025-11-13 03:54:56', '2025-11-13 03:55:46', 'aprobado', NULL, NULL, NULL, NULL),
(27, NULL, '1516182', 'VIS65281182', 'Académico', '2025-11-14', '09:03:42', '09:04:12', '2025-11-13 14:00:04', '2025-11-13 14:04:12', 'aprobado', NULL, NULL, NULL, NULL);

--
-- Disparadores `visitantes`
--
DELIMITER $$
CREATE TRIGGER `visitantes_after_insert` AFTER INSERT ON `visitantes` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla_afectada, accion, id_registro, datos_nuevos, usuario_id)
    VALUES ('visitantes', 'INSERT', NEW.id, 
           JSON_OBJECT(
               'documento', NEW.documento,
               'codigo', NEW.codigo,
               'motivo_visita', NEW.motivo_visita,
               'fecha_visita', NEW.fecha_visita,
               'estado', NEW.estado
           ), NEW.usuario_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `visitantes_after_update` AFTER UPDATE ON `visitantes` FOR EACH ROW BEGIN
    -- Registrar entrada
    IF NEW.fecha_hora_entrada IS NOT NULL AND (OLD.fecha_hora_entrada IS NULL OR NEW.fecha_hora_entrada != OLD.fecha_hora_entrada) THEN
        INSERT INTO auditoria (tabla_afectada, accion, id_registro, datos_nuevos, usuario_id)
        VALUES ('visitantes', 'ENTRADA', NEW.id, 
               JSON_OBJECT(
                   'documento', NEW.documento,
                   'codigo', NEW.codigo,
                   'fecha_hora_entrada', NEW.fecha_hora_entrada,
                   'usuario_registra_entrada', NEW.usuario_registra_entrada,
                   'estado', NEW.estado
               ), NEW.usuario_registra_entrada);
    END IF;
    
    -- Registrar salida
    IF NEW.fecha_hora_salida IS NOT NULL AND (OLD.fecha_hora_salida IS NULL OR NEW.fecha_hora_salida != OLD.fecha_hora_salida) THEN
        INSERT INTO auditoria (tabla_afectada, accion, id_registro, datos_nuevos, usuario_id)
        VALUES ('visitantes', 'SALIDA', NEW.id, 
               JSON_OBJECT(
                   'documento', NEW.documento,
                   'codigo', NEW.codigo,
                   'fecha_hora_salida', NEW.fecha_hora_salida,
                   'usuario_registra_salida', NEW.usuario_registra_salida,
                   'tiempo_visita', TIMESTAMPDIFF(MINUTE, NEW.fecha_hora_entrada, NEW.fecha_hora_salida)
               ), NEW.usuario_registra_salida);
    END IF;
    
    -- Registrar cambios generales
    IF NEW.estado != OLD.estado OR NEW.motivo_visita != OLD.motivo_visita THEN
        INSERT INTO auditoria (tabla_afectada, accion, id_registro, datos_anteriores, datos_nuevos, usuario_id)
        VALUES ('visitantes', 'UPDATE', NEW.id, 
               JSON_OBJECT(
                   'estado', OLD.estado,
                   'motivo_visita', OLD.motivo_visita,
                   'fecha_visita', OLD.fecha_visita
               ),
               JSON_OBJECT(
                   'estado', NEW.estado,
                   'motivo_visita', NEW.motivo_visita,
                   'fecha_visita', NEW.fecha_visita
               ), NEW.usuario_id);
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda_visitantes`
--
ALTER TABLE `agenda_visitantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitante_id` (`visitante_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indices de la tabla `asistencia_eventos`
--
ALTER TABLE `asistencia_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabla_afectada` (`tabla_afectada`),
  ADD KEY `fecha_hora` (`fecha_hora`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `directivos`
--
ALTER TABLE `directivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_empleado` (`codigo_empleado`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_estudiante` (`codigo_estudiante`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos_especiales`
--
ALTER TABLE `permisos_especiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `personal_administrativo`
--
ALTER TABLE `personal_administrativo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_empleado` (`codigo_empleado`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_empleado` (`codigo_empleado`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `registros_acceso`
--
ALTER TABLE `registros_acceso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `visitantes`
--
ALTER TABLE `visitantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_visitante_usuario_entrada` (`usuario_registra_entrada`),
  ADD KEY `fk_visitante_usuario_salida` (`usuario_registra_salida`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda_visitantes`
--
ALTER TABLE `agenda_visitantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_eventos`
--
ALTER TABLE `asistencia_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `directivos`
--
ALTER TABLE `directivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos_especiales`
--
ALTER TABLE `permisos_especiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_administrativo`
--
ALTER TABLE `personal_administrativo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `registros_acceso`
--
ALTER TABLE `registros_acceso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `visitantes`
--
ALTER TABLE `visitantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda_visitantes`
--
ALTER TABLE `agenda_visitantes`
  ADD CONSTRAINT `agenda_visitantes_ibfk_1` FOREIGN KEY (`visitante_id`) REFERENCES `visitantes` (`id`),
  ADD CONSTRAINT `agenda_visitantes_ibfk_2` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`);

--
-- Filtros para la tabla `asistencia_eventos`
--
ALTER TABLE `asistencia_eventos`
  ADD CONSTRAINT `asistencia_eventos_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`),
  ADD CONSTRAINT `asistencia_eventos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `directivos`
--
ALTER TABLE `directivos`
  ADD CONSTRAINT `directivos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `permisos_especiales`
--
ALTER TABLE `permisos_especiales`
  ADD CONSTRAINT `permisos_especiales_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `personal_administrativo`
--
ALTER TABLE `personal_administrativo`
  ADD CONSTRAINT `personal_administrativo_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `registros_acceso`
--
ALTER TABLE `registros_acceso`
  ADD CONSTRAINT `registros_acceso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `visitantes`
--
ALTER TABLE `visitantes`
  ADD CONSTRAINT `fk_visitante_usuario_entrada` FOREIGN KEY (`usuario_registra_entrada`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_visitante_usuario_salida` FOREIGN KEY (`usuario_registra_salida`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `visitantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
