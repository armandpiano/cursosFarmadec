<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\MySQLUserRepository;
use Farmadec\Infrastructure\Persistence\Repositories\MySQLPasswordResetRepository;
use Farmadec\Application\Services\MailService;
use Farmadec\Domain\Entities\User;

/**
 * Servicio de Autenticación
 */
class AuthService
{
    /** @var MySQLUserRepository */
    private $userRepository;

    /** @var MySQLPasswordResetRepository */
    private $passwordResetRepository;

    /** @var MailService */
    private $mailService;

    public function __construct()
    {
        $this->userRepository = new MySQLUserRepository();
        $this->passwordResetRepository = new MySQLPasswordResetRepository();
        $this->mailService = new MailService();
    }

    /**
     * Manejar inicio de sesión de usuario (sesión)
     */
    private function loginUser(User $user)
    {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_role'] = $user->getRole();
        $_SESSION['user_avatar'] = $user->getAvatarUrl();
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
                $user = $this->userRepository->findByEmail($email);

                if ($user) {
                    if (!$user->getGoogleSub()) {
                        $user->setGoogleSub($google_sub);
                    }
                    if ($avatar) {
                        $user->setAvatarUrl($avatar);
                    }
                    $user->setName($name);
                    $user = $this->userRepository->update($user);
                } else {
                    $user = new User($google_sub, $email, null, $name, $avatar, 'user');
                    $user = $this->userRepository->create($user);
                }
            } else {
                $user->setName($name);
                if ($avatar) {
                    $user->setAvatarUrl($avatar);
                }
                if ($user->getEmail() !== $email) {
                    $user->setEmail($email);
                }
                $user = $this->userRepository->update($user);
            }

            $this->loginUser($user);
            
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

            if (!$user || !$user->verifyPassword($password)) {
                return ['success' => false, 'message' => 'Credenciales incorrectas'];
            }

            $this->loginUser($user);

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
            $this->loginUser($user);
            
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

    /**
     * Solicitar recuperación de contraseña
     */
    public function requestPasswordReset($email)
    {
        try {
            $user  = $this->userRepository->findByEmail($email);
            $token = bin2hex(random_bytes(32));

            if ($user) {
                $this->passwordResetRepository->deleteByEmail($email);
                $this->passwordResetRepository->create($email, $token);

                // Detectar esquema (http/https)
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

                // Host + puerto (localhost, farmadec.dyndns-remote.com:8081, etc.)
                $host = $_SERVER['HTTP_HOST']; // incluye :8081 automáticamente

                // Base del proyecto según la ruta de index.php
                // Si index.php está en /cursosFarmadec/index.php, esto da "/cursosFarmadec"
                $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

                // URL absoluta final para el correo
                $resetUrl = $scheme . '://' . $host . $basePath . '/reset-password?token=' . urlencode($token);

                $this->mailService->sendPasswordResetEmail(
                    $email,
                    $user->getName() ?: $email,
                    $resetUrl
                );
            }

            return ['success' => true];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }



    /**
     * Validar token de restablecimiento
     */
    public function validateResetToken($token)
    {
        try {
            $resetRequest = $this->passwordResetRepository->findByToken($token);

            if (!$resetRequest) {
                return ['valid' => false];
            }

            $createdAt = new \DateTime($resetRequest['created_at']);
            $expiresAt = (clone $createdAt)->modify('+1 hour');

            if (new \DateTime() > $expiresAt) {
                $this->passwordResetRepository->deleteByToken($token);
                return ['valid' => false];
            }

            return ['valid' => true, 'email' => $resetRequest['email']];

        } catch (\Exception $e) {
            return ['valid' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Restablecer contraseña usando token
     */
    public function resetPassword($token, $newPassword)
    {
        try {
            $validation = $this->validateResetToken($token);

            if (!$validation['valid']) {
                return ['success' => false, 'message' => 'Token inválido o expirado'];
            }

            $user = $this->userRepository->findByEmail($validation['email']);

            if (!$user) {
                $this->passwordResetRepository->deleteByToken($token);
                return ['success' => false, 'message' => 'Token inválido o expirado'];
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);
            $this->userRepository->update($user);

            $this->passwordResetRepository->deleteByEmail($validation['email']);

            return ['success' => true];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
