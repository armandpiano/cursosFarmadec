<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Question
 */
class Question
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $exam_id;
    
    /** @var string */
    private $text;
    
    /** @var string */
    private $type;
    
    /** @var string|null */
    private $created_at;
    
    /** @var array */
    private $options = [];
    
    public function __construct($exam_id, $text, $type)
    {
        $this->exam_id = $exam_id;
        $this->text = $text;
        $this->type = $type;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getExamId() { return $this->exam_id; }
    public function getText() { return $this->text; }
    public function getType() { return $this->type; }
    public function getCreatedAt() { return $this->created_at; }
    public function getOptions() { return $this->options; }
    
    public function setText($text) { $this->text = $text; }
    public function setType($type) { $this->type = $type; }
    public function setOptions($options) { $this->options = $options; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'text' => $this->text,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'options' => array_map(function($o) { 
                return is_object($o) && method_exists($o, 'toArray') ? $o->toArray() : $o; 
            }, $this->options)
        ];
    }
}
