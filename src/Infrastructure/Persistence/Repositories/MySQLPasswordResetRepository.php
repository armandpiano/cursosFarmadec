<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use PDO;

/**
 * Repositorio para tokens de restablecimiento de contraseña
 */
class MySQLPasswordResetRepository
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }

    public function create($email, $token)
    {
        $stmt = $this->db->prepare('INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$email, $token]);
    }

    public function findByToken($token)
    {
        $stmt = $this->db->prepare('SELECT * FROM password_resets WHERE token = ? LIMIT 1');
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function deleteByEmail($email)
    {
        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$email]);
    }

    public function deleteByToken($token)
    {
        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE token = ?');
        $stmt->execute([$token]);
    }
}
