<?php
/**
 * Google OAuth Callback Handler (versión corregida)
 * - Responde siempre JSON válido
 * - Lee id_token desde POST/JSON/GET
 * - Evita "Class not found" usando FQCN y require_once con __DIR__
 */

// ---- Salida JSON y sesión ----
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ---- Autoload (si usas Composer) ----
$vendor = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendor)) { require_once $vendor; }

// ---- Carga de clases propias (sin depender de autoload PSR-4) ----
require_once __DIR__ . '/src/Infrastructure/Persistence/MySQLConnection.php';
require_once __DIR__ . '/src/Infrastructure/Persistence/Repositories/MySQLUserRepository.php';
require_once __DIR__ . '/src/Domain/Entities/User.php';

// ---- Helper URL ----
function url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
    if ($path === '') {
        return $protocol . '://' . $host . $basePath . '/';
    }
    return $protocol . '://' . $host . $basePath . '/' . ltrim($path, '/');
}

// ---- Log básico de la petición ----
error_log('Google Callback - Method: ' . ($_SERVER['REQUEST_METHOD'] ?? ''));
error_log('Google Callback - Content-Type: ' . ($_SERVER['CONTENT_TYPE'] ?? 'Not set'));

// ---- Obtener id_token (POST | JSON | GET) ----
$id_token = null;

// 1) Form-data / x-www-form-urlencoded
if (!empty($_POST['id_token'])) {
    $id_token = $_POST['id_token'];
    error_log('Google Callback - Token found in $_POST');
} else {
    // 2) JSON body
    $raw_input = file_get_contents('php://input');
    if (!empty($raw_input)) {
        $data = json_decode($raw_input, true);
        if (json_last_error() === JSON_ERROR_NONE && !empty($data['id_token'])) {
            $id_token = $data['id_token'];
            error_log('Google Callback - Token found in JSON body');
        }
    }
}
// 3) GET
if (!$id_token && !empty($_GET['id_token'])) {
    $id_token = $_GET['id_token'];
    error_log('Google Callback - Token found in $_GET');
}

// ---- Si no hay token, responde 400 con diagnóstico ----
if (!$id_token) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error'   => 'No se recibieron parámetros de Google OAuth',
        'debug'   => [
            'timestamp'     => date('Y-m-d H:i:s'),
            'request_uri'   => $_SERVER['REQUEST_URI'] ?? '',
            'query_string'  => $_SERVER['QUERY_STRING'] ?? '',
            'method'        => $_SERVER['REQUEST_METHOD'] ?? '',
            'get_params'    => array_keys($_GET),
            'post_params'   => array_keys($_POST),
            'content_type'  => $_SERVER['CONTENT_TYPE'] ?? 'not set',
            'input_length'  => strlen($raw_input ?? '')
        ],
        'solution' => [
            '1. Verifica que el botón GSI llame a este endpoint',
            '2. Asegura que el URL coincide: ' . url('google-callback.php'),
            '3. Probar en modo incógnito o otro navegador'
        ]
    ]);
    exit;
}

error_log('Google Callback - Token length: ' . strlen($id_token));

try {
    // ---- Cargar configuración local de Google ----
    $configPath = __DIR__ . '/src/Config/google.local.php';
    if (!file_exists($configPath)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Archivo de configuración no encontrado (src/Config/google.local.php)'
        ]);
        exit;
    }
    $config = require $configPath;

    if (empty($config['client_id']) ||
        strpos($config['client_id'], 'TU_GOOGLE_CLIENT_ID') !== false ||
        $config['client_id'] === 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com') {
        http_response_code(500);
        echo json_encode([
            'success'      => false,
            'message'      => 'Configuración de Google OAuth no completada',
            'instructions' => 'Reemplaza "TU_GOOGLE_CLIENT_ID" en src/Config/google.local.php'
        ]);
        exit;
    }

    // ---- Google Client Library ----
    if (!class_exists('Google_Client')) {
        // Si no se cargó via autoload, intenta cargar vendor ahora
        if (file_exists($vendor)) { require_once $vendor; }
    }
    if (!class_exists('Google_Client')) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Google API Client no está instalado. Ejecuta: composer require google/apiclient'
        ]);
        exit;
    }

    // ---- Verificar el id_token ----
    $client  = new Google_Client(['client_id' => $config['client_id']]);
    $payload = $client->verifyIdToken($id_token);

    if (!$payload) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token ID inválido o expirado',
            'debug'   => [
                'token_length' => strlen($id_token),
                'token_preview'=> substr($id_token, 0, 20) . '...'
            ]
        ]);
        exit;
    }

    // ---- Datos del usuario Google ----
    $google_sub     = $payload['sub'] ?? null;
    $email          = $payload['email'] ?? null;
    $name           = $payload['name'] ?? ($email ?? 'Usuario');
    $avatar         = $payload['picture'] ?? null;
    $email_verified = $payload['email_verified'] ?? false;

    if (!$google_sub || !$email) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'No se pudieron extraer datos mínimos del token'
        ]);
        exit;
    }
    if (!$email_verified) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'El email debe estar verificado en Google'
        ]);
        exit;
    }

    // ---- Instanciar repositorio (FQCN para evitar Class not found) ----
    $repoClass = '\\Farmadec\\Infrastructure\\Persistence\\Repositories\\MySQLUserRepository';
    if (!class_exists($repoClass)) {
        throw new Exception('Clase repositorio no encontrada: ' . $repoClass);
    }
    /** @var \Farmadec\Infrastructure\Persistence\Repositories\MySQLUserRepository $userRepository */
    $userRepository = new $repoClass();

    // ---- Buscar / Crear / Actualizar usuario ----
    $user = $userRepository->findByGoogleSub($google_sub);

    if (!$user) {
        $existingUser = $userRepository->findByEmail($email);
        if ($existingUser) {
            $existingUser->setGoogleSub($google_sub);
            if ($avatar) { $existingUser->setAvatarUrl($avatar); }
            $user = $userRepository->update($existingUser);
        } else {
            $userClass = '\\Farmadec\\Domain\\Entities\\User';
            if (!class_exists($userClass)) {
                throw new Exception('Clase entidad no encontrada: ' . $userClass);
            }
            $user = new $userClass($google_sub, $email, null, $name, $avatar, 'user');
            $user = $userRepository->create($user);
        }
    } else {
        $user->setName($name);
        if ($avatar) { $user->setAvatarUrl($avatar); }
        $user = $userRepository->update($user);
    }

    // ---- Sesión ----
    $_SESSION['user_id']     = $user->getId();
    $_SESSION['user_email']  = $user->getEmail();
    $_SESSION['user_name']   = $user->getName();
    $_SESSION['user_role']   = $user->getRole();
    $_SESSION['user_avatar'] = $user->getAvatarUrl();

    // ---- Respuesta OK ----
    echo json_encode([
        'success'   => true,
        'redirect'  => url('app'),
        'user_info' => [
            'email' => $user->getEmail(),
            'name'  => $user->getName()
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage(),
        'debug'   => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
    error_log('Google OAuth Error: ' . $e->getMessage());
}
