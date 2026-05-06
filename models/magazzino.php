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
  
  function read(){
    $query = "SELECT
              m.id_giacenza,
              m.id_canotta, 
              
              c.giocatore,
              t.nome_taglia, 
              m.quantita_disponibile
              FROM "
              . $this->table_name . " m
              INNER JOIN 
              canotta c ON m.id_canotta = c.id_canotta
              INNER JOIN 
              tabella_taglie t ON m.id_taglia = t.id_taglia
              ORDER BY
              c.giocatore ASC";

    //preparo la query
    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
    
    }

  function update(){

    $query = "UPDATE " .$this->table_name . " 
              SET
              id_canotta =:id_canotta,
              id_taglia =:id_taglia,
              quantita_disponibile =:quantita_disponibile
              WHERE
              id_giacenza =:id_giacenza";

    $stmt = $this->conn->prepare($query);

    $this->id_giacenza = htmlspecialchars(strip_tags($this->id_giacenza));
    $this->id_canotta = htmlspecialchars(strip_tags($this->id_canotta));
    $this->id_taglia = htmlspecialchars(strip_tags($this->id_taglia));
    $this->quantita_disponibile = htmlspecialchars(strip_tags($this->quantita_disponibile));

    $stmt->bindParam(":id_giacenza", $this->id_giacenza);
    $stmt->bindParam(":id_canotta", $this->id_canotta);
    $stmt->bindParam(":id_taglia", $this->id_taglia);
    $stmt->bindParam(":quantita_disponibile", $this->quantita_disponibile);

    if ($stmt->execute()) {
        //Se ha trovato una corrispondenza restituisco TRUE
    if ($stmt->rowCount() > 0) {
        return true;
    }
    // Se rowCount è 0 = non ci sono corrispondenze oppure i dati sono identici
    return false;
    }
   

  }

  function delete(){}


}


?>