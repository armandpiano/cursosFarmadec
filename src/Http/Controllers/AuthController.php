<?php

namespace Farmadec\Http\Controllers;

use Farmadec\Application\Services\AuthService;

/**
 * Controlador de Autenticación
 */
class AuthController
{
    /** @var AuthService */
    private $authService;
    
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    
    /**
     * Vista de login
     */
    public function loginView($error = null)
    {
        if ($this->authService->isAuthenticated()) {
            header('Location: ' . url('app'));
            exit;
        }

        $loginError = $error;

        if (isset($_SESSION['login_error'])) {
            $loginError = $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }

        require __DIR__ . '/../Views/login.php';
    }
    
    /**
     * Vista de registro
     */
    public function registerView()
    {
        if ($this->authService->isAuthenticated()) {
            header('Location: ' . url('app'));
            exit;
        }
        
        require __DIR__ . '/../Views/register.php';
    }
    
    /**
     * Procesar login normal
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->loginView();
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->loginView('Email y contraseña son requeridos');
            return;
        }

        $result = $this->authService->authenticateUser($email, $password);

        if ($result['success']) {
            header('Location: ' . url('app'));
            exit;
        } else {
            $this->loginView('Credenciales incorrectas');
            return;
        }
    }

    /**
     * Mostrar formulario de recuperación
     */
    public function forgotPasswordView()
    {
        $message = $_SESSION['forgot_password_message'] ?? null;
        $error = $_SESSION['forgot_password_error'] ?? null;

        unset($_SESSION['forgot_password_message'], $_SESSION['forgot_password_error']);

        require __DIR__ . '/../Views/forgot-password.php';
    }

    /**
     * Procesar recuperación de contraseña
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->forgotPasswordView();
            return;
        }

        $email = trim($_POST['email'] ?? '');

        $result = $this->authService->requestPasswordReset($email);

        if (!$result['success']) {
            $_SESSION['forgot_password_error'] = 'No se pudo procesar la solicitud en este momento. Inténtalo de nuevo más tarde.';
        }

        $_SESSION['forgot_password_message'] = 'Si el correo está registrado, enviaremos instrucciones.';

        header('Location: ' . url('forgot-password'));
        exit;
    }

    /**
     * Formulario para restablecer contraseña
     */
    public function resetPasswordView()
    {
        $token = $_GET['token'] ?? '';
        $tokenValidation = $token ? $this->authService->validateResetToken($token) : ['valid' => false];

        $tokenError = $tokenValidation['valid'] ? null : 'Token inválido o expirado';
        $formError = $_SESSION['reset_password_error'] ?? null;

        unset($_SESSION['reset_password_error']);

        require __DIR__ . '/../Views/reset-password.php';
    }

    /**
     * Procesar restablecimiento de contraseña
     */
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->resetPasswordView();
            return;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $validation = $token ? $this->authService->validateResetToken($token) : ['valid' => false];

        if (!$validation['valid']) {
            $_SESSION['reset_password_error'] = 'Token inválido o expirado';
            $_GET['token'] = $token;
            $this->resetPasswordView();
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['reset_password_error'] = 'La contraseña debe tener al menos 6 caracteres';
            $_GET['token'] = $token;
            $this->resetPasswordView();
            return;
        }

        if ($password !== $confirm) {
            $_SESSION['reset_password_error'] = 'Las contraseñas no coinciden';
            $_GET['token'] = $token;
            $this->resetPasswordView();
            return;
        }

        $result = $this->authService->resetPassword($token, $password);

        if ($result['success']) {
            $_SESSION['login_error'] = 'Contraseña actualizada. Inicia sesión con tu correo y contraseña.';
            header('Location: ' . url());
            exit;
        }

        $_SESSION['reset_password_error'] = $result['message'] ?? 'No se pudo restablecer la contraseña';
        $_GET['token'] = $token;
        $this->resetPasswordView();
    }
    
    /**
     * Procesar registro
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('register'));
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        
        // Validaciones
        if (empty($email) || empty($password) || empty($name)) {
            $_SESSION['register_error'] = 'Todos los campos son requeridos';
            header('Location: ' . url('register'));
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_error'] = 'Email inválido';
            header('Location: ' . url('register'));
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['register_error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: ' . url('register'));
            exit;
        }
        
        if ($password !== $confirm_password) {
            $_SESSION['register_error'] = 'Las contraseñas no coinciden';
            header('Location: ' . url('register'));
            exit;
        }
        
        $result = $this->authService->registerUser($email, $password, $name);
        
        if ($result['success']) {
            header('Location: ' . url('app'));
            exit;
        } else {
            $_SESSION['register_error'] = $result['message'];
            header('Location: ' . url('register'));
            exit;
        }
    }
    
    /**
     * Callback de Google OAuth
     */
    public function googleCallback()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['id_token'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
            return;
        }
        
        try {
            $config = require __DIR__ . '/../../Config/google.local.php';
            $client = new \Google_Client(['client_id' => $config['client_id']]);
            
            $result = $this->authService->authenticateWithGoogle($client, $data['id_token']);
            
            if ($result['success']) {
                echo json_encode(['success' => true, 'redirect' => url('app')]);
            } else {
                http_response_code(401);
                echo json_encode($result);
            }
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout()
    {
        $this->authService->logout();
        header('Location: ' . url());
        exit;
    }
}
