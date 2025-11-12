<?php
/**
 * Script de Verificación Google OAuth - CORREGIDO
 * Ejecutar para diagnosticar problemas de configuración
 */

echo "🔍 VERIFICACIÓN GOOGLE OAUTH - FARMADEC LMS\n";
echo str_repeat("=", 50) . "\n\n";

$errors = [];
$warnings = [];
$success = [];

// Cargar autoloader de Composer
try {
    require_once 'vendor/autoload.php';
    echo "✅ Composer autoload cargado correctamente\n\n";
} catch (Exception $e) {
    echo "❌ Error cargando autoload: " . $e->getMessage() . "\n\n";
    $errors[] = "Composer autoload no funciona";
}

// 1. Verificar estructura de archivos
echo "1️⃣ Verificando archivos necesarios...\n";

$requiredFiles = [
    'google-callback.php' => 'Handler OAuth (raíz)',
    'src/Config/google.local.php' => 'Configuración Google',
    'vendor/autoload.php' => 'Composer autoload',
    'composer.json' => 'Dependencias Composer'
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "   ✅ $file - $description\n";
        $success[] = "Archivo encontrado: $file";
    } else {
        echo "   ❌ $file - $description\n";
        $errors[] = "Archivo faltante: $file";
    }
}

echo "\n2️⃣ Verificando configuración de Google...\n";

// 3. Verificar configuración google.local.php
if (file_exists('src/Config/google.local.php')) {
    $config = require 'src/Config/google.local.php';
    
    if (isset($config['client_id'])) {
        if ($config['client_id'] === 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com' || 
            strpos($config['client_id'], 'TU_GOOGLE_CLIENT_ID') !== false) {
            echo "   ❌ Client ID no configurado (placeholder)\n";
            $errors[] = "Client ID sigue siendo placeholder";
        } else {
            echo "   ✅ Client ID configurado\n";
            $success[] = "Client ID configurado";
        }
    }
    
    if (isset($config['client_secret'])) {
        if ($config['client_secret'] === 'TU_GOOGLE_CLIENT_SECRET') {
            echo "   ❌ Client Secret no configurado (placeholder)\n";
            $errors[] = "Client Secret sigue siendo placeholder";
        } else {
            echo "   ✅ Client Secret configurado\n";
            $success[] = "Client Secret configurado";
        }
    }
    
    if (isset($config['redirect_uri'])) {
        echo "   ℹ️  Redirect URI: " . $config['redirect_uri'] . "\n";
    }
} else {
    echo "   ❌ No se puede verificar configuración\n";
}

echo "\n3️⃣ Verificando Google API Client...\n";

// 4. Verificar Google API Client
if (class_exists('Google_Client')) {
    echo "   ✅ Google API Client disponible\n";
    $success[] = "Google API Client instalado";
} else {
    echo "   ❌ Google_Client class no encontrada\n";
    $errors[] = "Google API Client no instalado correctamente";
}

echo "\n4️⃣ Verificando base de datos...\n";

// 5. Verificar estructura de base de datos con namespace correcto
try {
    // Usar el namespace correcto
    $pdo = \Farmadec\Infrastructure\Persistence\MySQLConnection::getInstance();
    
    // Verificar tabla users
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $requiredColumns = ['google_sub', 'password_hash'];
    foreach ($requiredColumns as $column) {
        if (in_array($column, $columns)) {
            echo "   ✅ Columna '$column' existe\n";
            $success[] = "Columna $column en BD";
        } else {
            echo "   ❌ Columna '$column' falta\n";
            $errors[] = "Columna $column faltante en BD";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Error conectando a BD: " . $e->getMessage() . "\n";
    $errors[] = "Error conectando a base de datos: " . $e->getMessage();
} catch (Error $e) {
    echo "   ❌ Error de PHP: " . $e->getMessage() . "\n";
    $errors[] = "Error de PHP: " . $e->getMessage();
}

echo "\n5️⃣ Verificando .htaccess...\n";

// 6. Verificar .htaccess
if (file_exists('.htaccess')) {
    $htaccess = file_get_contents('.htaccess');
    
    if (strpos($htaccess, 'google-callback') !== false) {
        echo "   ✅ .htaccess permite acceso a google-callback.php\n";
        $success[] = ".htaccess configurado para OAuth";
    } else {
        echo "   ⚠️  .htaccess no menciona google-callback.php\n";
        $warnings[] = ".htaccess puede no estar configurado para OAuth";
    }
} else {
    echo "   ❌ .htaccess no encontrado\n";
    $errors[] = "Archivo .htaccess faltante";
}

echo "\n6️⃣ Probando carga de clases del sistema...\n";

// 7. Verificar que las clases del sistema se cargan correctamente
try {
    // Intentar cargar las clases principales
    $reflection = new ReflectionClass('Farmadec\\Infrastructure\\Persistence\\MySQLConnection');
    echo "   ✅ MySQLConnection class se puede cargar\n";
    
    $reflection = new ReflectionClass('Farmadec\\Infrastructure\\Persistence\\Repositories\\MySQLUserRepository');
    echo "   ✅ MySQLUserRepository class se puede cargar\n";
    
    $reflection = new ReflectionClass('Farmadec\\Domain\\Entities\\User');
    echo "   ✅ User class se puede cargar\n";
    
    $success[] = "Todas las clases del sistema se cargan correctamente";
} catch (Exception $e) {
    echo "   ❌ Error cargando clases: " . $e->getMessage() . "\n";
    $errors[] = "Error cargando clases del sistema";
}

// RESUMEN FINAL
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 RESUMEN DE VERIFICACIÓN\n";
echo str_repeat("=", 50) . "\n";

if (!empty($success)) {
    echo "\n✅ ASPECTOS CORRECTOS:\n";
    foreach ($success as $item) {
        echo "   • $item\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️  ADVERTENCIAS:\n";
    foreach ($warnings as $item) {
        echo "   • $item\n";
    }
}

if (!empty($errors)) {
    echo "\n❌ ERRORES ENCONTRADOS:\n";
    foreach ($errors as $item) {
        echo "   • $item\n";
    }
    echo "\n🔧 ACCIONES REQUERIDAS:\n";
    echo "   1. Configurar credenciales de Google OAuth\n";
    echo "   2. Instalar dependencias: composer require google/apiclient\n";
    echo "   3. Aplicar migración de BD si faltan columnas\n";
    echo "   4. Verificar configuración de Google Cloud Console\n";
} else {
    echo "\n🎉 ¡CONFIGURACIÓN CORRECTA!\n";
    echo "   Tu sistema Google OAuth está listo para funcionar.\n";
}

echo "\n📚 RECURSOS ADICIONALES:\n";
echo "   • Guía completa: SOLUCION_ERROR_TOKEN_GOOGLE.md\n";
echo "   • Google Cloud Console: https://console.cloud.google.com/apis/credentials\n";
echo "   • Documentación OAuth: https://developers.google.com/identity/protocols/oauth2\n";

echo "\n" . str_repeat("=", 50) . "\n";
?>