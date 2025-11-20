<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\Capsule;
use PDO;

/**
 * Repositorio MySQL para Capsules
 */
class MySQLCapsuleRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findByModuleId($module_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM capsules WHERE module_id = ? ORDER BY page_order ASC');
        $stmt->execute([$module_id]);
        $capsules = [];
        
        while ($data = $stmt->fetch()) {
            $capsules[] = $this->hydrate($data);
        }
        
        return $capsules;
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM capsules WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function create(Capsule $capsule)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO capsules (module_id, number, title, description, video_url, thumb_url, page_order) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $capsule->getModuleId(),
            $capsule->getNumber(),
            $capsule->getTitle(),
            $capsule->getDescription(),
            $capsule->getVideoUrl(),
            $capsule->getThumbUrl(),
            $capsule->getPageOrder()
        ]);
        
        $capsule->setId((int)$this->db->lastInsertId());
        return $capsule;
    }
    
    public function update(Capsule $capsule)
    {
        $stmt = $this->db->prepare(
            'UPDATE capsules SET module_id = ?, number = ?, title = ?, description = ?, video_url = ?, thumb_url = ?, page_order = ? WHERE id = ?'
        );
        
        $stmt->execute([
            $capsule->getModuleId(),
            $capsule->getNumber(),
            $capsule->getTitle(),
            $capsule->getDescription(),
            $capsule->getVideoUrl(),
            $capsule->getThumbUrl(),
            $capsule->getPageOrder(),
            $capsule->getId()
        ]);
        
        return $capsule;
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM capsules WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    private function hydrate($data)
    {
        $capsule = new Capsule(
            (int)$data['module_id'],
            (int)$data['number'],
            $data['title'],
            $data['description'] ?? null,
            $data['video_url'] ?? null,
            $data['thumb_url'] ?? null,
            (int)$data['page_order']
        );
        $capsule->setId((int)$data['id']);
        
        return $capsule;
    }
}
