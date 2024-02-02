<?php

class BddPicture {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion->getPdo();
    }

    public function getPicture($pictureId) {
        $stmt = $this->connexion->prepare('SELECT * FROM PICTURE WHERE PICTURE_ID = ?');
        $stmt->execute([$pictureId]);
        $pictureData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pictureData) {
            return new Picture($pictureData['PICTURE_ID'], $pictureData['LINK'], $pictureData['PRODUCT_ID']);
        } else {
            return null;
        }
    }

    public function getAllPicturesOfProduct($productId) {
        $stmt = $this->connexion->prepare('SELECT * FROM PICTURE WHERE PRODUCT_ID = ?');
        $stmt->execute([$productId]);
        $pictures = [];
        while ($pictureData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pictures[] = new Picture($pictureData['PICTURE_ID'], $pictureData['LINK'], $pictureData['PRODUCT_ID']);
        }
        return $pictures;
    }
}
