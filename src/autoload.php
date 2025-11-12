<?php
// Autoloader personalizado para PHP 7.3

spl_autoload_register(function ($class) {
    // Prefijo del namespace del proyecto
    $prefix = 'Farmadec\\';
    
    // Directorio base para el namespace
    $base_dir = __DIR__ . '/';
    
    // Verificar si la clase usa el namespace del proyecto
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);
    
    // Reemplazar namespace separators con directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo existe, requerirlo
    if (file_exists($file)) {
        require $file;
    }
});

// Cargar Composer autoload si existe
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

// Cargar configuración de la aplicación
require_once __DIR__ . '/Config/app.php';
