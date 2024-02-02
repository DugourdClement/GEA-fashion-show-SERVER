<?php

class BddUser {
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion->getPdo();
    }

    public function getUser($userId) {
        $stmt = $this->connexion->prepare('SELECT * FROM USER WHERE USER_ID = ?');
        $stmt->execute([$userId]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            return new User($userData['USER_ID'], $userData['FIRST_NAME'], $userData['LAST_NAME'], $userData['EMAIL'], $userData['PASSWORD'], $userData['INSCRIPTION_TYPE']);
        } else {
            return null;
        }
    }

    public function getAllUsers() {
        $stmt = $this->connexion->query('SELECT * FROM USER');
        $users = [];
        while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($userData['USER_ID'], $userData['FIRST_NAME'], $userData['LAST_NAME'], $userData['EMAIL'], $userData['PASSWORD'], $userData['INSCRIPTION_TYPE']);
        }
        return $users;
    }
}
