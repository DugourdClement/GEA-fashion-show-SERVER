<?php

class ConnexionBdd {
    private $host = "mysql-saes5mode.alwaysdata.net";
    private $dbname = "saes5mode_bdd";
    private $username = "saes5mode";
    private $password = "AdminSaeS5Mode.";
    private $pdo;
    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            exit();
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
