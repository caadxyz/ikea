<?php

class Product {

    public $name;
    public $description;
    public $keyword;  
    public $image;
    public $weight;
    public $height;
    public $width;
    public $nbPackage;
    public $category;

    public function __construct($map) {
        $this->name = isset($map["name"]) ? $map["name"] : "";
        $this->description = isset($map["description"])  ? $map["description"] : "";
        $this->keyword = isset($map["keyword"]) ? $map["keyword"] : "";
        $this->image =  isset($map["image"]) ? $map["image"] : "";
        $this->weight = isset($map["weight"]) ? $map["weight"] : "";
        $this->height = isset($map["height"]) ? $map["height"] : "";
        $this->width = isset($map["width"]) ? $map["width"] : "";
        $this->nbPackage = isset($map["nbPackage"]) ? $map["nbPackage"]  : "";
        $this->category = isset($map["category"]) ? $map["category"]  : "";
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getWidth(){
        return $this->width;
    }

    public function getNbPackage(){
        return $this->nbPackage;
    }

    public function getKeyword(){
        return $this->keyword;
    }

    public function getCategory(){
        return $this->category;
    }
}

?>
