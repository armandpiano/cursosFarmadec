<?php
// Configuración de la aplicación

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir la ruta base de la aplicación (para subcarpeta)
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define('BASE_URL', (strlen(BASE_PATH) > 1 ? BASE_PATH : '') . '/');

// Función auxiliar para construir URLs - CORREGIDA
function url($path = '') 
{
    // Si es una URL externa, devolverla tal como está
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    
    // Limpiar la ruta de barras al inicio
    $cleanPath = ltrim($path, '/');
    
    // Si la ruta está vacía, devolver la URL base con 'public/'
    if (empty($cleanPath)) {
        return BASE_URL . 'public/';
    }
    
    // Si la ruta ya empieza con 'public/', devolverla tal como está
    if (strpos($cleanPath, 'public/') === 0) {
        return BASE_URL . $cleanPath;
    }
    
    // Si la ruta no empieza con 'public/', agregarlo
    return BASE_URL . 'public/' . $cleanPath;
}

// Función auxiliar para generar URLs internas (SIN public/)
function internalUrl($path = '') 
{
    $cleanPath = ltrim($path, '/');
    if (empty($cleanPath)) {
        return BASE_URL;
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