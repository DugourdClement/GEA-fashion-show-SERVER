<?php

class Picture {
    public $pictureId;
    public $link;
    public $productId;

    public function __construct($pictureId, $link, $productId) {
        $this->pictureId = $pictureId;
        $this->link = $link;
        $this->productId = $productId;
    }

    // Méthode pour convertir l'objet en tableau associatif
    public function toArray() {
        return array(
            'pictureId' => $this->pictureId,
            'link' => $this->link,
            'productId' => $this->productId
        );
    }

    // Getters
    public function getPictureId() {
        return $this->pictureId;
    }

    public function getLink() {
        return $this->link;
    }

    public function getProductId() {
        return $this->productId;
    }

    // Setters
    public function setPictureId($pictureId) {
        $this->pictureId = $pictureId;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }
}
