<?php
/**
 * Classe Database - Gestion de la connexion à la base de données
 */

class Database {
    private $host;
    private $name;
    private $user;
    private $pass;
    private $pdo;
    private $error;

    public function __construct($host, $name, $user, $pass) {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
        $this->connect();
    }

    /**
     * Connecter à la base de données
     */
    private function connect() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name . ';charset=utf8mb4';
        
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }

    /**
     * Exécuter une requête
     */
    public function query($sql) {
        return $this->pdo->prepare($sql);
    }

    /**
     * Récupérer l'ID de la dernière insertion
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Vérifier la connexion
     */
    public function isConnected() {
        return $this->pdo !== null;
    }

    /**
     * Obtenir l'instance PDO
     */
    public function getPDO() {
        return $this->pdo;
    }
}
?>