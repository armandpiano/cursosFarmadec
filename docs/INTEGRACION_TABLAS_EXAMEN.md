# Integración de Tablas de Examen para LMS Farmadec

## Resumen

Este documento describe cómo integrar las nuevas tablas de examen en el sistema LMS Farmadec. El sistema de exámenes implementado utiliza un esquema de base de datos diferente al que existe actualmente en la base de datos, por lo que es necesario realizar una migración cuidadosa.

## Problema Identificado

Al revisar la base de datos existente, se ha identificado que:

1. Ya existen tablas relacionadas con exámenes: `attempts`, `exams`, `options`, `questions`
2. Sin embargo, el código PHP implementado para el sistema de exámenes espera un esquema diferente con tablas como: `exam_questions`, `exam_attempts`, `exam_options`, `exam_answers`
3. Existe un esquema mixto donde algunas funcionalidades pueden estar usando las tablas antiguas mientras otras esperan las nuevas

## Solución Propuesta

Para resolver esta discrepancia, se recomienda implementar un proceso de migración que:

1. **Mantenga los datos existentes**: No perder la información ya registrada en la base de datos
2. **Cree las nuevas tablas**: Implementar el esquema necesario para el código PHP
3. **Migre los datos existentes**: Transferir los datos del esquema anterior al nuevo
4. **Verifique la integridad**: Asegurar que todos los datos estén correctamente relacionados

## Archivos de Migración

Se han preparado los siguientes scripts para facilitar el proceso:

### 1. Script de Migración: `migrate_exam_tables.php`

Este script:
- Verifica si las tablas del nuevo esquema ya existen
- Crea las tablas faltantes del nuevo esquema
- Migra los datos existentes del esquema anterior al nuevo
- Verifica la integridad de los datos después de la migración

### 2. Script de Verificación: `verify_exam_tables.php`

Este script:
- Muestra la estructura actual de las tablas de examen
- Verifica las relaciones entre tablas
- Comprueba la integridad de los datos
- Verifica si los archivos del controlador y las vistas están correctamente implementados

## Instrucciones de Uso

### Paso 1: Ejecutar el Script de Migración

1. Coloca el archivo `migrate_exam_tables.php` en la raíz del proyecto
2. Asegúrate de que la configuración de la base de datos en el script sea correcta
3. Ejecuta el script desde tu navegador o servidor web:

```
http://localhost/cursosFarmadec/code/migrate_exam_tables.php
```

4. Sigue las instrucciones mostradas en pantalla

### Paso 2: Verificar la Migración

1. Ejecuta el script de verificación:

```
http://localhost/cursosFarmadec/code/verify_exam_tables.php
```

2. Revisa el informe generado para asegurar que todo esté correctamente configurado
3. Si hay problemas, corrígelos antes de continuar

### Paso 3: Probar el Sistema de Exámenes

1. Navega a la sección de cápsulas
2. Intenta realizar un examen
3. Verifica que las respuestas se guarden correctamente
4. Confirma que se calcule el puntaje adecuadamente

## Estructura de las Nuevas Tablas

Las tablas implementadas en el nuevo esquema son:

### Tabla `exam_questions`

Almacena las preguntas de los exámenes:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único de la pregunta |
| exam_id | INT | ID del examen al que pertenece la pregunta |
| question_text | TEXT | Texto de la pregunta |
| question_type | ENUM | Tipo de pregunta (multiple_choice, true_false, single_choice) |
| points | INT | Puntos asignados a la pregunta |
| correct_answer | VARCHAR | ID de la opción correcta |
| options | JSON | Array de opciones de respuesta |
| order_index | INT | Orden de la pregunta en el examen |
| created_at | TIMESTAMP | Fecha de creación |

### Tabla `exam_attempts`

Almacena los intentos de examen de los usuarios:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único del intento |
| user_id | INT | ID del usuario que realizó el intento |
| exam_id | INT | ID del examen |
| status | ENUM | Estado del intento (in_progress, completed, failed) |
| score | INT | Puntaje obtenido |
| passed | BOOLEAN | Si el intento fue exitoso |
| answers | JSON | Respuestas proporcionadas |
| created_at | TIMESTAMP | Fecha de inicio del intento |
| completed_at | TIMESTAMP | Fecha de finalización del intento |

### Tabla `exam_options`

Almacena las opciones de respuesta para cada pregunta:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único de la opción |
| question_id | INT | ID de la pregunta a la que pertenece |
| text | VARCHAR | Texto de la opción |
| is_correct | BOOLEAN | Si esta es la opción correcta |
| created_at | TIMESTAMP | Fecha de creación |

### Tabla `exam_answers`

Almacena las respuestas individuales de cada intento:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único de la respuesta |
| attempt_id | INT | ID del intento |
| question_id | INT | ID de la pregunta |
| selected_option_id | INT | ID de la opción seleccionada |
| is_correct | BOOLEAN | Si la respuesta es correcta |
| created_at | TIMESTAMP | Fecha de la respuesta |

## Consideraciones Técnicas

1. **Compatibilidad**: El nuevo esquema es compatible con el esquema anterior, ya que conserva todos los datos existentes.

2. **Flexibilidad**: El campo `options` en formato JSON permite mayor flexibilidad para diferentes tipos de preguntas.

3. **Rendimiento**: Se han añadido índices para mejorar el rendimiento de las consultas.

4. **Integridad**: Se han definido restricciones de clave foránea para mantener la integridad referencial.

## Posibles Problemas y Soluciones

### Problema: Error de clave foránea al intentar ejecutar la migración

**Solución**: Verifica que las tablas `exams`, `users`, etc. existan y tengan los datos necesarios antes de ejecutar la migración.

### Problema: Datos duplicados después de la migración

**Solución**: El script de migración verifica si los datos ya han sido migrados para evitar duplicados. Si aún así ocurren duplicados, puedes eliminar manualmente las tablas nuevas y volver a ejecutar la migración.

### Problema: El sistema de exámenes no funciona correctamente después de la migración

**Solución**: Verifica la implementación del ExamController y las rutas en index.php. Asegúrate de que el código esté utilizando las nuevas tablas y no las anteriores.

## Conclusión

La migración del esquema de base de datos para el sistema de exámenes permitirá que la aplicación funcione correctamente con todas las funcionalidades implementadas. Es importante seguir los pasos indicados para asegurar que los datos existentes no se pierdan y que la integridad de la base de datos se mantenga.

Si tienes algún problema durante el proceso de migración, no dudes en consultar el script de verificación para identificar posibles problemas o errores en la configuración.