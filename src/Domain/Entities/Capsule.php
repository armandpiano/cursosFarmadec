<?php

namespace Farmadec\Domain\Entities;

/**
 * Entidad Capsule (Lección)
 */
class Capsule
{
    /** @var int|null */
    private $id;
    
    /** @var int */
    private $module_id;
    
    /** @var int */
    private $number;
    
    /** @var string */
    private $title;
    
    /** @var string|null */
    private $description;
    
    /** @var string|null */
    private $video_url;
    
    /** @var string|null */
    private $thumb_url;
    
    /** @var int */
    private $page_order;
    
    /** @var string|null */
    private $created_at;
    
    public function __construct($module_id, $number, $title, $description = null, $video_url = null, $thumb_url = null, $page_order = 0)
    {
        $this->module_id = $module_id;
        $this->number = $number;
        $this->title = $title;
        $this->description = $description;
        $this->video_url = $video_url;
        $this->thumb_url = $thumb_url;
        $this->page_order = $page_order;
    }
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getModuleId() { return $this->module_id; }
    public function getNumber() { return $this->number; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getVideoUrl() { return $this->video_url; }
    public function getThumbUrl() { return $this->thumb_url; }
    public function getPageOrder() { return $this->page_order; }
    public function getCreatedAt() { return $this->created_at; }
    
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setVideoUrl($url) { $this->video_url = $url; }
    public function setThumbUrl($url) { $this->thumb_url = $url; }
    public function setPageOrder($order) { $this->page_order = $order; }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,
            'number' => $this->number,
            'title' => $this->title,
            'description' => $this->description,
            'video_url' => $this->video_url,
            'thumb_url' => $this->thumb_url,
            'page_order' => $this->page_order,
            'created_at' => $this->created_at
        ];
    }
}
