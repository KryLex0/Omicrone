<?php

/**
 * Classe d'accès aux données.

 * Utilise les services de la classe PDO
 * pour l'application Commission
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoCommission qui contiendra l'unique instance de la classe

 */
class PdoCommission extends pdo {

    private static $serveur = 'pgsql:host=127.0.0.1';
    private static $bdd = 'dbname=omicrone';
    private static $user = 'user=postgres';
    private static $password = 'password=root';
    private static $port="port=5432";
    private static $monPdo;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    public function __construct() {
      try{
        PdoCommission::$monPdo = parent :: __construct(PdoCommission::$serveur.';'.PdoCommission::$port.';'.PdoCommission::$bdd.';'.PdoCommission::$user.';'.PdoCommission::$password);
      }
      catch (PDOException $e) {
        echo $e;
      }
    }

    public function _destruct() {
        PdoCommission::$monPdo = null;
    }

    public static function getInstance() {
        if (!(self::$monPdo instanceof self))
            self::$monPdo = new self();
        return self::$monPdo;
    }

}




?>
