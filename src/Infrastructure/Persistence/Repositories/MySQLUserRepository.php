<?php

namespace Farmadec\Infrastructure\Persistence\Repositories;

use Farmadec\Infrastructure\Persistence\MySQLConnection;
use Farmadec\Domain\Entities\User;
use PDO;

/**
 * Repositorio MySQL para Users
 */
class MySQLUserRepository
{
    /** @var PDO */
    private $db;
    
    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }
    
    /**
     * Buscar usuario por Google Sub
     */
    public function findByGoogleSub($google_sub)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE google_sub = ?');
        $stmt->execute([$google_sub]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    /**
     * Buscar usuario por email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    /**
     * Buscar usuario por ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function findAll()
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        $users = [];
        
        while ($data = $stmt->fetch()) {
            $users[] = $this->hydrate($data);
        }
        
        return $users;
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create(User $user)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (google_sub, email, password_hash, name, avatar_url, role) VALUES (?, ?, ?, ?, ?, ?)'
        );
        
        $stmt->execute([
            $user->getGoogleSub(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
            $user->getAvatarUrl(),
            $user->getRole()
        ]);
        
        $user->setId((int)$this->db->lastInsertId());
        return $user;
    }
    
    /**
     * Actualizar usuario
     */
    public function update(User $user)
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET google_sub = ?, password_hash = ?, name = ?, avatar_url = ?, role = ?, email = ? WHERE id = ?'
        );

        $stmt->execute([
            $user->getGoogleSub(),
            $user->getPassword(),
            $user->getName(),
            $user->getAvatarUrl(),
            $user->getRole(),
            $user->getEmail(),
            $user->getId()
        ]);
        
        return $user;
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
    
    /**
     * Hidratar entidad desde array
     */
    private function hydrate($data)
    {
        $user = new User(
            $data['google_sub'] ?? null,
            $data['email'],
            $data['password_hash'] ?? null,
            $data['name'] ?? '',
            $data['avatar_url'] ?? null,
            $data['role'] ?? 'user'
        );
        $user->setId((int)$data['id']);
        
        return $user;
    }
}
