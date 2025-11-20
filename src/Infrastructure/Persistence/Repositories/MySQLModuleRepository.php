<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\Module;
use PDO;

/**
 * Repositorio MySQL para Modules
 */
class MySQLModuleRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findAll()
    {
        $stmt = $this->db->query('SELECT * FROM modules ORDER BY position ASC');
        $modules = [];
        
        while ($data = $stmt->fetch()) {
            $modules[] = $this->hydrate($data);
        }
        
        return $modules;
    }
    
    public function findByCourseId($course_id, $active_only = false)
    {
        $sql = 'SELECT * FROM modules WHERE course_id = ?';
        if ($active_only) {
            $sql .= ' AND is_active = 1';
        }
        $sql .= ' ORDER BY position ASC';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$course_id]);
        $modules = [];
        
        while ($data = $stmt->fetch()) {
            $modules[] = $this->hydrate($data);
        }
        
        return $modules;
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM modules WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function create(Module $module)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO modules (course_id, number, title, description, image_url, is_active, position) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $module->getCourseId(),
            $module->getNumber(),
            $module->getTitle(),
            $module->getDescription(),
            $module->getImageUrl(),
            $module->isActive() ? 1 : 0,
            $module->getPosition()
        ]);
        
        $module->setId((int)$this->db->lastInsertId());
        return $module;
    }
    
    public function update(Module $module)
    {
        $stmt = $this->db->prepare(
            'UPDATE modules SET course_id = ?, number = ?, title = ?, description = ?, image_url = ?, is_active = ?, position = ? WHERE id = ?'
        );
        
        $stmt->execute([
            $module->getCourseId(),
            $module->getNumber(),
            $module->getTitle(),
            $module->getDescription(),
            $module->getImageUrl(),
            $module->isActive() ? 1 : 0,
            $module->getPosition(),
            $module->getId()
        ]);
        
        return $module;
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM modules WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    private function hydrate($data)
    {
        $module = new Module(
            (int)$data['course_id'],
            (int)$data['number'],
            $data['title'],
            $data['description'] ?? null,
            $data['image_url'] ?? null,
            (int)$data['position'],
            (bool)$data['is_active']
        );
        $module->setId((int)$data['id']);
        
        return $module;
    }
}
