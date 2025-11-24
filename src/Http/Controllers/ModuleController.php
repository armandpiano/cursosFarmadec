<?php

namespace Farmadec\Http\Controllers;

use Farmadec\Application\Services\{ModuleService, CourseService, AuthService};

/**
 * Controlador de Módulos
 */
class ModuleController
{
    /** @var ModuleService */
    private $moduleService;
    
    /** @var CourseService */
    private $courseService;
    
    /** @var AuthService */
    private $authService;
    
    public function __construct()
    {
        $this->moduleService = new ModuleService();
        $this->courseService = new CourseService();
        $this->authService = new AuthService();
    }
    
    /**
     * Listar módulos de un curso
     */
    public function listByCourse($course_id)
    {
        $course = $this->courseService->getCourseById((int)$course_id);
        
        if (!$course) {
            http_response_code(404);
            echo 'Curso no encontrado';
            return;
        }
        
        $user = $this->authService->getCurrentUser();
        
        // Registrar inscripción al curso si es la primera vez que el usuario ingresa
        $courseStatus = $this->courseService->getCourseStatus($user['id'], $course_id);
        if ($courseStatus === 'not_started') {
            $this->courseService->startCourse($user['id'], $course_id);
            $courseStatus = 'in_progress';
        }
        $modules = $this->moduleService->getModulesWithProgress($user['id'], $course_id);
        
        $courseData = $course->toArray();
        $courseData['status'] = $courseStatus;
        $courseData['progress_percent'] = $this->courseService->getCourseProgress($user['id'], $course_id);
        
        require __DIR__ . '/../Views/modules.php';
    }
    
    /**
     * Ver módulo con cápsulas
     */
    public function view($module_id)
    {
        $user = $this->authService->getCurrentUser();
        $moduleData = $this->moduleService->getModuleWithCapsules((int)$module_id, $user['id']);
        
        if (!$moduleData) {
            http_response_code(404);
            echo 'Módulo no encontrado';
            return;
        }
        
        if ($moduleData === 'prerequisite_blocked') {
            // Módulo bloqueado por prerequisitos - obtener info del curso
            $module = $this->moduleService->getModuleById((int)$module_id);
            if ($module) {
                $course = $this->courseService->getCourseById($module->getCourseId());
                $courseData = $course ? $course->toArray() : null;
            }
            
            http_response_code(403);
            require __DIR__ . '/../Views/module_blocked.php';
            return;
        }
        
        $module = (object)$moduleData;
        $course = $this->courseService->getCourseById($module->course_id);
        
        // Obtener todos los módulos del curso para el sidebar
        $allCourseModules = $this->moduleService->getModulesWithProgress($user['id'], $module->course_id);
        
        require __DIR__ . '/../Views/module_view.php';
    }
    
    /**
     * Marcar cápsula como vista (AJAX)
     */
    public function markCapsuleViewed()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['capsule_id']) || !isset($data['module_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'capsule_id y module_id requeridos']);
            return;
        }
        
        $user = $this->authService->getCurrentUser();
        
        $progressRepo = new \Farmadec\Infrastructure\Persistence\Repositories\MySQLProgressRepository();
        $result = $progressRepo->markCapsuleAsViewed($user['id'], (int)$data['capsule_id']);
        
        if ($result) {
            $progress = $this->moduleService->updateModuleProgress($user['id'], (int)$data['module_id']);
            
            echo json_encode([
                'success' => true,
                'progress' => $progress ? $progress->toArray() : null
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar progreso']);
        }
    }
}
