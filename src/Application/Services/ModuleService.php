<?php

namespace Farmadec\Application\Services;

use Farmadec\Infrastructure\Persistence\Repositories\{
    MySQLModuleRepository,
    MySQLCapsuleRepository,
    MySQLProgressRepository
};
use Farmadec\Domain\Entities\{Module, Progress};
use Farmadec\Application\Services\CourseService;

/**
 * Servicio de Módulos
 */
class ModuleService
{
    /** @var MySQLModuleRepository */
    private $moduleRepository;
    
    /** @var MySQLCapsuleRepository */
    private $capsuleRepository;
    
    /** @var MySQLProgressRepository */
    private $progressRepository;

    /** @var CourseService */
    private $courseService;
    
    public function __construct()
    {
        $this->moduleRepository = new MySQLModuleRepository();
        $this->capsuleRepository = new MySQLCapsuleRepository();
        $this->progressRepository = new MySQLProgressRepository();
        $this->courseService = new CourseService();
    }
    
    /**
     * Obtener módulos de un curso con progreso del usuario
     */
    public function getModulesWithProgress($user_id, $course_id)
    {
        $modules = $this->moduleRepository->findByCourseId($course_id, true);
        $result = [];
        
        foreach ($modules as $module) {
            $moduleData = $module->toArray();
            $progress = $this->progressRepository->findByUserAndModule($user_id, $module->getId());
            
            $moduleData['status'] = $progress ? $progress->getStatus() : 'not_started';
            $moduleData['percent'] = $progress ? $progress->getPercent() : 0;
            
            $result[] = $moduleData;
        }
        
        return $result;
    }
    
    /**
     * Verificar si un usuario puede acceder a un módulo (prerequisitos)
     */
    public function canUserAccessModule($module_id, $user_id)
    {
        $module = $this->moduleRepository->findById($module_id);
        if (!$module) {
            return false;
        }
        
        // Obtener todos los módulos del curso ordenados por posición
        $allModules = $this->moduleRepository->findByCourseId($module->getCourseId(), true);
        
        // Encontrar la posición del módulo actual
        $currentPosition = null;
        foreach ($allModules as $index => $m) {
            if ($m->getId() === $module_id) {
                $currentPosition = $m->getPosition();
                break;
            }
        }
        
        if ($currentPosition === null || $currentPosition === 1) {
            // Primer módulo - siempre accesible
            return true;
        }
        
        // Verificar que todos los módulos anteriores estén completados
        foreach ($allModules as $m) {
            if ($m->getPosition() < $currentPosition) {
                $prevProgress = $this->progressRepository->findByUserAndModule($user_id, $m->getId());
                if (!$prevProgress || $prevProgress->getStatus() !== 'completed') {
                    return false; // Módulo anterior no completado
                }
            }
        }
        
        return true; // Todos los prerequisitos cumplidos
    }
    
    /**
     * Obtener módulo con cápsulas y progreso
     */
    public function getModuleWithCapsules($module_id, $user_id)
    {
        $module = $this->moduleRepository->findById($module_id);
        
        if (!$module) {
            return null;
        }
        
        // Verificar prerequisitos antes de permitir acceso
        if (!$this->canUserAccessModule($module_id, $user_id)) {
            return 'prerequisite_blocked';
        }
        
        $moduleData = $module->toArray();
        $progress = $this->progressRepository->findByUserAndModule($user_id, $module_id);
        
        $moduleData['status'] = $progress ? $progress->getStatus() : 'not_started';
        $moduleData['percent'] = $progress ? $progress->getPercent() : 0;
        
        $capsules = $this->capsuleRepository->findByModuleId($module_id);
        $moduleData['capsules'] = array_map(function($capsule) use ($user_id) {
            return $capsule->toArray();
        }, $capsules);
        
        return $moduleData;
    }
    
    /**
     * Actualizar progreso del módulo
     */
    public function updateModuleProgress($user_id, $module_id)
    {
        $module = $this->moduleRepository->findById($module_id);

        if (!$module) {
            return null;
        }

        $capsules = $this->capsuleRepository->findByModuleId($module_id);
        $totalCapsules = count($capsules);

        if ($totalCapsules === 0) {
            return;
        }

        // Marcar el curso como iniciado/en progreso
        $this->progressRepository->createEnrollment($user_id, $module->getCourseId());

        $viewedCount = $this->progressRepository->getViewedCapsulesCount($user_id, $module_id);
        $percent = (int)(($viewedCount / $totalCapsules) * 100);
        
        $status = 'not_started';
        if ($percent > 0 && $percent < 100) {
            $status = 'in_progress';
        } elseif ($percent === 100) {
            $status = 'completed';
        }

        $progress = new Progress($user_id, $module_id, $status, $percent);
        $this->progressRepository->createOrUpdate($progress);

        // Si todos los módulos están completos, marcar el curso como completado
        if ($status === 'completed') {
            $this->courseService->checkCourseCompletion($user_id, $module->getCourseId());
        }

        return $progress;
    }
    
    /**
     * Métodos CRUD para admin
     */
    public function getAllModules()
    {
        return $this->moduleRepository->findAll();
    }
    
    public function getModulesByCourseId($course_id)
    {
        return $this->moduleRepository->findByCourseId($course_id);
    }
    
    public function getModuleById($id)
    {
        return $this->moduleRepository->findById($id);
    }
    
    public function createModule($data)
    {
        $module = new Module(
            (int)$data['course_id'],
            (int)$data['number'],
            $data['title'],
            $data['description'] ?? null,
            $data['image_url'] ?? null,
            (int)($data['position'] ?? 0),
            isset($data['is_active']) ? (bool)$data['is_active'] : true
        );
        
        return $this->moduleRepository->create($module);
    }
    
    public function updateModule($id, $data)
    {
        $module = $this->moduleRepository->findById($id);
        
        if (!$module) {
            return null;
        }
        
        if (isset($data['title'])) $module->setTitle($data['title']);
        if (isset($data['description'])) $module->setDescription($data['description']);
        if (isset($data['image_url'])) $module->setImageUrl($data['image_url']);
        if (isset($data['position'])) $module->setPosition((int)$data['position']);
        if (isset($data['is_active'])) $module->setIsActive((bool)$data['is_active']);
        
        return $this->moduleRepository->update($module);
    }
    
    public function deleteModule($id)
    {
        return $this->moduleRepository->delete($id);
    }
}
