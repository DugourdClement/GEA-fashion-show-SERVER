<?php
include "ConnexionBdd.php";
include "Class/BddAdmin.php";
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Allow only POST method
    header("Access-Control-Allow-Methods: POST");
    // Allow headers like Authorization, Content-Type, etc.
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit;
}
$json = file_get_contents('php://input');

// Décoder le JSON en tableau associatif
$data = json_decode($json, true);
$userEmail = $data["userEmail"];
$userPassword = $data["userPassword"];

if($userEmail && $userPassword) {
    $connexionBdd = new ConnexionBdd();
    $bddAdmin = new BddAdmin($connexionBdd);
//    $bddAdmin->connexion($_GET["userId"], $_GET["userPassword"]);
//    var_dump(password_hash("test",PASSWORD_BCRYPT));
    echo json_encode($bddAdmin->connexion($userEmail, $userPassword));
}
else {
    echo json_encode("non");
}
