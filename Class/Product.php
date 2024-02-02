<?php

class Product {
    public $productId;
    public $productName;
    public $price;
    public $materials;
    public $description;
    public $history;
    public $link;

    public $sizes;

    public $pictures;
    public $creator;

    public function __construct($productId, $productName, $price, $materials, $description, $history, $link, $sizes, $pictures, $creator) {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->price = $price;
        $this->materials = $materials;
        $this->description = $description;
        $this->history = $history;
        $this->link = $link;
        $this->sizes = $sizes;
        $this->pictures = $pictures;
        $this->creator = $creator;
    }

    // Méthode pour convertir l'objet en tableau associatif
    public function toArray() {
        return array(
            'productId' => $this->productId,
            'productName' => $this->productName,
            'price' => $this->price,
            'materials' => $this->materials,
            'description' => $this->description,
            'history' => $this->history,
            'link' => $this->link,
            'sizes' => $this->sizes,
            'pictures' => $this->pictures,
            'creator' => $this->creator
        );
    }

    // Getters
    public function getProductId() {
        return $this->productId;
    }

    public function getProductName() {
        return $this->productName;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getMaterials() {
        return $this->materials;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHistory() {
        return $this->history;
    }

    public function getLink() {
        return $this->link;
    }

    // Setters
    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setProductName($productName) {
        $this->productName = $productName;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setMaterials($materials) {
        $this->materials = $materials;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setHistory($history) {
        $this->history = $history;
    }

    public function setLink($link) {
        $this->link = $link;
    }
}


