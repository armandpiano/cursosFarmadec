<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Exam
 */
class Exam
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $module_id;
    
    /** @var int */
    private $pass_score;
    
    /** @var bool */
    private $is_active;
    
    /** @var string|null */
    private $created_at;
    
    /** @var array */
    private $questions = [];
    
    public function __construct($module_id, $pass_score = 70, $is_active = true)
    {
        $this->module_id = $module_id;
        $this->pass_score = $pass_score;
        $this->is_active = $is_active;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getModuleId() { return $this->module_id; }
    public function getPassScore() { return $this->pass_score; }
    public function isActive() { return $this->is_active; }
    public function getCreatedAt() { return $this->created_at; }
    public function getQuestions() { return $this->questions; }
    
    public function setPassScore($score) { $this->pass_score = $score; }
    public function setIsActive($active) { $this->is_active = $active; }
    public function setQuestions($questions) { $this->questions = $questions; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'pass_score' => $this->pass_score,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'questions' => array_map(function($q) { 
                return is_object($q) && method_exists($q, 'toArray') ? $q->toArray() : $q; 
            }, $this->questions)
        ];
    }
}
