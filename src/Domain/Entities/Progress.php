<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Progress (Progreso de módulo)
 */
class Progress
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $user_id;
    
    /** @var int */
    private $module_id;
    
    /** @var string */
    private $status;
    
    /** @var int */
    private $percent;
    
    /** @var string|null */
    private $created_at;
    
    /** @var string|null */
    private $updated_at;
    
    public function __construct($user_id, $module_id, $status = 'not_started', $percent = 0)
    {
        $this->user_id = $user_id;
        $this->module_id = $module_id;
        $this->status = $status;
        $this->percent = $percent;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getUserId() { return $this->user_id; }
    public function getModuleId() { return $this->module_id; }
    public function getStatus() { return $this->status; }
    public function getPercent() { return $this->percent; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    public function setStatus($status) { $this->status = $status; }
    public function setPercent($percent) { $this->percent = $percent; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'module_id' => $this->module_id,
            'status' => $this->status,
            'percent' => $this->percent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
