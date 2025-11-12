<?php

namespace Farmadec\Http\Middlewares;

use Farmadec\Application\Services\AuthService;

/**
 * Middleware de Administrador
 */
class AdminMiddleware
{
    /** @var AuthService */
    private $authService;
    
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    
    /**
     * Verificar si el usuario es administrador
     */
    public function handle()
    {
        if (!$this->authService->isAuthenticated()) {
            header('Location: ' . url());
            exit;
        }
        
        if (!$this->authService->isAdmin()) {
            http_response_code(403);
            echo 'Acceso denegado. Se requieren permisos de administrador.';
            exit;
        }
    }
    
    /**
     * Verificar admin para AJAX
     */
    public function handleAjax()
    {
        if (!$this->authService->isAuthenticated() || !$this->authService->isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            exit;
        }
    }
}
