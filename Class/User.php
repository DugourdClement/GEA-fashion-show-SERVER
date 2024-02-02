<?php

class User {
    public $userId;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $inscriptionType;

    public function __construct($userId, $firstName, $lastName, $email, $password, $inscriptionType) {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->inscriptionType = $inscriptionType;
    }

    // Méthode pour convertir l'objet en tableau associatif
    public function toArray() {
        return array(
            'userId' => $this->userId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'inscriptionType' => $this->inscriptionType
        );
    }
    // Getters
    public function getUserId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getInscriptionType() {
        return $this->inscriptionType;
    }

    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setInscriptionType($inscriptionType) {
        $this->inscriptionType = $inscriptionType;
    }
}

