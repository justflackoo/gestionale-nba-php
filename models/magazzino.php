<?php

class Magazzino{

  private $conn;
  private $table_name = "magazzino";

  public $id_giacenza;
  public $id_canotta;
  public $id_taglia;
  public $quantita_disponibile;



  public function __construct($db){
    $this->conn = $db;
  }

  function create(){
    $query = "INSERT INTO " 
              . $this->table_name ."
              SET 
              id_canotta=:id_canotta,
              id_taglia =:id_taglia,
              quantita_disponibile =:quantita_disponibile";

  $stmt = $this->conn->prepare($query);

  $this->id_canotta = htmlspecialchars(strip_tags($this->id_canotta));
  $this->id_taglia = htmlspecialchars(strip_tags($this->id_taglia));
  $this->quantita_disponibile = htmlspecialchars(strip_tags($this->quantita_disponibile));

  $stmt->bindParam(":id_canotta", $this->id_canotta);
  $stmt->bindParam(":id_taglia", $this->id_taglia);
  $stmt->bindParam(":quantita_disponibile", $this->quantita_disponibile);

  // Esecuzione
    if($stmt->execute()){
        return true;
    }
    return false;

  }
  
  function read(){}

  function update(){}

  function delete(){}


}


?>