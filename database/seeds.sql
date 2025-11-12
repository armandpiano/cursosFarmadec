-- Datos de demostración para LMS Farmadec

-- Usuario administrador
INSERT INTO users (google_sub, email, name, avatar_url, role) VALUES
('admin123', 'admin@farmadec.com', 'Administrador Farmadec', 'https://via.placeholder.com/150', 'admin'),
('user123', 'usuario@test.com', 'Usuario Demo', 'https://via.placeholder.com/150', 'user');

-- Curso de demostración
INSERT INTO courses (slug, title, description, image_url, is_active) VALUES
('farmaceutica-basica', 'Farmacéutica Básica', 'Curso introductorio sobre los fundamentos de la farmacéutica, incluyendo principios activos, dosificación y administración de medicamentos.', '/cursosFarmadec/public/assets/img/curso-farmaceutica.jpg', 1);

-- Módulos del curso (3 módulos)
INSERT INTO modules (course_id, number, title, description, image_url, position) VALUES
(1, 1, 'Introducción a la Farmacéutica', 'Conceptos básicos y fundamentos de la ciencia farmacéutica.', '/cursosFarmadec/public/assets/img/modulo1.jpg', 1),
(1, 2, 'Principios Activos y Excipientes', 'Estudio de los componentes de los medicamentos y su función.', '/cursosFarmadec/public/assets/img/modulo2.jpg', 2),
(1, 3, 'Dosificación y Administración', 'Métodos correctos de dosificación y vías de administración.', '/cursosFarmadec/public/assets/img/modulo3.jpg', 3);

-- Cápsulas del módulo 1
INSERT INTO capsules (module_id, number, title, description, video_url, thumb_url, page_order) VALUES
(1, 1, 'Historia de la Farmacia', '<p>En esta cápsula exploraremos la evolución histórica de la farmacia desde sus orígenes hasta la actualidad.</p><h3>Objetivos de aprendizaje:</h3><ul><li>Conocer los orígenes de la farmacia</li><li>Identificar hitos importantes</li><li>Comprender la evolución científica</li></ul>', '/cursosFarmadec/public/assets/videos/modulo1-cap1.mp4', '/cursosFarmadec/public/assets/img/cap1-1.jpg', 1),
(1, 2, 'Conceptos Fundamentales', '<p>Aprenderemos los conceptos básicos que todo profesional farmacéutico debe conocer.</p><h3>Temas clave:</h3><ul><li>Definición de fármaco</li><li>Medicamento vs droga</li><li>Formas farmacéuticas</li></ul>', '/cursosFarmadec/public/assets/videos/modulo1-cap2.mp4', '/cursosFarmadec/public/assets/img/cap1-2.jpg', 2),
(1, 3, 'Legislación Farmacéutica', '<p>Marco legal que regula la práctica farmacéutica y la comercialización de medicamentos.</p><h3>Contenido:</h3><ul><li>Normativas nacionales</li><li>Regulaciones internacionales</li><li>Responsabilidades profesionales</li></ul>', '/cursosFarmadec/public/assets/videos/modulo1-cap3.mp4', '/cursosFarmadec/public/assets/img/cap1-3.jpg', 3);

-- Cápsulas del módulo 2
INSERT INTO capsules (module_id, number, title, description, video_url, thumb_url, page_order) VALUES
(2, 1, 'Principios Activos', '<p>Estudio detallado de los componentes activos de los medicamentos.</p><h3>Aprenderás:</h3><ul><li>Definición de principio activo</li><li>Clasificación farmacológica</li><li>Mecanismos de acción</li></ul>', '/cursosFarmadec/public/assets/videos/modulo2-cap1.mp4', '/cursosFarmadec/public/assets/img/cap2-1.jpg', 1),
(2, 2, 'Excipientes Farmacéuticos', '<p>Los componentes inactivos y su importancia en la formulación.</p><h3>Contenido:</h3><ul><li>Tipos de excipientes</li><li>Funciones en la formulación</li><li>Compatibilidad química</li></ul>', '/cursosFarmadec/public/assets/videos/modulo2-cap2.mp4', '/cursosFarmadec/public/assets/img/cap2-2.jpg', 2),
(2, 3, 'Interacciones Medicamentosas', '<p>Cómo los diferentes componentes interactúan entre sí.</p><h3>Temas:</h3><ul><li>Interacciones fármaco-fármaco</li><li>Interacciones fármaco-alimento</li><li>Efectos adversos</li></ul>', '/cursosFarmadec/public/assets/videos/modulo2-cap3.mp4', '/cursosFarmadec/public/assets/img/cap2-3.jpg', 3);

