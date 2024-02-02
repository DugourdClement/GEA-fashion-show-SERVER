<?php
include "ConnexionBdd.php";
include "Class/BddUser.php";
include "Class/User.php";

if($_GET["userInscrit"] == "oui") {
    $connexionBdd = new ConnexionBdd();
    $bddUser = new BddUser($connexionBdd);

    $nbUsers = $bddUser->getAllUsers();
//    $jsonData = array_map(function($nbUsers) {
//        return $nbUsers->toArray();
//    }, $nbUsers);

     echo json_encode($nbUsers);
     exit;
}