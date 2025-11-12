<?php
// Configuración de la aplicación

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir la ruta base de la aplicación (para subcarpeta)
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define('BASE_URL', (strlen(BASE_PATH) > 1 ? BASE_PATH : '') . '/');

// Función auxiliar para construir URLs
function url($path = '') 
{
    if (empty($path)) {
        return BASE_URL;
    }
    
    // Si es una URL externa, la devolvemos tal como está
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    
    // Limpiar la ruta de "public/" si existe
    $cleanPath = ltrim($path, '/');
    if (strpos($cleanPath, 'public/') === 0) {
        $cleanPath = substr($cleanPath, 7); // Remover "public/"
    }
    
    return BASE_URL . $cleanPath;
}

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de errores (desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charset
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