-- Cápsulas del módulo 3
INSERT INTO capsules (module_id, number, title, description, video_url, thumb_url, page_order) VALUES
(3, 1, 'Cálculo de Dosis', '<p>Métodos y fórmulas para calcular la dosificación correcta.</p><h3>Objetivos:</h3><ul><li>Fórmulas de dosificación</li><li>Ajuste por peso y edad</li><li>Casos prácticos</li></ul>', '/cursosFarmadec/public/assets/videos/modulo3-cap1.mp4', '/cursosFarmadec/public/assets/img/cap3-1.jpg', 1),
(3, 2, 'Vías de Administración', '<p>Diferentes rutas para administrar medicamentos y sus características.</p><h3>Vías principales:</h3><ul><li>Vía oral</li><li>Vía parenteral</li><li>Vías tópicas</li></ul>', '/cursosFarmadec/public/assets/videos/modulo3-cap2.mp4', '/cursosFarmadec/public/assets/img/cap3-2.jpg', 2),
(3, 3, 'Adherencia al Tratamiento', '<p>Estrategias para asegurar el cumplimiento terapéutico.</p><h3>Aprenderás:</h3><ul><li>Importancia de la adherencia</li><li>Barreras comunes</li><li>Estrategias de mejora</li></ul>', '/cursosFarmadec/public/assets/videos/modulo3-cap3.mp4', '/cursosFarmadec/public/assets/img/cap3-3.jpg', 3);

-- Exámenes (uno por módulo)
INSERT INTO exams (module_id, pass_score, is_active) VALUES
(1, 70, 1),
(2, 70, 1),
(3, 70, 1);

-- Preguntas para examen del módulo 1
INSERT INTO questions (exam_id, text, type) VALUES
(1, 'La farmacia tiene sus orígenes en civilizaciones antiguas como Egipto y Mesopotamia.', 'true_false'),
(1, 'Un fármaco y un medicamento son exactamente lo mismo.', 'true_false'),
(1, 'La legislación farmacéutica es igual en todos los países del mundo.', 'true_false'),
(1, '¿Cuál es el objetivo principal de la farmacéutica?', 'single_choice'),
(1, '¿Qué profesional está autorizado para dispensar medicamentos controlados?', 'single_choice'),
(1, '¿Cuáles son formas farmacéuticas sólidas? (seleccione todas las correctas)', 'multiple_choice'),
(1, 'La farmacocinética estudia:', 'single_choice'),
(1, 'Las Buenas Prácticas de Manufactura (BPM) son obligatorias en la industria farmacéutica.', 'true_false'),
(1, '¿Cuáles son responsabilidades del farmacéutico? (seleccione todas)', 'multiple_choice'),
(1, 'El código de ética farmacéutica regula:', 'single_choice');

-- Opciones para preguntas del módulo 1
INSERT INTO options (question_id, text, is_correct) VALUES
(1, 'Verdadero', 1), (1, 'Falso', 0),
(2, 'Verdadero', 0), (2, 'Falso', 1),
(3, 'Verdadero', 0), (3, 'Falso', 1),
(4, 'Desarrollar y producir medicamentos seguros y eficaces', 1),
(4, 'Vender la mayor cantidad de productos posibles', 0),
(4, 'Prescribir tratamientos médicos', 0),
(5, 'Farmacéutico titulado', 1),
(5, 'Cualquier vendedor de farmacia', 0),
(5, 'El paciente directamente', 0),
(6, 'Tabletas', 1), (6, 'Cápsulas', 1), (6, 'Jarabes', 0), (6, 'Inyecciones', 0),
(7, 'Cómo el organismo procesa el fármaco', 1),
(7, 'El precio de los medicamentos', 0),
(7, 'La publicidad farmacéutica', 0),
(8, 'Verdadero', 1), (8, 'Falso', 0),
(9, 'Dispensar medicamentos', 1), (9, 'Orientar al paciente', 1), (9, 'Realizar diagnósticos médicos', 0), (9, 'Controlar inventario', 1),
(10, 'La conducta profesional del farmacéutico', 1),
(10, 'Los precios de los medicamentos', 0),
(10, 'La decoración de la farmacia', 0);

