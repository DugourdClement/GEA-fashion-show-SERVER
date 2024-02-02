<?php
include "Creator.php";
class BddProduct {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion->getPdo();
    }

//    public function getProduct($productId) {
//        $stmt = $this->connexion->prepare('SELECT * FROM Product WHERE USER_ID = ?');
//        $stmt->execute([$productId]);
//        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        if ($productData) {
//            return new Product($productData['PRODUCT_ID'], $productData['PRODUCT_NAME'], $productData['PRICE'], $productData['MATERIALS'], $productData['DESCRIPTION'], $productData['HISTORY'], $productData['LINK']);
//        } else {
//            return null;
//        }
//    }

    public function getAllProducts() {
        $stmt = $this->connexion->query('SELECT * FROM PRODUCT');
        $products = [];
        while ($productData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stmtSize = $this->connexion->prepare('SELECT SIZE.SIZE FROM SIZE_PRODUCT, SIZE  WHERE SIZE_PRODUCT.SIZE_ID = SIZE.SIZE_ID AND SIZE_PRODUCT.PRODUCT_ID = ?');
            $stmtSize->execute([$productData['PRODUCT_ID']]);
            $sizes = [];
            while ($size = $stmtSize->fetchColumn()) {
                $sizes[] = $size;
            }
            $stmtPictures = $this->connexion->prepare('SELECT PICTURE.LINK FROM PICTURE WHERE PICTURE.PRODUCT_ID = ?');
            $stmtPictures->execute([$productData['PRODUCT_ID']]);
            $pictures = [];
            while ($picture = $stmtPictures->fetchColumn()) {
                $pictures[] = $picture;
            }

            $stmtCreator = $this->connexion->prepare('SELECT * FROM CREATOR WHERE CREATOR_ID = ?');
            $stmtCreator->execute([$productData["CREATOR_ID"]]);
            $creatorData = $stmtCreator->fetch(PDO::FETCH_ASSOC);
            $creator = new Creator($creatorData["CREATOR_ID"], $creatorData["ORG_NAME"], $creatorData["LASTNAME"], $creatorData["FIRSTNAME"],
                $creatorData["GERANT"], $creatorData["EMAIL"], $creatorData["TEL"], $creatorData["INSTAGRAM"], $creatorData["FACEBOOK"], $creatorData["PINTEREST"],
                $creatorData["NUM_PASSAGE"], $creatorData["NB_TENUES"], $creatorData["MANNEQUIN"]);


            $products[] = new Product($productData['PRODUCT_ID'], $productData['PRODUCT_NAME'], $productData['PRICE'], $productData['MATERIALS'], $productData['DESCRIPTION'],
                $productData['HISTORY'], $productData['LINK'], $sizes, $pictures, $creator);
            unset($sizes, $pictures, $creator);
        }
        return $products;
    }

