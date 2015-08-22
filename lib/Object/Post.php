<?php

namespace Campusswap\Object;

class Post {
    
    public static $conn, $sql, $query, $posts, $rowCount;
    
    private $id; 
    private $item; 
    private $description; 
    private $username; 
    private $domain; 
    private $price; 
    private $hits; 
    private $views; 
    private $created; 
    private $modified;
    private $img;


    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getHits() {
        return $this->hits;
    }

    public function setHits($hits) {
        $this->hits = $hits;
    }

    public function getViews() {
        return $this->views;
    }

    public function setViews($views) {
        $this->views = $views;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setModified($modified) {
        $this->modified = $modified;
    }

    public function getImg() {
        return $this->img;
    }

    public function setImg($img) {
        $this->img = $img;
    }

    
}

?>