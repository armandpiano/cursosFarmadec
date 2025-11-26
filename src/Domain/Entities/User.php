<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad User
 */
class User
{
    /** @var int|null */
    private $id;
    
    /** @var string */
    private $google_sub;
    
    /** @var string */
    private $email;
    
    /** @var string */
    private $password;
    
    /** @var string */
    private $name;
    
    /** @var string|null */
    private $avatar_url;
    
    /** @var string */
    private $role;
    
    /** @var string|null */
    private $created_at;
    
    public function __construct($google_sub, $email, $password = null, $name = '', $avatar_url = null, $role = 'user')
    {
        $this->google_sub = $google_sub;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->avatar_url = $avatar_url;
        $this->role = $role;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getGoogleSub() { return $this->google_sub; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getName() { return $this->name; }
    public function getAvatarUrl() { return $this->avatar_url; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }
    
    public function setPassword($password) { $this->password = $password; }
    public function setGoogleSub($google_sub) { $this->google_sub = $google_sub; }
    public function setEmail($email) { $this->email = $email; }
    public function setName($name) { $this->name = $name; }
    public function setAvatarUrl($url) { $this->avatar_url = $url; }
    public function setRole($role) { $this->role = $role; }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'google_sub' => $this->google_sub,
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
            'avatar_url' => $this->avatar_url,
            'role' => $this->role,
            'created_at' => $this->created_at
        ];
    }
    
    public function verifyPassword($password)
    {
        if (!$this->password) {
            return false;
        }
        return password_verify($password, $this->password);
    }
}
