<?php
class BddCreator {
    public $connexion;

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

    public function add($orgName, $firstName, $lastName, $gerant, $email, $tel, $instagram, $facebook, $pinterest, $numPassage, $nbTenues, $mannequin) {
        $stmt = $this->connexion->prepare('INSERT INTO CREATOR (ORG_NAME, FIRSTNAME, LASTNAME, GERANT, EMAIL, TEL, INSTAGRAM, FACEBOOK, PINTEREST, NUM_PASSAGE, NB_TENUES, MANNEQUIN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$orgName, $firstName, $lastName, $gerant, $email, $tel, $instagram, $facebook, $pinterest, $numPassage, $nbTenues, $mannequin]);
    }

    public function getAllCreators() {
        $stmt = $this->connexion->query('SELECT * FROM CREATOR');
        $creators = [];
        while ($creatorData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $creators[] = $creatorData;
        }
        return $creators;
    }


    public function remove($creatorId) {
        // Supprimer tous les produits associés à ce créateur
        $stmt = $this->connexion->prepare('SELECT * FROM PRODUCT WHERE CREATOR_ID = ?');
        $stmt->execute([$creatorId]);
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Supprimer les enregistrements liés dans la table SIZE_PRODUCT
            $stmtSizeProduct = $this->connexion->prepare('DELETE FROM SIZE_PRODUCT WHERE PRODUCT_ID = ?');
            $stmtSizeProduct->execute([$data["PRODUCT_ID"]]);

            // Supprimer les enregistrements liés dans la table PICTURE
            $stmtPicture = $this->connexion->prepare('DELETE FROM PICTURE WHERE PRODUCT_ID = ?');
            $stmtPicture->execute([$data["PRODUCT_ID"]]);

            // Supprimer l'enregistrement du produit dans la table PRODUCT
            $stmtProduct = $this->connexion->prepare('DELETE FROM PRODUCT WHERE PRODUCT_ID = ?');
            $stmtProduct->execute([$data["PRODUCT_ID"]]);
        }

        try {
            // Supprimer le créateur de la table CREATOR
            $stmt = $this->connexion->prepare('DELETE FROM CREATOR WHERE CREATOR_ID = ?');
            $stmt->execute([$creatorId]);

            return true; // Succès de la suppression
        } catch (PDOException $e) {
            // Gérer les erreurs éventuelles
            return false; // Échec de la suppression
        }
    }

    public function getAllCreatorsByName($pattern)
    {
        $pattern = "%$pattern%"; // Ajout de wildcards pour rechercher n'importe quelle correspondance dans le nom

        $stmt = $this->connexion->prepare('SELECT CREATOR_ID, ORG_NAME, FIRSTNAME, LASTNAME FROM CREATOR WHERE FIRSTNAME LIKE ? OR LASTNAME LIKE ? OR ORG_NAME LIKE ?');
        $stmt->execute([$pattern, $pattern, $pattern]);

        $creators = [];
        while ($creatorData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $creators[] = $creatorData;
        }
        return $creators;
    }



}
