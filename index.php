<?php

// Front Controller - index.php
require_once __DIR__ . '/src/autoload.php';

use Farmadec\Http\Controllers\{AuthController, CourseController, ModuleController, ExamController, AdminController};
use Farmadec\Http\Middlewares\{AuthMiddleware, AdminMiddleware};

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$parts = $url ? explode('/', $url) : [];

$authMiddleware = new AuthMiddleware();
$adminMiddleware = new AdminMiddleware();

try {
    if (empty($parts[0])) {
        $controller = new AuthController();
        $controller->loginView();
    } elseif ($parts[0] === 'logout') {
        $controller = new AuthController();
        $controller->logout();
    } elseif ($parts[0] === 'forgot-password') {
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->forgotPassword();
        } else {
            $controller->forgotPasswordView();
        }
    } elseif ($parts[0] === 'reset-password') {
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->resetPassword();
        } else {
            $controller->resetPasswordView();
        }
    } elseif ($parts[0] === 'register') {
        $controller = new AuthController();
        $controller->registerView();
    } elseif ($parts[0] === 'auth') {
        if ($parts[1] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->login();
        } elseif ($parts[1] === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            $controller->register();
        } else {
            http_response_code(404);
            echo 'Ruta de autenticación no encontrada';
        }
    } elseif ($parts[0] === 'google-callback.php') {
        $controller = new AuthController();
        $controller->googleCallback();
    } elseif ($parts[0] === 'profile') {
        $authMiddleware->handle();
        // Placeholder para vista de perfil
        require __DIR__ . '/src/Http/Views/profile.php';
    } elseif ($parts[0] === 'courses') {
        $authMiddleware->handle();
        $controller = new CourseController();
        $controller->list();
    } elseif ($parts[0] === 'app') {
        $authMiddleware->handle();
        $controller = new CourseController();
        $controller->list();
    } elseif ($parts[0] === 'course') {
        $authMiddleware->handle();
        
        if (isset($parts[1]) && isset($parts[2]) && $parts[2] === 'modules') {
            $controller = new ModuleController();
            $controller->listByCourse($parts[1]);
        } else {
            http_response_code(404);
            echo 'Ruta no encontrada';
        }
    } elseif ($parts[0] === 'module' && isset($parts[1])) {
        $authMiddleware->handle();
        $controller = new ModuleController();
        $controller->view($parts[1]);
    } elseif ($parts[0] === 'certificate' && isset($parts[1])) {
        $authMiddleware->handle();
        $controller = new CourseController();
        $controller->downloadCertificate($parts[1]);
    } elseif ($parts[0] === 'api') {
        $authMiddleware->handleAjax();
        
        if ($parts[1] === 'progress' && $parts[2] === 'capsule') {
            $controller = new ModuleController();
            $controller->markCapsuleViewed();
        } elseif ($parts[1] === 'exam' && $parts[2] === 'module' && isset($parts[3])) {
            $controller = new ExamController();
            $controller->getByModule($parts[3]);
        } elseif ($parts[1] === 'exam' && $parts[2] === 'submit') {
            $controller = new ExamController();
            $controller->submit();
        } elseif ($parts[1] === 'course' && $parts[2] === 'start') {
            $controller = new CourseController();
            $controller->start();
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'API endpoint no encontrado']);
        }
    } elseif ($parts[0] === 'admin') {
        $adminMiddleware->handle();
        $controller = new AdminController();
        
        if (empty($parts[1])) {
            $controller->dashboard();
        } elseif ($parts[1] === 'courses') {
            if (isset($parts[2]) && $parts[2] === 'create') {
                $controller->createCourse();
            } elseif (isset($parts[2]) && isset($parts[3]) && $parts[3] === 'update') {
                $controller->updateCourse($parts[2]);
            } elseif (isset($parts[2]) && isset($parts[3]) && $parts[3] === 'delete') {
                $controller->deleteCourse($parts[2]);
            } else {
                $controller->courses();
            }
        } elseif ($parts[1] === 'modules') {
            $controller->modules();
        } elseif ($parts[1] === 'users') {
            $controller->users();
        } else {
            http_response_code(404);
            echo 'Página de admin no encontrada';
        }
    } else {
        http_response_code(404);
        echo 'Página no encontrada';
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Error del servidor: ' . $e->getMessage();
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
}
