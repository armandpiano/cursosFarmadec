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
    public function loginView()
    {
        if ($this->authService->isAuthenticated()) {
            header('Location: ' . url('app'));
            exit;
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
            header('Location: ' . url('login'));
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Email y contraseña son requeridos';
            header('Location: ' . url('login'));
            exit;
        }
        
        $result = $this->authService->authenticateUser($email, $password);
        
        if ($result['success']) {
            header('Location: ' . url('app'));
            exit;
        } else {
            $_SESSION['login_error'] = $result['message'];
            header('Location: ' . url('login'));
            exit;
        }
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
