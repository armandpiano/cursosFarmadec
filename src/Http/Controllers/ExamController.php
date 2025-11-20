<?php

namespace Farmadec\Http\Controllers;

use Farmadec\Application\Services\{ExamService, AuthService, ModuleService, CourseService, CertificateService};

/**
 * Controlador de Exámenes
 */
class ExamController
{
    /** @var ExamService */
    private $examService;
    
    /** @var AuthService */
    private $authService;
    
    /** @var ModuleService */
    private $moduleService;
    
    /** @var CourseService */
    private $courseService;
    
    /** @var CertificateService */
    private $certificateService;
    
    public function __construct()
    {
        $this->examService = new ExamService();
        $this->authService = new AuthService();
        $this->moduleService = new ModuleService();
        $this->courseService = new CourseService();
        $this->certificateService = new CertificateService();
    }
    
    /**
     * Obtener examen de un módulo (AJAX)
     */
    public function getByModule($module_id)
    {
        header('Content-Type: application/json');
        
        $exam = $this->examService->getExamByModuleId((int)$module_id);
        
        if (!$exam) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Examen no encontrado']);
            return;
        }
        
        $examData = $exam->toArray();
        
        foreach ($examData['questions'] as &$question) {
            foreach ($question['options'] as &$option) {
                unset($option['is_correct']);
            }
        }
        
        echo json_encode(['success' => true, 'exam' => $examData]);
    }
    
    /**
     * Enviar respuestas del examen (AJAX)
     */
    public function submit()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos JSON inválidos']);
            return;
        }
        
        if (!isset($data['exam_id']) || !isset($data['answers']) || !isset($data['module_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos incompletos. Se requieren: exam_id, answers, module_id']);
            return;
        }
        
        $user = $this->authService->getCurrentUser();
        $result = $this->examService->evaluateExam($user['id'], (int)$data['exam_id'], $data['answers']);
        
        if ($result['success'] && $result['passed']) {
            $progress = new \Farmadec\Domain\Entities\Progress(
                $user['id'],
                (int)$data['module_id'],
                'completed',
                100
            );
            
            $progressRepo = new \Farmadec\Infrastructure\Persistence\Repositories\MySQLProgressRepository();
            $progressRepo->createOrUpdate($progress);
            
            $module = $this->moduleService->getModuleById((int)$data['module_id']);
            if ($module) {
                $courseCompleted = $this->courseService->checkCourseCompletion($user['id'], $module->getCourseId());
                
                if ($courseCompleted) {
                    $course = $this->courseService->getCourseById($module->getCourseId());
                    $this->certificateService->generateCertificate($user, $course);
                    $result['course_completed'] = true;
                }
            }
        }
        
        echo json_encode($result);
    }
}
