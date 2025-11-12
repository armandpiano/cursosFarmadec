<?php
/**
 * Google OAuth Callback Handler - CORRECCIÓN CRÍTICA
 * Fixed: Now reads JSON body correctly
 */

// Configurar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función helper para URLs
function url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    
    if ($path === '') {
        return $protocol . '://' . $host . $basePath . '/';
    }
    
    return $protocol . '://' . $host . $basePath . '/' . ltrim($path, '/');
}

// DEBUG: Log request info
error_log('Google Callback - Request Method: ' . $_SERVER['REQUEST_METHOD']);
error_log('Google Callback - Content-Type: ' . ($_SERVER['CONTENT_TYPE'] ?? 'Not set'));

// 🔧 CORRECCIÓN: Leer token desde JSON body correctamente
$id_token = null;

// 1. Intentar desde $_POST (form data)
if (isset($_POST['id_token']) && !empty($_POST['id_token'])) {
    $id_token = $_POST['id_token'];
    error_log('Google Callback - Token found in $_POST');
} 
// 2. Intentar desde JSON body
else {
    $raw_input = file_get_contents('php://input');
    error_log('Google Callback - Raw input: ' . $raw_input);
    
    if (!empty($raw_input)) {
        $data = json_decode($raw_input, true);
        error_log('Google Callback - JSON decoded: ' . print_r($data, true));
        
        if (isset($data['id_token']) && !empty($data['id_token'])) {
            $id_token = $data['id_token'];
            error_log('Google Callback - Token found in JSON body');
        }
    }
}
// 3. Intentar desde GET
elseif (isset($_GET['id_token']) && !empty($_GET['id_token'])) {
    $id_token = $_GET['id_token'];
    error_log('Google Callback - Token found in $_GET');
}

// 🔍 Verificación final del token
if (!$id_token) {
    // Debug completo para diagnóstico
    $debug_info = [
        'error' => 'No se recibieron parámetros de Google OAuth',
        'debug' => [
            'timestamp' => date('Y-m-d H:i:s'),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
            'query_string' => $_SERVER['QUERY_STRING'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'get_params' => array_keys($_GET),
            'post_params' => array_keys($_POST),
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
            'raw_input' => file_get_contents('php://input'),
            'input_length' => strlen(file_get_contents('php://input'))
        ]
    ];
    
    error_log('Google OAuth Debug: ' . print_r($debug_info, true));
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibieron parámetros de Google OAuth',
        'debug' => $debug_info['debug'],
        'solution' => [
            '1. Verificar redirect URI en Google Cloud Console',
            '2. Asegurar que el URL coincide exactamente: http://localhost/cursosFarmadec/google-callback.php',
            '3. Probar con otro navegador o modo incógnito'
        ]
    ]);
    exit;
}

error_log('Google Callback - Token found, length: ' . strlen($id_token));

try {
    // Cargar configuración de Google
    $configPath = __DIR__ . '/src/Config/google.local.php';
    if (!file_exists($configPath)) {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Archivo de configuración no encontrado'
        ]);
        exit;
    }
    
    $config = require $configPath;
    
    // Verificar configuración
    if (empty($config['client_id']) || 
        strpos($config['client_id'], 'TU_GOOGLE_CLIENT_ID') !== false ||
        $config['client_id'] === 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com') {
        
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Configuración de Google OAuth no completada',
            'instructions' => 'Reemplaza "TU_GOOGLE_CLIENT_ID" en src/Config/google.local.php'
        ]);
        exit;
    }
    
    // Cargar Google Client Library
    $vendor_autoload = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($vendor_autoload)) {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Google API Client no está instalado. Ejecuta: composer require google/apiclient'
        ]);
        exit;
    }
    
    require_once $vendor_autoload;
    
    // Crear cliente Google
    $client = new Google_Client([
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'redirect_uri' => $config['redirect_uri'],
    ]);
    
    // Verificar el token ID
    $payload = $client->verifyIdToken($id_token);
    
    if (!$payload) {
        http_response_code(401);
        echo json_encode([
            'success' => false, 
            'message' => 'Token ID inválido o expirado',
            'debug' => [
                'token_length' => strlen($id_token),
                'token_preview' => substr($id_token, 0, 20) . '...'
            ]
        ]);
        exit;
    }
    
    // Extraer datos del usuario
    $google_sub = $payload['sub'];
    $email = $payload['email'];
    $name = $payload['name'] ?? $email;
    $avatar = $payload['picture'] ?? null;
    $email_verified = $payload['email_verified'] ?? false;
    
    // Verificar que el email está verificado
    if (!$email_verified) {
        http_response_code(403);
        echo json_encode([
            'success' => false, 
            'message' => 'El email debe estar verificado en Google'
        ]);
        exit;
    }
    
    // Conectar a la base de datos
    require_once __DIR__ . '/src/Infrastructure/Persistence/MySQLConnection.php';
    require_once __DIR__ . '/src/Infrastructure/Persistence/Repositories/MySQLUserRepository.php';
    require_once __DIR__ . '/src/Domain/Entities/User.php';
    
    $userRepository = new MySQLUserRepository();
    
    // Buscar usuario por Google Sub
    $user = $userRepository->findByGoogleSub($google_sub);
    
    if (!$user) {
        // Verificar si ya existe usuario con este email
        $existingUser = $userRepository->findByEmail($email);
        if ($existingUser) {
            // Actualizar usuario existente con Google Sub
            $existingUser->setGoogleSub($google_sub);
            if ($avatar) $existingUser->setAvatarUrl($avatar);
            $user = $userRepository->update($existingUser);
        } else {
            // Crear nuevo usuario
            $user = new User($google_sub, $email, null, $name, $avatar, 'user');
            $user = $userRepository->create($user);
        }
    } else {
        // Actualizar datos del usuario existente
        $user->setName($name);
        if ($avatar) $user->setAvatarUrl($avatar);
        $user = $userRepository->update($user);
    }
    
    // Crear sesión
    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_email'] = $user->getEmail();
    $_SESSION['user_name'] = $user->getName();
    $_SESSION['user_role'] = $user->getRole();
    $_SESSION['user_avatar'] = $user->getAvatarUrl();
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'redirect' => url('app'),
        'user_info' => [
            'email' => $user->getEmail(),
            'name' => $user->getName()
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error del servidor: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
    error_log('Google OAuth Error: ' . $e->getMessage());
}
?>