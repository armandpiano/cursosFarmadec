<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Course
 */
class Course
{
    /** @var int|null */
    private $id;
    
    /** @var string */
    private $slug;
    
    /** @var string */
    private $title;
    
    /** @var string|null */
    private $description;
    
    /** @var string|null */
    private $image_url;
    
    /** @var bool */
    private $is_active;
    
    /** @var string|null */
    private $created_at;
    
    public function __construct($slug, $title, $description = null, $image_url = null, $is_active = true)
    {
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->is_active = $is_active;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getSlug() { return $this->slug; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getImageUrl() { return $this->image_url; }
    public function isActive() { return $this->is_active; }
    public function getCreatedAt() { return $this->created_at; }
    
    public function setSlug($slug) { $this->slug = $slug; }
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setImageUrl($url) { $this->image_url = $url; }
    public function setIsActive($active) { $this->is_active = $active; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at
        ];
    }
}
