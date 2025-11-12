<?php
/**
 * Verificador de Redirect URI Google OAuth
 * Te ayudará a confirmar la URL exacta que Google espera
 */

echo "🔍 VERIFICADOR DE REDIRECT URI - GOOGLE OAUTH\n";
echo str_repeat("=", 60) . "\n\n";

// Verificar la URL actual del callback
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// URL completa esperada por Google
$expected_redirect_uri = $protocol . '://' . $host . $basePath . '/google-callback.php';

echo "📊 INFORMACIÓN DE CONFIGURACIÓN:\n";
echo "   • Protocolo: " . $protocol . "\n";
echo "   • Host: " . $host . "\n";
echo "   • Base Path: " . $basePath . "\n";
echo "   • URL completa: " . $expected_redirect_uri . "\n";

echo "\n✅ URL QUE DEBES CONFIGURAR EN GOOGLE CLOUD CONSOLE:\n";
echo str_repeat("=", 60) . "\n";
echo $expected_redirect_uri;
echo str_repeat("\n", 2);

echo "📝 PASOS PARA CORREGIR:\n";
echo "1. Ve a Google Cloud Console: https://console.cloud.google.com/apis/credentials\n";
echo "2. Clic en tu OAuth 2.0 Client ID\n";
echo "3. En 'Authorized redirect URIs', elimina cualquier URL incorrecta\n";
echo "4. Agrega exactamente esta URL: " . $expected_redirect_uri . "\n";
echo "5. Guarda los cambios\n";
echo "6. Prueba el login nuevamente\n";

echo "\n🔍 VERIFICACIONES ADICIONALES:\n";

// Verificar que google-callback.php existe
if (file_exists('google-callback.php')) {
    echo "   ✅ google-callback.php existe\n";
} else {
    echo "   ❌ google-callback.php no encontrado\n";
}

// Verificar configuración actual
if (file_exists('src/Config/google.local.php')) {
    $config = require 'src/Config/google.local.php';
    if (isset($config['redirect_uri'])) {
        echo "   ℹ️  Redirect URI en config: " . $config['redirect_uri'] . "\n";
        if ($config['redirect_uri'] === $expected_redirect_uri) {
            echo "   ✅ La configuración coincide\n";
        } else {
            echo "   ❌ La configuración NO coincide\n";
        }
    }
}

// Verificar .htaccess
if (file_exists('.htaccess')) {
    $htaccess = file_get_contents('.htaccess');
    if (strpos($htaccess, 'google-callback') !== false) {
        echo "   ✅ .htaccess permite acceso a google-callback.php\n";
    } else {
        echo "   ❌ .htaccess puede estar bloqueando google-callback.php\n";
    }
}

echo "\n🚀 DESPUÉS DE CORREGIR:\n";
echo "1. Prueba el login con Google\n";
echo "2. Google debe redirigir a: " . $expected_redirect_uri . "?code=ABC123&state=XYZ\n";
echo "3. El log debe mostrar código recibido\n";

echo "\n" . str_repeat("=", 60) . "\n";
?>