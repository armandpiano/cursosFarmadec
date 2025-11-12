<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\MySQLUserRepository;
use Farmadec\Domain\Entities\User;

/**
 * Servicio de Autenticación
 */
class AuthService
{
    /** @var MySQLUserRepository */
    private $userRepository;
    
    public function __construct()
    {
        $this->userRepository = new MySQLUserRepository();
    }
    
    /**
     * Verificar token de Google y crear/actualizar usuario
     */
    public function authenticateWithGoogle($google_client, $id_token)
    {
        try {
            $payload = $google_client->verifyIdToken($id_token);
            
            if (!$payload) {
                return ['success' => false, 'message' => 'Token inválido'];
            }
            
            $google_sub = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'] ?? $email;
            $avatar = $payload['picture'] ?? null;
            
            $user = $this->userRepository->findByGoogleSub($google_sub);
            
            if (!$user) {
                $user = new User($google_sub, $email, $name, $avatar, 'user');
                $user = $this->userRepository->create($user);
            } else {
                $user->setName($name);
                $user->setAvatarUrl($avatar);
                $user = $this->userRepository->update($user);
            }
            
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_role'] = $user->getRole();
            $_SESSION['user_avatar'] = $user->getAvatarUrl();
            
            return ['success' => true, 'user' => $user->toArray()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Verificar si el usuario es admin
     */
    public function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Autenticar usuario con email y password
     */
    public function authenticateUser($email, $password)
    {
        try {
            $user = $this->userRepository->findByEmail($email);
            
            if (!$user) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
            
            if (!$user->verifyPassword($password)) {
                return ['success' => false, 'message' => 'Contraseña incorrecta'];
            }
            
            // Crear sesión
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_role'] = $user->getRole();
            $_SESSION['user_avatar'] = $user->getAvatarUrl();
            
            return ['success' => true, 'user' => $user->toArray()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Registrar nuevo usuario
     */
    public function registerUser($email, $password, $name)
    {
        try {
            // Verificar si el email ya existe
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser) {
                return ['success' => false, 'message' => 'El email ya está registrado'];
            }
            
            // Crear nuevo usuario con password hasheado
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Para usuarios normales, google_sub será null
            $user = new User(null, $email, $hashedPassword, $name, null, 'user');
            $user = $this->userRepository->create($user);
            
            // Crear sesión automáticamente
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_role'] = $user->getRole();
            $_SESSION['user_avatar'] = $user->getAvatarUrl();
            
            return ['success' => true, 'user' => $user->toArray()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Obtener datos del usuario actual
     */
    public function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
            'avatar_url' => $_SESSION['user_avatar'] ?? null
        ];
    }
    
    /**
     * Cerrar sesión
     */
    public function logout()
    {
        session_destroy();
    }
}
