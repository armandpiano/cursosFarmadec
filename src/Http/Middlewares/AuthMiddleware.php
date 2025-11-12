<?php

namespace Farmadec\Http\Middlewares;

use Farmadec\Application\Services\AuthService;

/**
 * Middleware de Autenticación
 */
class AuthMiddleware
{
    /** @var AuthService */
    private $authService;
    
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public function handle()
    {
        if (!$this->authService->isAuthenticated()) {
            header('Location: ' . url());
            exit;
        }
    }
    
    /**
     * Verificar autenticación para AJAX
     */
    public function handleAjax()
    {
        if (!$this->authService->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }
    }
}
