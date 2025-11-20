<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\Certificate;
use PDO;

/**
 * Repositorio MySQL para Certificates
 */
class MySQLCertificateRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    public function findByUserAndCourse($user_id, $course_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM certificates WHERE user_id = ? AND course_id = ?');
        $stmt->execute([$user_id, $course_id]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function findByCode($code)
    {
        $stmt = $this->db->prepare('SELECT * FROM certificates WHERE code = ?');
        $stmt->execute([$code]);
        $data = $stmt->fetch();
        
        return $data ? $this->hydrate($data) : null;
    }
    
    public function create(Certificate $certificate)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO certificates (user_id, course_id, code, pdf_path) VALUES (?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $certificate->getUserId(),
            $certificate->getCourseId(),
            $certificate->getCode(),
            $certificate->getPdfPath()
        ]);
        
        $certificate->setId((int)$this->db->lastInsertId());
        return $certificate;
    }
    
    public function update(Certificate $certificate)
    {
        $stmt = $this->db->prepare(
            'UPDATE certificates SET pdf_path = ? WHERE id = ?'
        );
        
        $stmt->execute([
            $certificate->getPdfPath(),
            $certificate->getId()
        ]);
        
        return $certificate;
    }
    
    private function hydrate($data)
    {
        $certificate = new Certificate(
            (int)$data['user_id'],
            (int)$data['course_id'],
            $data['code']
        );
        $certificate->setId((int)$data['id']);
        $certificate->setPdfPath($data['pdf_path']);
        
        return $certificate;
    }
}
