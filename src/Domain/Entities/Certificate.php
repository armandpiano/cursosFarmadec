<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Certificate
 */
class Certificate
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $user_id;
    
    /** @var int */
    private $course_id;
    
    /** @var string */
    private $code;
    
    /** @var string|null */
    private $issued_at;
    
    /** @var string|null */
    private $pdf_path;
    
    public function __construct($user_id, $course_id, $code)
    {
        $this->user_id = $user_id;
        $this->course_id = $course_id;
        $this->code = $code;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getUserId() { return $this->user_id; }
    public function getCourseId() { return $this->course_id; }
    public function getCode() { return $this->code; }
    public function getIssuedAt() { return $this->issued_at; }
    public function getPdfPath() { return $this->pdf_path; }
    
    public function setPdfPath($path) { $this->pdf_path = $path; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'code' => $this->code,
            'issued_at' => $this->issued_at,
            'pdf_path' => $this->pdf_path
        ];
    }
}
