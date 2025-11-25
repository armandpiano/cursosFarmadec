<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\{
    MySQLCourseRepository,
    MySQLModuleRepository,
    MySQLProgressRepository
};

/**
 * Servicio de Cursos
 */
class CourseService
{
    /** @var MySQLCourseRepository */
    private $courseRepository;
    
    /** @var MySQLModuleRepository */
    private $moduleRepository;
    
    /** @var MySQLProgressRepository */
    private $progressRepository;
    
    public function __construct()
    {
        $this->courseRepository = new MySQLCourseRepository();
        $this->moduleRepository = new MySQLModuleRepository();
        $this->progressRepository = new MySQLProgressRepository();
    }
    
    /**
     * Obtener todos los cursos con su estado de progreso para el usuario
     */
    public function getAllCoursesWithProgress($user_id)
    {
        $courses = $this->courseRepository->findAll(true);
        $result = [];
        
        foreach ($courses as $course) {
            $courseData = $course->toArray();
            $courseData['status'] = $this->getCourseStatus($user_id, $course->getId());
            $courseData['progress_percent'] = $this->getCourseProgress($user_id, $course->getId());
            $result[] = $courseData;
        }
        
        return $result;
    }
    
    /**
     * Obtener estado del curso para un usuario
     */
    public function getCourseStatus($user_id, $course_id)
    {
        $modules = $this->moduleRepository->findByCourseId($course_id, true);

        if (empty($modules)) {
            return 'not_started';
        }

        $enrollment = $this->progressRepository->getEnrollment($user_id, $course_id);
        $hasProgress = false;
        $allCompleted = true;

        foreach ($modules as $module) {
            $progress = $this->progressRepository->findByUserAndModule($user_id, $module->getId());
            $percent = $progress ? $progress->getPercent() : 0;

            if ($percent > 0) {
                $hasProgress = true;
            }

            if (!$progress || $progress->getStatus() !== 'completed') {
                $allCompleted = false;
            }
        }

        if ($allCompleted && $hasProgress) {
            if ($enrollment) {
                $this->progressRepository->completeEnrollment($user_id, $course_id);
            }
            return 'completed';
        }

        if ($hasProgress) {
            if (!$enrollment) {
                $this->progressRepository->createEnrollment($user_id, $course_id);
            }
            return 'in_progress';
        }

        return 'not_started';
    }
    
    /**
     * Obtener progreso total del curso
     */
    public function getCourseProgress($user_id, $course_id)
    {
        $modules = $this->moduleRepository->findByCourseId($course_id, true);
        
        if (empty($modules)) {
            return 0;
        }
        
        $totalPercent = 0;
        $moduleCount = count($modules);
        
        foreach ($modules as $module) {
            $progress = $this->progressRepository->findByUserAndModule($user_id, $module->getId());
            $totalPercent += $progress ? $progress->getPercent() : 0;
        }
        
        return (int)($totalPercent / $moduleCount);
    }
    
    /**
     * Iniciar un curso (crear inscripción)
     */
    public function startCourse($user_id, $course_id)
    {
        return $this->progressRepository->createEnrollment($user_id, $course_id);
    }
    
    /**
     * Verificar si el curso está completo
     */
    public function checkCourseCompletion($user_id, $course_id)
    {
        $modules = $this->moduleRepository->findByCourseId($course_id, true);
        
        foreach ($modules as $module) {
            $progress = $this->progressRepository->findByUserAndModule($user_id, $module->getId());
            
            if (!$progress || $progress->getStatus() !== 'completed') {
                return false;
            }
        }
        
        $this->progressRepository->completeEnrollment($user_id, $course_id);
        return true;
    }
    
    /**
     * Métodos CRUD para admin
     */
    public function getAllCourses()
    {
        return $this->courseRepository->findAll();
    }
    
    public function getCourseById($id)
    {
        return $this->courseRepository->findById($id);
    }
    
    public function getCourseBySlug($slug)
    {
        return $this->courseRepository->findBySlug($slug);
    }
    
    public function createCourse($data)
    {
        $course = new \Farmadec\Domain\Entities\Course(
            $data['slug'],
            $data['title'],
            $data['description'] ?? null,
            $data['image_url'] ?? null,
            isset($data['is_active']) ? (bool)$data['is_active'] : true
        );
        
        return $this->courseRepository->create($course);
    }
    
    public function updateCourse($id, $data)
    {
        $course = $this->courseRepository->findById($id);
        
        if (!$course) {
            return null;
        }
        
        if (isset($data['slug'])) $course->setSlug($data['slug']);
        if (isset($data['title'])) $course->setTitle($data['title']);
        if (isset($data['description'])) $course->setDescription($data['description']);
        if (isset($data['image_url'])) $course->setImageUrl($data['image_url']);
        if (isset($data['is_active'])) $course->setIsActive((bool)$data['is_active']);
        
        return $this->courseRepository->update($course);
    }
    
    public function deleteCourse($id)
    {
        return $this->courseRepository->delete($id);
    }
}
