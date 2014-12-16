<?php

require_once ('configBdd.php');

class Bdd{

  private static $instance = false;
  protected $connexion;


  private function __construct() {
      
      $this->connexion = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER, PASSWORD);
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }


  static public function getInstance() {
      
    if (self::$instance === false) {
        
      self::$instance = new self();
    }
    
    return self::$instance;
  }

  public function getConnexion() {
    return $this->connexion;
  }
}

?>