//    public function getAllProductsByCreators($creators) {
//        $stmt = $this->connexion->query('SELECT * FROM PRODUCT');
//        $products = [];
//        while ($productData = $stmt->fetch(PDO::FETCH_ASSOC)) {
//            $stmtSize = $this->connexion->prepare('SELECT SIZE.SIZE FROM SIZE_PRODUCT, SIZE  WHERE SIZE_PRODUCT.SIZE_ID = SIZE.SIZE_ID AND SIZE_PRODUCT.PRODUCT_ID = ?');
//            $stmtSize->execute([$productData['PRODUCT_ID']]);
//            $sizes = [];
//            while ($size = $stmtSize->fetchColumn()) {
//                $sizes[] = $size;
//            }
//            $stmtPictures = $this->connexion->prepare('SELECT PICTURE.LINK FROM PICTURE WHERE PICTURE.PRODUCT_ID = ?');
//            $stmtPictures->execute([$productData['PRODUCT_ID']]);
//            $pictures = [];
//            while ($picture = $stmtPictures->fetchColumn()) {
//                $pictures[] = $picture;
//            }
//            $products[] = new Product($productData['PRODUCT_ID'], $productData['PRODUCT_NAME'], $productData['PRICE'], $productData['MATERIALS'], $productData['DESCRIPTION'],
//                $productData['HISTORY'], $productData['LINK'], $sizes, $pictures, $productData['FACEBOOK_LINK'], $productData['INSTAGRAM_LINK'], $productData['PINTEREST_LINK'],
//                $productData['CREATORS']);
//            unset($sizes, $pictures);
//        }
//        return $products;
//    }

    public function add($productName, $price, $materials, $description, $history, $link, $sizes, $creator, $pictures) {
        // Préparation de la requête d'insertion pour les informations de base du produit
        $query = "INSERT INTO PRODUCT (PRODUCT_NAME, PRICE, MATERIALS, DESCRIPTION, HISTORY, LINK, CREATOR_ID) 
              VALUES (:productName, :price, :materials, :description, :history, :link, :creator)";

        // Préparation de la requête
        $statement = $this->connexion->prepare($query);

        // Liaison des paramètres
//        $statement->bindParam(':productId', $productId);
        $statement->bindParam(':productName', $productName);
        $statement->bindParam(':price', $price);
        $statement->bindParam(':materials', $materials);
        $statement->bindParam(':description', $description);
        $statement->bindParam(':history', $history);
        $statement->bindParam(':link', $link);
        $statement->bindParam(':creator', $creator);

        // Exécution de la requête
        if (!$statement->execute()) {
            return false; // Échec de l'insertion des informations de base du produit
        }
        $stmtProductId= $this->connexion->prepare('SELECT PRODUCT_ID FROM PRODUCT WHERE PRODUCT_NAME = ?');
        $stmtProductId->execute([$productName]);
        $productId = $stmtProductId->fetchColumn();

        // Insertion des tailles du produit dans la table de jointure SIZE_PRODUCT
        $taillesPossibles = array("xs","s", "m", "l", "xl");
        $taillesChoisies  = array();
// Parcourir le tableau et récupérer les clés correspondant à la valeur true
        for($i=0; $i<count($taillesPossibles); $i = $i +1) {
            if ($sizes[$i] == "true") {
                $taillesChoisies[] = $taillesPossibles[$i];
            }
        }

        foreach ($taillesChoisies as $size) {
            $stmtSize = $this->connexion->prepare('SELECT SIZE_ID FROM SIZE WHERE SIZE = ?');
            $stmtSize->execute([$size]);
            $sizeId = $stmtSize->fetchColumn();

            $querySize = "INSERT INTO SIZE_PRODUCT (SIZE_ID, PRODUCT_ID) 
                      VALUES (:sizeId, :productId)";
            $statementSize = $this->connexion->prepare($querySize);
            $statementSize->bindParam(':sizeId', $sizeId);
            $statementSize->bindParam(':productId', $productId);
            if (!$statementSize->execute()) {
                return false; // Échec de l'insertion de la taille du produit
            }
        }

         //Insertion des images du produit dans la table PICTURE
        foreach ($pictures as $picture) {

            $queryPicture = "INSERT INTO PICTURE (PICTURE_ID, LINK, PRODUCT_ID) VALUES (:pictureId, :link, :productId)";
            $statementPicture = $this->connexion->prepare($queryPicture);
            $pictureId = uniqid();
            $statementPicture->bindParam(':pictureId', $pictureId);
            $statementPicture->bindParam(':link', $picture);
            $statementPicture->bindParam(':productId', $productId);
            if (!$statementPicture->execute()) {
                return false; // Échec de l'insertion de l'image du produit
            }
        }
        return true; // Succès de l'insertion
    }

    public function remove($productId) {
        try {
            // Supprimer les enregistrements liés dans la table SIZE_PRODUCT
            $stmtSizeProduct = $this->connexion->prepare('DELETE FROM SIZE_PRODUCT WHERE PRODUCT_ID = ?');
            $stmtSizeProduct->execute([$productId]);

            // Supprimer les enregistrements liés dans la table PICTURE
            $stmtPicture = $this->connexion->prepare('DELETE FROM PICTURE WHERE PRODUCT_ID = ?');
            $stmtPicture->execute([$productId]);

            // Supprimer l'enregistrement du produit dans la table PRODUCT
            $stmtProduct = $this->connexion->prepare('DELETE FROM PRODUCT WHERE PRODUCT_ID = ?');
            $stmtProduct->execute([$productId]);

            return true; // Succès de la suppression
        } catch (PDOException $e) {
            // Gérer les erreurs éventuelles
            return false; // Échec de la suppression
        }
    }

    public function getAllProductsByCreator($creatorId) {
        $stmt = $this->connexion->prepare('SELECT * FROM PRODUCT WHERE CREATOR_ID = ?');
        $products = [];
        $stmt->execute([$creatorId]);
        while ($productData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stmtSize = $this->connexion->prepare('SELECT SIZE.SIZE FROM SIZE_PRODUCT, SIZE  WHERE SIZE_PRODUCT.SIZE_ID = SIZE.SIZE_ID AND SIZE_PRODUCT.PRODUCT_ID = ?');
            $stmtSize->execute([$productData['PRODUCT_ID']]);
            $sizes = [];
            while ($size = $stmtSize->fetchColumn()) {
                $sizes[] = $size;
            }
            $stmtPictures = $this->connexion->prepare('SELECT PICTURE.LINK FROM PICTURE WHERE PICTURE.PRODUCT_ID = ?');
            $stmtPictures->execute([$productData['PRODUCT_ID']]);
            $pictures = [];
            while ($picture = $stmtPictures->fetchColumn()) {
                $pictures[] = $picture;
            }

            $stmtCreator = $this->connexion->prepare('SELECT * FROM CREATOR WHERE CREATOR_ID = ?');
            $stmtCreator->execute([$productData["CREATOR_ID"]]);
            $creatorData = $stmtCreator->fetch(PDO::FETCH_ASSOC);
            $creator = new Creator($creatorData["CREATOR_ID"], $creatorData["ORG_NAME"], $creatorData["LASTNAME"], $creatorData["FIRSTNAME"],
                $creatorData["GERANT"], $creatorData["EMAIL"], $creatorData["TEL"], $creatorData["INSTAGRAM"], $creatorData["FACEBOOK"], $creatorData["PINTEREST"],
                $creatorData["NUM_PASSAGE"], $creatorData["NB_TENUES"], $creatorData["MANNEQUIN"]);


            $products[] = new Product($productData['PRODUCT_ID'], $productData['PRODUCT_NAME'], $productData['PRICE'], $productData['MATERIALS'], $productData['DESCRIPTION'],
                $productData['HISTORY'], $productData['LINK'], $sizes, $pictures, $creator);
            unset($sizes, $pictures, $creator);
        }
        return $products;
    }

}
