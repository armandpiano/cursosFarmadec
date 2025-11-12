<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Module
 */
class Module
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $course_id;
    
    /** @var int */
    private $number;
    
    /** @var string */
    private $title;
    
    /** @var string|null */
    private $description;
    
    /** @var string|null */
    private $image_url;
    
    /** @var bool */
    private $is_active;
    
    /** @var int */
    private $position;
    
    /** @var string|null */
    private $created_at;
    
    public function __construct($course_id, $number, $title, $description = null, $image_url = null, $position = 0, $is_active = true)
    {
        $this->course_id = $course_id;
        $this->number = $number;
        $this->title = $title;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->position = $position;
        $this->is_active = $is_active;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getCourseId() { return $this->course_id; }
    public function getNumber() { return $this->number; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getImageUrl() { return $this->image_url; }
    public function isActive() { return $this->is_active; }
    public function getPosition() { return $this->position; }
    public function getCreatedAt() { return $this->created_at; }
    
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setImageUrl($url) { $this->image_url = $url; }
    public function setPosition($position) { $this->position = $position; }
    public function setIsActive($active) { $this->is_active = $active; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'number' => $this->number,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'position' => $this->position,
            'created_at' => $this->created_at
        ];
    }
}
