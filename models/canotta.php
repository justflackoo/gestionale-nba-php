<?php

class Canotta{

  private $conn;
  private $table_name = "canotta";

  public $id_canotta;
  public $giocatore;
  public $squadra;
  public $numero;
  public $tipo;
  public $anno;
  public $prezzo_originale;
  public $percentuale_sconto;


  public function __construct($db){
    $this->conn = $db;
  }

  function create(){

  }

  function read(){

        $query = "SELECT
                        id_canotta AS id, 
                        giocatore, 
                        squadra, 
                        numero, 
                        tipo, 
                        anno, 
                        prezzo_originale, 
                        percentuale_sconto, 
                       (prezzo_originale - (prezzo_originale * percentuale_sconto / 100)) AS prezzo_finale
                  FROM
                  "     .$this->table_name;

        //Dopo aver creato la stringa corrispondente a quella query non devo far altro che prepararla ed eseguirla
        $stmt = $this->conn->prepare($query);
        
        //Invio il comando al DB
        $stmt->execute();
        
        return $stmt;
    
  }

  function delete(){
    
  }

  function update(){
    
  }

}


?>