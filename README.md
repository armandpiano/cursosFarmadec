# Migración de Tablas de Examen - LMS Farmadec

Este repositorio contiene scripts para migrar e integrar las tablas de examen en tu sistema LMS Farmadec.

## Problema

Tu base de datos actual utiliza un esquema con tablas `attempts`, `questions` y `options`, pero el código PHP implementado para el sistema de exámenes requiere un esquema diferente con tablas `exam_questions`, `exam_attempts`, `exam_options` y `exam_answers`.

Además, hemos identificado incompatibilidades con MariaDB que necesitaban ser solucionadas.

## Solución

Los scripts en este repositorio facilitan la migración sin perder datos existentes:

1. **`migrate_exams_compatible.php`**: Script compatible con MariaDB (recomendado)
2. **`migrate_exams_simple.php`**: Script simplificado (puede tener problemas con MariaDB)
3. **`migrate_exams.sql`**: Archivo SQL para ejecutar directamente en phpMyAdmin
4. **`verify_exam_tables.php`**: Script para verificar que la migración fue exitosa

## Cómo ejecutar

### Opción 1: Script PHP Compatible (Recomendado)

```bash
# En tu navegador, visita:
http://localhost/cursosFarmadec/code/migrate_exams_compatible.php
```

### Opción 2: Archivo SQL para phpMyAdmin

1. Abre phpMyAdmin
2. Selecciona la base de datos `farmadec_lms`
3. Ve a la pestaña "SQL"
4. Copia y pega el contenido del archivo `migrate_exams.sql`
5. Haz clic en "Continuar" para ejecutar

### Verificar la migración

```bash
# En tu navegador, visita:
http://localhost/cursosFarmadec/code/verify_exam_tables.php
```

## Documentación

Para información más detallada, consulta:

- `code/INSTRUCCIONES_MIGRACION_FINALES.md`: Guía paso a paso con explicaciones
- `docs/INTEGRACION_TABLAS_EXAMEN.md`: Documentación técnica completa sobre el proceso

## Notas importantes

- La migración **NO elimina** ningún dato existente
- La migración **crea nuevas tablas** y **copia** los datos al nuevo esquema
- Es un proceso seguro que mantiene toda tu información
- Los nuevos scripts están optimizados para MariaDB
- Una vez ejecutada la migración, podrás utilizar el sistema de exámenes sin problemas