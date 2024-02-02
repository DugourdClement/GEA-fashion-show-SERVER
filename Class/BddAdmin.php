<?php

class BddAdmin
{
    private $connexion;
    public function __construct($connexion) {
        $this->connexion = $connexion->getPdo();
    }


    public function connexion($email, $userPassword)
    {
        $query = "SELECT PASSWORD FROM ADMIN WHERE EMAIL = :email";
        $stmt = $this->connexion->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $hashedPassword = $stmt->fetchColumn();

        // Vérifier si le mot de passe hashé correspond au mot de passe fourni
        if (password_verify($userPassword, $hashedPassword)) {
            // Le mot de passe est correct
            return true;
        } else {
            // Le mot de passe est incorrect
            return false;
        }
    }

}