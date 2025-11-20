<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Option (Opción de respuesta)
 */
class Option
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $question_id;
    
    /** @var string */
    private $text;
    
    /** @var bool */
    private $is_correct;
    
    /** @var string|null */
    private $created_at;
    
    public function __construct($question_id, $text, $is_correct = false)
    {
        $this->question_id = $question_id;
        $this->text = $text;
        $this->is_correct = $is_correct;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getQuestionId() { return $this->question_id; }
    public function getText() { return $this->text; }
    public function isCorrect() { return $this->is_correct; }
    public function getCreatedAt() { return $this->created_at; }
    
    public function setText($text) { $this->text = $text; }
    public function setIsCorrect($correct) { $this->is_correct = $correct; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'text' => $this->text,
            'is_correct' => $this->is_correct,
            'created_at' => $this->created_at
        ];
    }
}
