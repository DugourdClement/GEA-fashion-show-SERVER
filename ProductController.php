<?php
include "ConnexionBdd.php";
include "Class/Product.php";
include "Class/BddProduct.php";
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Allow only POST method
    header("Access-Control-Allow-Methods: POST, GET");
    // Allow headers like Authorization, Content-Type, etc.
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit;
}
$connexionBdd = new ConnexionBdd();
$bddProduct = new BddProduct($connexionBdd);
$json = file_get_contents('php://input');
// Décoder le JSON en tableau associatif
$data = json_decode($json, true);
//if(!empty($_GET["productId"])) {
if(isset($_POST["name"]) && !empty($_POST["name"])) {
//socials creatorLink creator
    if(!isset($_POST["materials"]))
        $_POST["materials"] = "";
    if(!isset($_POST["description"]))
        $_POST["description"] = "";
    if(!isset($_POST["materials"]))
        $_POST["history"] = "";
    if(!isset($_POST["history"]))
        $_POST["history"] = "";
    if(!isset($_POST["creatorLink"]))
        $_POST["creatorLink"] = "";
    $pictures = [];
    if(isset($_FILES["images"])) {
        $files = $_FILES["images"];
        $pictures = []; // Initialisez le tableau pour stocker les chemins des images

        // Boucle à travers les fichiers
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files["name"][$i];
            $fileTmpName = $files["tmp_name"][$i];
            $fileSize = $files["size"][$i];
            $fileError = $files["error"][$i];
            $fileType = $files["type"][$i];

            // Définir le chemin de destination où enregistrer l'image sur le serveur
            $upload_dir = "uploads/";
            $upload_path = $upload_dir . $fileName;

            // Déplacer le fichier téléchargé vers le chemin de destination spécifié
            if (move_uploaded_file($fileTmpName, $upload_path)) {
                // Stockez le chemin de l'image dans le tableau
                $pictures[] = "https://saes5mode.alwaysdata.net/" . $upload_path;
            } else {
                // Gérez les erreurs d'enregistrement de l'image
                echo json_encode("Une erreur s'est produite lors de l'enregistrement de l'image.");
                exit;
            }
        }
    }
    echo json_encode($bddProduct->add($_POST["name"], $_POST["price"], $_POST["materials"], $_POST["description"], $_POST["history"], $_POST["creatorLink"],
        $_POST["size"], $_POST["creatorId"], $pictures));
    exit;
}
else if (isset($data["product_id"]) && !empty($data["product_id"])) {
//socials creatorLink creator
    //supprimer l'image du serveur a faire


    echo json_encode($bddProduct->remove($data["product_id"]));
    exit;
}

//else if (isset($_GET["creators"])) {
//    $products = $bddProduct->getAllProducts();
//
//    $jsonData = array_map(function($products) {
//        return $products->toArray();
//    }, $products);
//
//    echo json_encode($jsonData);
//    exit;
//}

else if(isset($_GET["productAdmin"])) {
    $products = $bddProduct->getAllProducts();
    $productsFinal = [];

// Parcourir chaque produit
    foreach ($products as $product) {
            // Si le créateur n'existe pas, initialiser un tableau vide pour ses produits
        $productsFinal[] = array($product->productId, $product->productName, $product->price, $product->link, $product->creator->creatorId,
            $product->creator->orgName, $product->creator->lastName,$product->creator->firstName);
    }
    echo json_encode($productsFinal);
    exit;
}

else {
    $products = $bddProduct->getAllProducts();
    $productEnd = [];
    $creators = [];
// Parcourir chaque produit
    foreach ($products as $product) {
        // Récupérer l'ID du créateur pour ce produit
        if(!isset($product->creator->orgName))
            $creatorOrgName = "";
        else {
            $creatorOrgName = $product->creator->orgName;
        }
        if(!isset($product->creator->lastName))
            $creatorLastName = "";
        else {
            $creatorLastName = $product->creator->lastName;
        }
        if(!isset($product->creator->firstName))
            $creatorFirstName = "";
        else {
            $creatorFirstName = $product->creator->firstName;
        }

        $creatorId = $product->creator->creatorId;
        $arrayCreator = array($creatorId, $creatorOrgName, $creatorFirstName, $creatorLastName);
        // Vérifier si le créateur existe déjà dans le tableau
        if (!in_array($arrayCreator, $creators)) {
            // Si le créateur n'existe pas, initialiser un tableau vide pour ses produits
            $creators[] = array($creatorId, $creatorOrgName, $creatorFirstName, $creatorLastName);
        }
    }
        // Ajouter le produit au tableau des produits du créateur}
    for($i=0; $i<count($creators); $i=$i+1) {
        $creators[$i][] = $bddProduct->getAllProductsByCreator($creators[$i][0]);
        $productEnd[] = $creators[$i];
    }
//    $jsonData = array_map(function($products) {
//        return $products->toArray();
//    }, $products);
    echo json_encode($productEnd);
    exit;
}
//    $imagePath = 'image.jpg';
//    $compressedImagePath = 'compressed_image.jpg';
//    $quality = 50; // Valeur de qualité entre 0 et 100
//    $image = imagecreatefromjpeg($imagePath);
//    imagejpeg($image, $compressedImagePath, $quality);
//    $compressedImagePath = 'compressed_image.jpg';
//
//    // Lire le contenu de l'image compressée
//    $compressedImageData = file_get_contents($compressedImagePath);
//
//    // Convertir les données de l'image en base64
//    $base64Image = base64_encode($compressedImageData);
//    echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Image depuis Base64">';

//var_dump(strlen($base64Image));
    ?>


