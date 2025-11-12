<?php
/**
 * Google OAuth Debug Script
 * Ejecutar para diagnosticar problemas del callback
 */

echo "🔍 GOOGLE OAUTH DEBUG - FARMADEC LMS\n";
echo str_repeat("=", 60) . "\n\n";

// Test 1: Verificar request actual
echo "1️⃣ INFORMACIÓN DE LA REQUEST ACTUAL:\n";
echo "   Método: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "   URI: " . ($_SERVER['REQUEST_URI'] ?? 'No disponible') . "\n";
echo "   Query String: " . ($_SERVER['QUERY_STRING'] ?? 'Vacío') . "\n";
echo "   Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'No definido') . "\n";
echo "   User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'No disponible') . "\n\n";

// Test 2: Verificar parámetros GET
echo "2️⃣ PARÁMETROS GET RECIBIDOS:\n";
if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        echo "   GET['$key'] = " . (is_string($value) ? $value : print_r($value, true)) . "\n";
    }
} else {
    echo "   ❌ No hay parámetros GET\n";
}
echo "\n";

// Test 3: Verificar parámetros POST
echo "3️⃣ PARÁMETROS POST RECIBIDOS:\n";
if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        echo "   POST['$key'] = " . (is_string($value) ? $value : print_r($value, true)) . "\n";
    }
} else {
    echo "   ❌ No hay parámetros POST\n";
}
echo "\n";

// Test 4: Verificar body JSON
echo "4️⃣ BODY JSON RECIBIDO:\n";
$raw_input = file_get_contents('php://input');
if (!empty($raw_input)) {
    echo "   ✅ Raw input: $raw_input\n";
    $json_data = json_decode($raw_input, true);
    if ($json_data) {
        echo "   ✅ JSON decodificado correctamente:\n";
        foreach ($json_data as $key => $value) {
            echo "      $key: $value\n";
        }
    } else {
        echo "   ❌ Error decodificando JSON\n";
    }
} else {
    echo "   ❌ No hay body input\n";
}
echo "\n";

// Test 5: Verificar hash/fragment
echo "5️⃣ HASH/FRAGMENT EN URL:\n";
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $hash_params);
    if (!empty($hash_params)) {
        echo "   ✅ Parámetros en hash:\n";
        foreach ($hash_params as $key => $value) {
            echo "      $key: $value\n";
        }
    } else {
        echo "   ❌ No se pudieron parsear parámetros del hash\n";
    }
} else {
    echo "   ❌ No hay query string/hash\n";
}
echo "\n";

// Test 6: Verificar tokens posibles
echo "6️⃣ BÚSQUEDA DE ID_TOKEN:\n";
$found_token = false;

if (isset($_GET['id_token']) && !empty($_GET['id_token'])) {
    echo "   ✅ Token encontrado en GET['id_token']\n";
    echo "      Primeros 20 caracteres: " . substr($_GET['id_token'], 0, 20) . "...\n";
    $found_token = true;
}

if (!$found_token && isset($json_data['id_token']) && !empty($json_data['id_token'])) {
    echo "   ✅ Token encontrado en JSON body['id_token']\n";
    echo "      Primeros 20 caracteres: " . substr($json_data['id_token'], 0, 20) . "...\n";
    $found_token = true;
}

if (!$found_token && isset($_POST['id_token']) && !empty($_POST['id_token'])) {
    echo "   ✅ Token encontrado en POST['id_token']\n";
    echo "      Primeros 20 caracteres: " . substr($_POST['id_token'], 0, 20) . "...\n";
    $found_token = true;
}

if (!$found_token && isset($hash_params['id_token']) && !empty($hash_params['id_token'])) {
    echo "   ✅ Token encontrado en hash['id_token']\n";
    echo "      Primeros 20 caracteres: " . substr($hash_params['id_token'], 0, 20) . "...\n";
    $found_token = true;
}

if (!$found_token) {
    echo "   ❌ NO SE ENCONTRÓ ID_TOKEN EN NINGÚN LUGAR\n";
}
echo "\n";

// Test 7: Verificar configuración
echo "7️⃣ VERIFICAR CONFIGURACIÓN GOOGLE:\n";
$config_path = __DIR__ . '/src/Config/google.local.php';
if (file_exists($config_path)) {
    try {
        $config = require $config_path;
        if (isset($config['client_id'])) {
            if (strpos($config['client_id'], 'TU_GOOGLE_CLIENT_ID') !== false) {
                echo "   ❌ Client ID es placeholder: " . $config['client_id'] . "\n";
            } else {
                echo "   ✅ Client ID configurado: " . $config['client_id'] . "\n";
            }
        }
        if (isset($config['client_secret'])) {
            if ($config['client_secret'] === 'TU_GOOGLE_CLIENT_SECRET') {
                echo "   ❌ Client Secret es placeholder\n";
            } else {
                echo "   ✅ Client Secret configurado\n";
            }
        }
        if (isset($config['redirect_uri'])) {
            echo "   ℹ️  Redirect URI: " . $config['redirect_uri'] . "\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Error cargando configuración: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ❌ Archivo de configuración no encontrado\n";
}
echo "\n";

// Test 8: Verificar Google API Client
echo "8️⃣ VERIFICAR GOOGLE API CLIENT:\n";
if (class_exists('Google_Client')) {
    echo "   ✅ Google_Client class disponible\n";
} else {
    echo "   ❌ Google_Client class no encontrada\n";
    echo "      Solución: composer require google/apiclient\n";
}
echo "\n";

// RECOMENDACIONES
echo "📋 RECOMENDACIONES:\n";
if (!$found_token) {
    echo "   ⚠️  ID_TOKEN no encontrado - revisar flujo OAuth\n";
    echo "   1. Verificar que Google Identity Services esté cargado\n";
    echo "   2. Revisar consola del navegador por errores JavaScript\n";
    echo "   3. Verificar que el Client ID sea válido\n";
    echo "   4. Confirmar que el redirect URI sea correcto\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🔧 Si sigues teniendo problemas, revisa:\n";
echo "   • GOOGLE_OAUTH_SETUP_GUIDE.md\n";
echo "   • Verificar configuración en Google Cloud Console\n";
echo "   • Logs del servidor web\n";
echo str_repeat("=", 60) . "\n";
?>