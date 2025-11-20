<?php

namespace Farmadec\Http\Controllers;

use Farmadec\Application\Services\{CourseService, ModuleService, AuthService};
use Farmadec\Infrastructure\Persistence\Repositories\MySQLUserRepository;

/**
 * Controlador de Administración
 */
class AdminController
{
    /** @var CourseService */
    private $courseService;
    
    /** @var ModuleService */
    private $moduleService;
    
    /** @var AuthService */
    private $authService;
    
    /** @var MySQLUserRepository */
    private $userRepository;
    
    public function __construct()
    {
        $this->courseService = new CourseService();
        $this->moduleService = new ModuleService();
        $this->authService = new AuthService();
        $this->userRepository = new MySQLUserRepository();
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        $courses = $this->courseService->getAllCourses();
        $users = $this->userRepository->findAll();
        
        $stats = [
            'total_courses' => count($courses),
            'total_users' => count($users),
            'active_courses' => count(array_filter($courses, function($c) { return $c->isActive(); }))
        ];
        
        require __DIR__ . '/../Views/admin/dashboard.php';
    }
    
    /**
     * Gestión de cursos
     */
    public function courses()
    {
        $courses = $this->courseService->getAllCourses();
        require __DIR__ . '/../Views/admin/courses.php';
    }
    
    /**
     * Gestión de módulos
     */
    public function modules()
    {
        $courses = $this->courseService->getAllCourses();
        $modules = $this->moduleService->getAllModules();
        require __DIR__ . '/../Views/admin/modules.php';
    }
    
    /**
     * Gestión de usuarios
     */
    public function users()
    {
        $users = $this->userRepository->findAll();
        require __DIR__ . '/../Views/admin/users.php';
    }
    
    /**
     * API: Crear curso (AJAX)
     */
    public function createCourse()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $data = $_POST;
        
        try {
            $course = $this->courseService->createCourse($data);
            echo json_encode(['success' => true, 'course' => $course->toArray()]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Actualizar curso (AJAX)
     */
    public function updateCourse($id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $data = $_POST;
        
        try {
            $course = $this->courseService->updateCourse((int)$id, $data);
            
            if ($course) {
                echo json_encode(['success' => true, 'course' => $course->toArray()]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Eliminar curso (AJAX)
     */
    public function deleteCourse($id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        try {
            $result = $this->courseService->deleteCourse((int)$id);
            echo json_encode(['success' => (bool)$result]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
