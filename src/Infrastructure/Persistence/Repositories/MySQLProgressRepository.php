<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\Progress;
use PDO;

/**
 * Repositorio MySQL para Progress (Progreso de módulos)
 */
class MySQLProgressRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findByUserAndModule($user_id, $module_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM progress_modules WHERE user_id = ? AND module_id = ?');
        $stmt->execute([$user_id, $module_id]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function findByUserAndCourse($user_id, $course_id)
    {
        $stmt = $this->db->prepare(
            'SELECT pm.* FROM progress_modules pm 
             INNER JOIN modules m ON pm.module_id = m.id 
             WHERE pm.user_id = ? AND m.course_id = ?
             ORDER BY m.position ASC'
        );
        $stmt->execute([$user_id, $course_id]);
        $progress = [];
        
        while ($data = $stmt->fetch()) {
            $progress[] = $this->hydrate($data);
        }
        
        return $progress;
    }
    
    public function createOrUpdate(Progress $progress)
    {
        $existing = $this->findByUserAndModule($progress->getUserId(), $progress->getModuleId());
        
        if ($existing) {
            return $this->update($progress);
        }
        
        return $this->create($progress);
    }
    
    public function create(Progress $progress)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO progress_modules (user_id, module_id, status, percent) VALUES (?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $progress->getUserId(),
            $progress->getModuleId(),
            $progress->getStatus(),
            $progress->getPercent()
        ]);
        
        $progress->setId((int)$this->db->lastInsertId());
        return $progress;
    }
    
    public function update(Progress $progress)
    {
        $stmt = $this->db->prepare(
            'UPDATE progress_modules SET status = ?, percent = ? WHERE user_id = ? AND module_id = ?'
        );
        
        $stmt->execute([
            $progress->getStatus(),
            $progress->getPercent(),
            $progress->getUserId(),
            $progress->getModuleId()
        ]);
        
        return $progress;
    }
    
    public function markCapsuleAsViewed($user_id, $capsule_id)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO progress_capsules (user_id, capsule_id, viewed, finished_at) 
             VALUES (?, ?, 1, NOW()) 
             ON DUPLICATE KEY UPDATE viewed = 1, finished_at = NOW()'
        );
        
        return $stmt->execute([$user_id, $capsule_id]);
    }
    
    public function getViewedCapsulesCount($user_id, $module_id)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as count FROM progress_capsules pc
             INNER JOIN capsules c ON pc.capsule_id = c.id
             WHERE pc.user_id = ? AND c.module_id = ? AND pc.viewed = 1'
        );
        $stmt->execute([$user_id, $module_id]);
        $result = $stmt->fetch();
        
        return (int)$result['count'];
    }
    
    public function saveAttempt($user_id, $exam_id, $score, $passed)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO attempts (user_id, exam_id, score, passed) VALUES (?, ?, ?, ?)'
        );
        
        return $stmt->execute([$user_id, $exam_id, $score, $passed ? 1 : 0]);
    }
    
    public function getBestAttempt($user_id, $exam_id)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM attempts WHERE user_id = ? AND exam_id = ? ORDER BY score DESC, taken_at DESC LIMIT 1'
        );
        $stmt->execute([$user_id, $exam_id]);
        
        return $stmt->fetch();
    }
    
    public function createEnrollment($user_id, $course_id)
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO enrollments (user_id, course_id, started_at) VALUES (?, ?, NOW())'
        );
        
        return $stmt->execute([$user_id, $course_id]);
    }
    
    public function getEnrollment($user_id, $course_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?');
        $stmt->execute([$user_id, $course_id]);
        
        return $stmt->fetch();
    }
    
    public function completeEnrollment($user_id, $course_id)
    {
        $stmt = $this->db->prepare(
            'UPDATE enrollments SET completed_at = NOW() WHERE user_id = ? AND course_id = ? AND completed_at IS NULL'
        );
        
        return $stmt->execute([$user_id, $course_id]);
    }
    
    private function hydrate($data)
    {
        $progress = new Progress(
            (int)$data['user_id'],
            (int)$data['module_id'],
            $data['status'],
            (int)$data['percent']
        );
        $progress->setId((int)$data['id']);
        
        return $progress;
    }
}
