<?php

class Creator {
    public $creatorId;

    public $orgName;

    public $lastName;
    public $firstName;

    public $gerant;

    public $email;

    public $tel;

    public $instagram;

    public $facebook;

    public $pinterest;

    public $numPassage;

    public $nbTenues;

    public $mannequin;

    public function __construct($creatorId, $orgName, $lastName, $firstName, $gerant, $email, $tel, $instagram,
$facebook, $pinterest, $numPassage, $nbTenues, $mannequin) {
        $this->creatorId = $creatorId;
        $this->orgName = $orgName;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->gerant = $gerant;
        $this->email = $email;
        $this->tel = $tel;
        $this->instagram = $instagram;
        $this->facebook = $facebook;
        $this->pinterest = $pinterest;
        $this->numPassage = $numPassage;
        $this->nbTenues = $nbTenues;
        $this->mannequin = $mannequin;
    }
}