-- Preguntas para examen del módulo 2
INSERT INTO questions (exam_id, text, type) VALUES
(2, 'Los principios activos son los componentes responsables del efecto terapéutico.', 'true_false'),
(2, 'Los excipientes no tienen ninguna función importante en un medicamento.', 'true_false'),
(2, '¿Qué es un principio activo?', 'single_choice'),
(2, '¿Cuál es la función principal de los excipientes?', 'single_choice'),
(2, 'Las interacciones medicamentosas pueden:', 'single_choice'),
(2, '¿Cuáles son tipos de excipientes? (seleccione todas)', 'multiple_choice'),
(2, 'Es seguro mezclar cualquier medicamento sin consultar al farmacéutico.', 'true_false'),
(2, '¿Qué factores afectan la absorción de un fármaco?', 'multiple_choice'),
(2, 'La biodisponibilidad se refiere a:', 'single_choice'),
(2, 'Los medicamentos genéricos contienen el mismo principio activo que los de marca.', 'true_false');

INSERT INTO options (question_id, text, is_correct) VALUES
(11, 'Verdadero', 1), (11, 'Falso', 0),
(12, 'Verdadero', 0), (12, 'Falso', 1),
(13, 'Componente con acción farmacológica', 1),
(13, 'Envase del medicamento', 0),
(13, 'Nombre comercial del producto', 0),
(14, 'Facilitar la formulación y administración', 1),
(14, 'Aumentar el precio del medicamento', 0),
(14, 'Decorar el producto', 0),
(15, 'Aumentar, disminuir o modificar el efecto de los fármacos', 1),
(15, 'Solo mejorar el sabor', 0),
(15, 'No afectan en absoluto', 0),
(16, 'Aglutinantes', 1), (16, 'Diluyentes', 1), (16, 'Conservantes', 1), (16, 'Principios activos', 0),
(17, 'Verdadero', 0), (17, 'Falso', 1),
(18, 'pH del medio', 1), (18, 'Forma farmacéutica', 1), (18, 'Presencia de alimentos', 1), (18, 'Color del envase', 0),
(19, 'La cantidad de fármaco que llega a la circulación sistémica', 1),
(19, 'El precio del medicamento', 0),
(19, 'La fecha de vencimiento', 0),
(20, 'Verdadero', 1), (20, 'Falso', 0);

-- Preguntas para examen del módulo 3
INSERT INTO questions (exam_id, text, type) VALUES
(3, 'La dosis de un medicamento es siempre la misma para todos los pacientes.', 'true_false'),
(3, 'La vía oral es la más común para administrar medicamentos.', 'true_false'),
(3, '¿Qué factores se deben considerar al calcular una dosis?', 'multiple_choice'),
(3, '¿Cuál es la ventaja de la vía intravenosa?', 'single_choice'),
(3, 'La adherencia al tratamiento se refiere a:', 'single_choice'),
(3, '¿Cuáles son vías parenterales? (seleccione todas)', 'multiple_choice'),
(3, 'Es seguro ajustar la dosis de un medicamento sin consultar al médico.', 'true_false'),
(3, '¿Qué estrategias mejoran la adherencia? (seleccione todas)', 'multiple_choice'),
(3, 'La biodisponibilidad de un fármaco varía según la vía de administración.', 'true_false'),
(3, '¿Cuándo está indicada la vía sublingual?', 'single_choice');

INSERT INTO options (question_id, text, is_correct) VALUES
(21, 'Verdadero', 0), (21, 'Falso', 1),
(22, 'Verdadero', 1), (22, 'Falso', 0),
(23, 'Peso del paciente', 1), (23, 'Edad', 1), (23, 'Función renal y hepática', 1), (23, 'Color de cabello', 0),
(24, 'Efecto inmediato y biodisponibilidad del 100%', 1),
(24, 'Es más económica', 0),
(24, 'No requiere personal capacitado', 0),
(25, 'El cumplimiento del paciente con el tratamiento prescrito', 1),
(25, 'El costo del medicamento', 0),
(25, 'La publicidad del producto', 0),
(26, 'Intravenosa', 1), (26, 'Intramuscular', 1), (26, 'Subcutánea', 1), (26, 'Oral', 0),
(27, 'Verdadero', 0), (27, 'Falso', 1),
(28, 'Educación al paciente', 1), (28, 'Simplificar el régimen', 1), (28, 'Uso de recordatorios', 1), (28, 'Aumentar el precio', 0),
(29, 'Verdadero', 1), (29, 'Falso', 0),
(30, 'Cuando se requiere efecto rápido sin paso hepático', 1),
(30, 'Para cualquier medicamento', 0),
(30, 'Solo para vitaminas', 0);
