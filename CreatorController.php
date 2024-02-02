<?php
include "ConnexionBdd.php";
include "Class/Product.php";
include "Class/BddCreator.php";
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Allow only POST method
    header("Access-Control-Allow-Methods: POST, GET");
    // Allow headers like Authorization, Content-Type, etc.
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit;
}
$connexionBdd = new ConnexionBdd();
$bddCreator = new BddCreator($connexionBdd);
$json = file_get_contents('php://input');
// Décoder le JSON en tableau associatif
$data = json_decode($json, true);
//if(!empty($_GET["productId"])) {
//numero //mail


if(!isset($data["orgName"]))
    $data["orgName"] = "";
if(!isset($data["firstname"]))
    $data["firstname"] = "";
if(!isset($data["lastname"]))
    $data["lastname"] = "";
$data["mannequin"] = "";

if(!isset($data["gerant"]))
    $data["gerant"] = "";
if(!isset($data["mail"]))
    $data["mail"] = "";
if(!isset($data["phoneNumber"]))
    $data["phoneNumber"] = "";
if(!isset($data["instagram"]))
    $data["instagram"] = "";
if(!isset($data["facebook"]))
    $data["facebook"] = "";
if(!isset($data["pinterest"]))
    $data["pinterest"] = "";


if($data["orgName"] != "" || $data["lastname"] != "") {
    if(!isset($data["orgName"]))
        $data["orgName"] = "";
    if(!isset($data["firstname"]))
        $data["firstname"] = "";
    if(!isset($data["lastname"]))
        $data["lastname"] = "";
    $data["mannequin"] = "";
    $retourAddCreator = $bddCreator->add($data["orgName"], $data["firstname"], $data["lastname"], $data["gerant"],
        $data["mail"], $data["phoneNumber"], $data["socials"]["instagram"], $data["socials"]["facebook"], $data["socials"]["pinterest"], $data["number"], $data["nbOutfits"], $data["mannequin"]);
    echo json_encode($retourAddCreator);
    exit;
}

else if (isset($_GET["selectCreators"])) {
    $creators = $bddCreator->getAllCreatorsByName($_GET["selectCreators"]);
    echo json_encode($creators);
}



else if(isset($data["CREATOR_ID"]) && isset($data["CREATOR_ID"])) {

    $retourRemoveCreator = $bddCreator->remove($data["CREATOR_ID"]);
    echo json_encode($retourRemoveCreator);
    exit;
}

else {

    $creators = $bddCreator->getAllCreators();
    echo json_encode($creators);
//    $jsonData = array_map(function ($creators) {
//        return $creators->toArray();
//    }, $creators);
//    echo json_encode($jsonData);
//    exit;
}
    ?>


