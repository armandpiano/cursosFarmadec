<?php

namespace Farmadec\Http\Controllers;
use Farmadec\Application\Services\{CourseService, AuthService, CertificateService};

class CourseController{
    private $courseService;
    private $authService;
    private $certificateService;

    public function __construct(){
        $this->courseService = new CourseService();
        $this->authService = new AuthService();
        $this->certificateService = new CertificateService();
    }
    
    /**
     * Listar cursos (página principal del usuario)
     */
    public function list()
    {
        $user = $this->authService->getCurrentUser();
        $courses = $this->courseService->getAllCoursesWithProgress($user['id']);
        
        require __DIR__ . '/../Views/dashboard.php';
    }
    
    /**
     * Iniciar curso (AJAX)
     */
    public function start()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['course_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'course_id requerido']);
            return;
        }
        
        $user = $this->authService->getCurrentUser();
        $result = $this->courseService->startCourse($user['id'], (int)$data['course_id']);
        
        echo json_encode(['success' => (bool)$result]);
    }
    
    /**
     * Ver curso por slug
     */
    public function view($slug)
    {
        $course = $this->courseService->getCourseBySlug($slug);
        
        if (!$course) {
            http_response_code(404);
            echo 'Curso no encontrado';
            return;
        }
        
        $user = $this->authService->getCurrentUser();
        header('Location: ' . url('course/' . $course->getId() . '/modules'));
        exit;
    }
    
    /**
     * Descargar certificado
     */
    public function downloadCertificate($course_id)
    {
        $user = $this->authService->getCurrentUser();
        $course = $this->courseService->getCourseById((int)$course_id);
        
        if (!$course) {
            http_response_code(404);
            echo 'Curso no encontrado';
            return;
        }
        
        $certificate = $this->certificateService->getCertificateByUserAndCourse($user['id'], $course_id);
        
        if (!$certificate) {
            $courseCompleted = $this->courseService->checkCourseCompletion($user['id'], $course_id);
            
            if ($courseCompleted) {
                $certificate = $this->certificateService->generateCertificate($user, $course);
            } else {
                http_response_code(400);
                echo 'Debes completar el curso antes de descargar el certificado';
                return;
            }
        }
        
        $pdfPath = $certificate->getPdfPath();
        
        if ($pdfPath) {
            header('Location: ' . $pdfPath);
            exit;
        } else {
            http_response_code(404);
            echo 'Certificado no disponible';
        }
    }
}
