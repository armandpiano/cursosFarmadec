-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2025 a las 22:42:55
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmadec_lms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attempts`
--

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `passed` tinyint(1) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capsules`
--

CREATE TABLE `capsules` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` text COLLATE utf8mb4_unicode_ci,
  `thumb_url` text COLLATE utf8mb4_unicode_ci,
  `page_order` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `capsules`
--

INSERT INTO `capsules` (`id`, `module_id`, `number`, `title`, `description`, `video_url`, `thumb_url`, `page_order`, `created_at`) VALUES
(1, 1, 1, 'Historia de la Farmacia', '<p>En esta cápsula exploraremos la evolución histórica de la farmacia desde sus orígenes hasta la actualidad.</p><h3>Objetivos de aprendizaje:</h3><ul><li>Conocer los orígenes de la farmacia</li><li>Identificar hitos importantes</li><li>Comprender la evolución científica</li></ul>', '/cursosFarmadec/videos/capsulas/capsula1.mp4', '/cursosFarmadec/img/modulos/modulo1.webp', 1, '2025-11-11 17:06:39'),
(2, 1, 2, 'Conceptos Fundamentales', '<p>Aprenderemos los conceptos básicos que todo profesional farmacéutico debe conocer.</p><h3>Temas clave:</h3><ul><li>Definición de fármaco</li><li>Medicamento vs droga</li><li>Formas farmacéuticas</li></ul>', '/cursosFarmadec/videos/capsulas/capsula2.mp4', '/cursosFarmadec/public/assets/img/cap1-2.jpg', 2, '2025-11-11 17:06:39'),
(3, 1, 3, 'Legislación Farmacéutica', '<p>Marco legal que regula la práctica farmacéutica y la comercialización de medicamentos.</p><h3>Contenido:</h3><ul><li>Normativas nacionales</li><li>Regulaciones internacionales</li><li>Responsabilidades profesionales</li></ul>', '/cursosFarmadec/videos/capsulas/capsula3.mp4', '/cursosFarmadec/public/assets/img/cap1-3.jpg', 3, '2025-11-11 17:06:39'),
(4, 1, 4, 'Principios Activos', '<p>Estudio detallado de los componentes activos de los medicamentos.</p><h3>Aprenderás:</h3><ul><li>Definición de principio activo</li><li>Clasificación farmacológica</li><li>Mecanismos de acción</li></ul>', '/cursosFarmadec/videos/capsulas/capsula4.mp4', '/cursosFarmadec/public/assets/img/cap2-1.jpg', 4, '2025-11-11 17:06:39'),
(5, 2, 2, 'Excipientes Farmacéuticos', '<p>Los componentes inactivos y su importancia en la formulación.</p><h3>Contenido:</h3><ul><li>Tipos de excipientes</li><li>Funciones en la formulación</li><li>Compatibilidad química</li></ul>', '/cursosFarmadec/videos/capsulas/capsula5.mp4', '/cursosFarmadec/public/assets/img/cap2-2.jpg', 2, '2025-11-11 17:06:39'),
(6, 2, 3, 'Interacciones Medicamentosas', '<p>Cómo los diferentes componentes interactúan entre sí.</p><h3>Temas:</h3><ul><li>Interacciones fármaco-fármaco</li><li>Interacciones fármaco-alimento</li><li>Efectos adversos</li></ul>', '/cursosFarmadec/videos/capsulas/capsula6.mp4', '/cursosFarmadec/public/assets/img/cap2-3.jpg', 3, '2025-11-11 17:06:39'),
(7, 3, 1, 'Cálculo de Dosis', '<p>Métodos y fórmulas para calcular la dosificación correcta.</p><h3>Objetivos:</h3><ul><li>Fórmulas de dosificación</li><li>Ajuste por peso y edad</li><li>Casos prácticos</li></ul>', '/cursosFarmadec/videos/capsulas/capsula7.mp4', '/cursosFarmadec/public/assets/img/cap3-1.jpg', 1, '2025-11-11 17:06:39'),
(8, 3, 2, 'Vías de Administración', '<p>Diferentes rutas para administrar medicamentos y sus características.</p><h3>Vías principales:</h3><ul><li>Vía oral</li><li>Vía parenteral</li><li>Vías tópicas</li></ul>', '/cursosFarmadec/videos/capsulas/capsula8.mp4', '/cursosFarmadec/public/assets/img/cap3-2.jpg', 2, '2025-11-11 17:06:39'),
(9, 3, 3, 'Adherencia al Tratamiento', '<p>Estrategias para asegurar el cumplimiento terapéutico.</p><h3>Aprenderás:</h3><ul><li>Importancia de la adherencia</li><li>Barreras comunes</li><li>Estrategias de mejora</li></ul>', '/cursosFarmadec/videos/capsulas/capsula9.mp4', '/cursosFarmadec/public/assets/img/cap3-3.jpg', 3, '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pdf_path` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `slug`, `title`, `description`, `image_url`, `is_active`, `created_at`) VALUES
(1, 'farmaceutica-basica', 'Farmacéutica Básica', 'Curso introductorio sobre los fundamentos de la farmacéutica, incluyendo principios activos, dosificación y administración de medicamentos.', '/cursosFarmadec/img/cursos/curso1.jpg', 1, '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `pass_score` int(11) NOT NULL DEFAULT '70',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `exams`
--

INSERT INTO `exams` (`id`, `module_id`, `pass_score`, `is_active`, `created_at`) VALUES
(1, 1, 70, 1, '2025-11-11 17:06:39'),
(2, 2, 70, 1, '2025-11-11 17:06:39'),
(3, 3, 70, 1, '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `position` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `course_id`, `number`, `title`, `description`, `image_url`, `is_active`, `position`, `created_at`) VALUES
(1, 1, 1, 'Introducción a la Farmacéutica', 'Conceptos básicos y fundamentos de la ciencia farmacéutica.', '/cursosFarmadec/img/modulos/modulo1.webp', 1, 1, '2025-11-11 17:06:39'),
(2, 1, 2, 'Principios Activos y Excipientes', 'Estudio de los componentes de los medicamentos y su función.', '/cursosFarmadec/img/modulos/modulo2.webp', 1, 2, '2025-11-11 17:06:39'),
(3, 1, 3, 'Dosificación y Administración', 'Métodos correctos de dosificación y vías de administración.', '/cursosFarmadec/img/modulos/modulo3.webp', 1, 3, '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `options`
--

INSERT INTO `options` (`id`, `question_id`, `text`, `is_correct`, `created_at`) VALUES
(1, 1, 'Verdadero', 1, '2025-11-11 17:06:39'),
(2, 1, 'Falso', 0, '2025-11-11 17:06:39'),
(3, 2, 'Verdadero', 0, '2025-11-11 17:06:39'),
(4, 2, 'Falso', 1, '2025-11-11 17:06:39'),
(5, 3, 'Verdadero', 0, '2025-11-11 17:06:39'),
(6, 3, 'Falso', 1, '2025-11-11 17:06:39'),
(7, 4, 'Desarrollar y producir medicamentos seguros y eficaces', 1, '2025-11-11 17:06:39'),
(8, 4, 'Vender la mayor cantidad de productos posibles', 0, '2025-11-11 17:06:39'),
(9, 4, 'Prescribir tratamientos médicos', 0, '2025-11-11 17:06:39'),
(10, 5, 'Farmacéutico titulado', 1, '2025-11-11 17:06:39'),
(11, 5, 'Cualquier vendedor de farmacia', 0, '2025-11-11 17:06:39'),
(12, 5, 'El paciente directamente', 0, '2025-11-11 17:06:39'),
(13, 6, 'Tabletas', 1, '2025-11-11 17:06:39'),
(14, 6, 'Cápsulas', 1, '2025-11-11 17:06:39'),
(15, 6, 'Jarabes', 0, '2025-11-11 17:06:39'),
(16, 6, 'Inyecciones', 0, '2025-11-11 17:06:39'),
(17, 7, 'Cómo el organismo procesa el fármaco', 1, '2025-11-11 17:06:39'),
(18, 7, 'El precio de los medicamentos', 0, '2025-11-11 17:06:39'),
(19, 7, 'La publicidad farmacéutica', 0, '2025-11-11 17:06:39'),
(20, 8, 'Verdadero', 1, '2025-11-11 17:06:39'),
(21, 8, 'Falso', 0, '2025-11-11 17:06:39'),
(22, 9, 'Dispensar medicamentos', 1, '2025-11-11 17:06:39'),
(23, 9, 'Orientar al paciente', 1, '2025-11-11 17:06:39'),
(24, 9, 'Realizar diagnósticos médicos', 0, '2025-11-11 17:06:39'),
(25, 9, 'Controlar inventario', 1, '2025-11-11 17:06:39'),
(26, 10, 'La conducta profesional del farmacéutico', 1, '2025-11-11 17:06:39'),
(27, 10, 'Los precios de los medicamentos', 0, '2025-11-11 17:06:39'),
(28, 10, 'La decoración de la farmacia', 0, '2025-11-11 17:06:39'),
(29, 11, 'Verdadero', 1, '2025-11-11 17:06:39'),
(30, 11, 'Falso', 0, '2025-11-11 17:06:39'),
(31, 12, 'Verdadero', 0, '2025-11-11 17:06:39'),
(32, 12, 'Falso', 1, '2025-11-11 17:06:39'),
(33, 13, 'Componente con acción farmacológica', 1, '2025-11-11 17:06:39'),
(34, 13, 'Envase del medicamento', 0, '2025-11-11 17:06:39'),
(35, 13, 'Nombre comercial del producto', 0, '2025-11-11 17:06:39'),
(36, 14, 'Facilitar la formulación y administración', 1, '2025-11-11 17:06:39'),
(37, 14, 'Aumentar el precio del medicamento', 0, '2025-11-11 17:06:39'),
(38, 14, 'Decorar el producto', 0, '2025-11-11 17:06:39'),
(39, 15, 'Aumentar, disminuir o modificar el efecto de los fármacos', 1, '2025-11-11 17:06:39'),
(40, 15, 'Solo mejorar el sabor', 0, '2025-11-11 17:06:39'),
(41, 15, 'No afectan en absoluto', 0, '2025-11-11 17:06:39'),
(42, 16, 'Aglutinantes', 1, '2025-11-11 17:06:39'),
(43, 16, 'Diluyentes', 1, '2025-11-11 17:06:39'),
(44, 16, 'Conservantes', 1, '2025-11-11 17:06:39'),
(45, 16, 'Principios activos', 0, '2025-11-11 17:06:39'),
(46, 17, 'Verdadero', 0, '2025-11-11 17:06:39'),
(47, 17, 'Falso', 1, '2025-11-11 17:06:39'),
(48, 18, 'pH del medio', 1, '2025-11-11 17:06:39'),
(49, 18, 'Forma farmacéutica', 1, '2025-11-11 17:06:39'),
(50, 18, 'Presencia de alimentos', 1, '2025-11-11 17:06:39'),
(51, 18, 'Color del envase', 0, '2025-11-11 17:06:39'),
(52, 19, 'La cantidad de fármaco que llega a la circulación sistémica', 1, '2025-11-11 17:06:39'),
(53, 19, 'El precio del medicamento', 0, '2025-11-11 17:06:39'),
(54, 19, 'La fecha de vencimiento', 0, '2025-11-11 17:06:39'),
(55, 20, 'Verdadero', 1, '2025-11-11 17:06:39'),
(56, 20, 'Falso', 0, '2025-11-11 17:06:39'),
(57, 21, 'Verdadero', 0, '2025-11-11 17:06:39'),
(58, 21, 'Falso', 1, '2025-11-11 17:06:39'),
(59, 22, 'Verdadero', 1, '2025-11-11 17:06:39'),
(60, 22, 'Falso', 0, '2025-11-11 17:06:39'),
(61, 23, 'Peso del paciente', 1, '2025-11-11 17:06:39'),
(62, 23, 'Edad', 1, '2025-11-11 17:06:39'),
(63, 23, 'Función renal y hepática', 1, '2025-11-11 17:06:39'),
(64, 23, 'Color de cabello', 0, '2025-11-11 17:06:39'),
(65, 24, 'Efecto inmediato y biodisponibilidad del 100%', 1, '2025-11-11 17:06:39'),
(66, 24, 'Es más económica', 0, '2025-11-11 17:06:39'),
(67, 24, 'No requiere personal capacitado', 0, '2025-11-11 17:06:39'),
(68, 25, 'El cumplimiento del paciente con el tratamiento prescrito', 1, '2025-11-11 17:06:39'),
(69, 25, 'El costo del medicamento', 0, '2025-11-11 17:06:39'),
(70, 25, 'La publicidad del producto', 0, '2025-11-11 17:06:39'),
(71, 26, 'Intravenosa', 1, '2025-11-11 17:06:39'),
(72, 26, 'Intramuscular', 1, '2025-11-11 17:06:39'),
(73, 26, 'Subcutánea', 1, '2025-11-11 17:06:39'),
(74, 26, 'Oral', 0, '2025-11-11 17:06:39'),
(75, 27, 'Verdadero', 0, '2025-11-11 17:06:39'),
(76, 27, 'Falso', 1, '2025-11-11 17:06:39'),
(77, 28, 'Educación al paciente', 1, '2025-11-11 17:06:39'),
(78, 28, 'Simplificar el régimen', 1, '2025-11-11 17:06:39'),
(79, 28, 'Uso de recordatorios', 1, '2025-11-11 17:06:39'),
(80, 28, 'Aumentar el precio', 0, '2025-11-11 17:06:39'),
(81, 29, 'Verdadero', 1, '2025-11-11 17:06:39'),
(82, 29, 'Falso', 0, '2025-11-11 17:06:39'),
(83, 30, 'Cuando se requiere efecto rápido sin paso hepático', 1, '2025-11-11 17:06:39'),
(84, 30, 'Para cualquier medicamento', 0, '2025-11-11 17:06:39'),
(85, 30, 'Solo para vitaminas', 0, '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progress_capsules`
--

CREATE TABLE `progress_capsules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `capsule_id` int(11) NOT NULL,
  `viewed` tinyint(1) DEFAULT '0',
  `finished_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `progress_capsules`
--

INSERT INTO `progress_capsules` (`id`, `user_id`, `capsule_id`, `viewed`, `finished_at`) VALUES
(1, 3, 1, 1, '2025-11-11 17:22:07'),
(2, 3, 2, 1, '2025-11-11 17:22:10'),
(3, 4, 1, 1, '2025-11-13 21:30:27'),
(4, 4, 2, 1, '2025-11-13 21:26:18'),
(9, 4, 3, 1, '2025-11-13 21:26:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progress_modules`
--

CREATE TABLE `progress_modules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'not_started',
  `percent` tinyint(4) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `progress_modules`
--

INSERT INTO `progress_modules` (`id`, `user_id`, `module_id`, `status`, `percent`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'in_progress', 66, '2025-11-11 17:22:07', '2025-11-11 17:22:10'),
(2, 4, 1, 'in_progress', 75, '2025-11-13 16:07:09', '2025-11-13 16:12:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('true_false','single_choice','multiple_choice') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `exam_id`, `text`, `type`, `created_at`) VALUES
(1, 1, 'La farmacia tiene sus orígenes en civilizaciones antiguas como Egipto y Mesopotamia.', 'true_false', '2025-11-11 17:06:39'),
(2, 1, 'Un fármaco y un medicamento son exactamente lo mismo.', 'true_false', '2025-11-11 17:06:39'),
(3, 1, 'La legislación farmacéutica es igual en todos los países del mundo.', 'true_false', '2025-11-11 17:06:39'),
(4, 1, '¿Cuál es el objetivo principal de la farmacéutica?', 'single_choice', '2025-11-11 17:06:39'),
(5, 1, '¿Qué profesional está autorizado para dispensar medicamentos controlados?', 'single_choice', '2025-11-11 17:06:39'),
(6, 1, '¿Cuáles son formas farmacéuticas sólidas? (seleccione todas las correctas)', 'multiple_choice', '2025-11-11 17:06:39'),
(7, 1, 'La farmacocinética estudia:', 'single_choice', '2025-11-11 17:06:39'),
(8, 1, 'Las Buenas Prácticas de Manufactura (BPM) son obligatorias en la industria farmacéutica.', 'true_false', '2025-11-11 17:06:39'),
(9, 1, '¿Cuáles son responsabilidades del farmacéutico? (seleccione todas)', 'multiple_choice', '2025-11-11 17:06:39'),
(10, 1, 'El código de ética farmacéutica regula:', 'single_choice', '2025-11-11 17:06:39'),
(11, 2, 'Los principios activos son los componentes responsables del efecto terapéutico.', 'true_false', '2025-11-11 17:06:39'),
(12, 2, 'Los excipientes no tienen ninguna función importante en un medicamento.', 'true_false', '2025-11-11 17:06:39'),
(13, 2, '¿Qué es un principio activo?', 'single_choice', '2025-11-11 17:06:39'),
(14, 2, '¿Cuál es la función principal de los excipientes?', 'single_choice', '2025-11-11 17:06:39'),
(15, 2, 'Las interacciones medicamentosas pueden:', 'single_choice', '2025-11-11 17:06:39'),
(16, 2, '¿Cuáles son tipos de excipientes? (seleccione todas)', 'multiple_choice', '2025-11-11 17:06:39'),
(17, 2, 'Es seguro mezclar cualquier medicamento sin consultar al farmacéutico.', 'true_false', '2025-11-11 17:06:39'),
(18, 2, '¿Qué factores afectan la absorción de un fármaco?', 'multiple_choice', '2025-11-11 17:06:39'),
(19, 2, 'La biodisponibilidad se refiere a:', 'single_choice', '2025-11-11 17:06:39'),
(20, 2, 'Los medicamentos genéricos contienen el mismo principio activo que los de marca.', 'true_false', '2025-11-11 17:06:39'),
(21, 3, 'La dosis de un medicamento es siempre la misma para todos los pacientes.', 'true_false', '2025-11-11 17:06:39'),
(22, 3, 'La vía oral es la más común para administrar medicamentos.', 'true_false', '2025-11-11 17:06:39'),
(23, 3, '¿Qué factores se deben considerar al calcular una dosis?', 'multiple_choice', '2025-11-11 17:06:39'),
(24, 3, '¿Cuál es la ventaja de la vía intravenosa?', 'single_choice', '2025-11-11 17:06:39'),
(25, 3, 'La adherencia al tratamiento se refiere a:', 'single_choice', '2025-11-11 17:06:39'),
(26, 3, '¿Cuáles son vías parenterales? (seleccione todas)', 'multiple_choice', '2025-11-11 17:06:39'),
(27, 3, 'Es seguro ajustar la dosis de un medicamento sin consultar al médico.', 'true_false', '2025-11-11 17:06:39'),
(28, 3, '¿Qué estrategias mejoran la adherencia? (seleccione todas)', 'multiple_choice', '2025-11-11 17:06:39'),
(29, 3, 'La biodisponibilidad de un fármaco varía según la vía de administración.', 'true_false', '2025-11-11 17:06:39'),
(30, 3, '¿Cuándo está indicada la vía sublingual?', 'single_choice', '2025-11-11 17:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `google_sub` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` text COLLATE utf8mb4_unicode_ci,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `google_sub`, `email`, `name`, `avatar_url`, `role`, `created_at`, `password_hash`) VALUES
(1, 'admin123', 'admin@farmadec.com', 'Administrador Farmadec', 'https://via.placeholder.com/150', 'admin', '2025-11-11 17:06:39', NULL),
(2, 'user123', 'usuario@test.com', 'Usuario Demo', 'https://via.placeholder.com/150', 'user', '2025-11-11 17:06:39', NULL),
(3, NULL, 'leonardo@pass.com.mx', 'Leonardo Soto', NULL, 'user', '2025-11-11 17:21:35', '$2y$10$xGK/J1e8cY.JX7ZQZsNoWuyz6961lao2YsDDzGEVtVQwpI9oe3GMS'),
(4, '115331495716745964784', 'armancma777@gmail.com', 'Armando Vazquez', 'https://lh3.googleusercontent.com/a/ACg8ocJbHZQFSz08bHxH5R-szVsmmVP73ywqZAnVqXPnf9Wj8HxxiJWY=s96-c', 'user', '2025-11-12 22:25:19', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attempts_exam` (`exam_id`),
  ADD KEY `idx_user_exam` (`user_id`,`exam_id`),
  ADD KEY `idx_taken_at` (`taken_at`);

--
-- Indices de la tabla `capsules`
--
ALTER TABLE `capsules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_module` (`module_id`),
  ADD KEY `idx_order` (`page_order`);

--
-- Indices de la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `unique_user_course_cert` (`user_id`,`course_id`),
  ADD KEY `fk_certificates_course` (`course_id`),
  ADD KEY `idx_code` (`code`);

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indices de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`course_id`),
  ADD KEY `fk_enrollments_course` (`course_id`),
  ADD KEY `idx_user_course` (`user_id`,`course_id`);

--
-- Indices de la tabla `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_module` (`module_id`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_position` (`position`);

--
-- Indices de la tabla `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_question` (`question_id`);

--
-- Indices de la tabla `progress_capsules`
--
ALTER TABLE `progress_capsules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_capsule` (`user_id`,`capsule_id`),
  ADD KEY `fk_progress_capsules_capsule` (`capsule_id`),
  ADD KEY `idx_user_capsule` (`user_id`,`capsule_id`);

--
-- Indices de la tabla `progress_modules`
--
ALTER TABLE `progress_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_module` (`user_id`,`module_id`),
  ADD KEY `fk_progress_modules_module` (`module_id`),
  ADD KEY `idx_user_module` (`user_id`,`module_id`);

--
-- Indices de la tabla `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exam` (`exam_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_sub` (`google_sub`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_google_sub` (`google_sub`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_google_sub` (`google_sub`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capsules`
--
ALTER TABLE `capsules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `progress_capsules`
--
ALTER TABLE `progress_capsules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `progress_modules`
--
ALTER TABLE `progress_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `fk_attempts_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attempts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `capsules`
--
ALTER TABLE `capsules`
  ADD CONSTRAINT `fk_capsules_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `fk_certificates_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `fk_exams_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `fk_modules_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `progress_capsules`
--
ALTER TABLE `progress_capsules`
  ADD CONSTRAINT `fk_progress_capsules_capsule` FOREIGN KEY (`capsule_id`) REFERENCES `capsules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progress_capsules_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `progress_modules`
--
ALTER TABLE `progress_modules`
  ADD CONSTRAINT `fk_progress_modules_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progress_modules_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
