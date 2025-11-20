<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\Course;
use PDO;

/**
 * Repositorio MySQL para Courses
 */
class MySQLCourseRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findAll($active_only = false)
    {
        $sql = 'SELECT * FROM courses';
        if ($active_only) {
            $sql .= ' WHERE is_active = 1';
        }
        $sql .= ' ORDER BY created_at DESC';
        
        $stmt = $this->db->query($sql);
        $courses = [];
        
        while ($data = $stmt->fetch()) {
            $courses[] = $this->hydrate($data);
        }
        
        return $courses;
    }
    
    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE slug = ?');
        $stmt->execute([$slug]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function create(Course $course)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO courses (slug, title, description, image_url, is_active) VALUES (?, ?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $course->getSlug(),
            $course->getTitle(),
            $course->getDescription(),
            $course->getImageUrl(),
            $course->isActive() ? 1 : 0
        ]);
        
        $course->setId((int)$this->db->lastInsertId());
        return $course;
    }
    
    public function update(Course $course)
    {
        $stmt = $this->db->prepare(
            'UPDATE courses SET slug = ?, title = ?, description = ?, image_url = ?, is_active = ? WHERE id = ?'
        );
        
        $stmt->execute([
            $course->getSlug(),
            $course->getTitle(),
            $course->getDescription(),
            $course->getImageUrl(),
            $course->isActive() ? 1 : 0,
            $course->getId()
        ]);
        
        return $course;
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    private function hydrate($data)
    {
        $course = new Course(
            $data['slug'],
            $data['title'],
            $data['description'] ?? null,
            $data['image_url'] ?? null,
            (bool)$data['is_active']
        );
        $course->setId((int)$data['id']);
        
        return $course;
    }
}
