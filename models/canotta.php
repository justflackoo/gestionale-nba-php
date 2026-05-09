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
    $query = "INSERT INTO "
              . $this->table_name .
              "
              SET
                giocatore=:giocatore, 
                squadra=:squadra, 
                numero=:numero, 
                tipo=:tipo, 
                anno=:anno, 
                prezzo_originale=:prezzo_originale, 
                percentuale_sconto=:percentuale_sconto";

     //Query creata, ora tocca prepararla
     $stmt = $this->conn->prepare($query);

     //Sanitizzazione
      $this->giocatore = htmlspecialchars(strip_tags($this->giocatore));
      $this->squadra = htmlspecialchars(strip_tags($this->squadra));
      $this->numero = htmlspecialchars(strip_tags($this->numero));
      $this->tipo = htmlspecialchars(strip_tags($this->tipo));
      $this->anno = htmlspecialchars(strip_tags($this->anno));
      $this->prezzo_originale = htmlspecialchars(strip_tags($this->prezzo_originale));
      $this->percentuale_sconto = htmlspecialchars(strip_tags($this->percentuale_sconto));

      // Binding dei parametri
      $stmt->bindParam(":giocatore", $this->giocatore);
      $stmt->bindParam(":squadra", $this->squadra);
      $stmt->bindParam(":numero", $this->numero);
      $stmt->bindParam(":tipo", $this->tipo);
      $stmt->bindParam(":anno", $this->anno);
      $stmt->bindParam(":prezzo_originale", $this->prezzo_originale);
      $stmt->bindParam(":percentuale_sconto", $this->percentuale_sconto);
        
      if($stmt->execute()){
          return true;
      }
      return false;

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

 function update() {
    
    $query = "UPDATE " . $this->table_name . "
              SET
                giocatore = :giocatore,
                squadra = :squadra,
                numero = :numero,
                tipo = :tipo,
                anno = :anno,
                prezzo_originale = :prezzo_originale,
                percentuale_sconto = :percentuale_sconto
              WHERE
                id_canotta = :id";

    
    $stmt = $this->conn->prepare($query);

    //Sanitizzazione dei dati 
    $this->giocatore = htmlspecialchars(strip_tags($this->giocatore));
    $this->squadra = htmlspecialchars(strip_tags($this->squadra));
    $this->numero = htmlspecialchars(strip_tags($this->numero));
    $this->tipo = htmlspecialchars(strip_tags($this->tipo));
    $this->anno = htmlspecialchars(strip_tags($this->anno));
    $this->prezzo_originale = htmlspecialchars(strip_tags($this->prezzo_originale));
    $this->percentuale_sconto = htmlspecialchars(strip_tags($this->percentuale_sconto));
    $this->id_canotta = htmlspecialchars(strip_tags($this->id_canotta));

    //Binding dei parametri
    $stmt->bindParam(":giocatore", $this->giocatore);
    $stmt->bindParam(":squadra", $this->squadra);
    $stmt->bindParam(":numero", $this->numero);
    $stmt->bindParam(":tipo", $this->tipo);
    $stmt->bindParam(":anno", $this->anno);
    $stmt->bindParam(":prezzo_originale", $this->prezzo_originale);
    $stmt->bindParam(":percentuale_sconto", $this->percentuale_sconto);
    $stmt->bindParam(":id", $this->id_canotta);

    
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

  function delete() {
    
    $query = "DELETE FROM " 
            . $this->table_name . 
            " WHERE id_canotta = :id";

   
    $stmt = $this->conn->prepare($query);

    // Sanitizzazione
    $this->id_canotta = htmlspecialchars(strip_tags($this->id_canotta));

    //Binding dell' ID
    $stmt->bindParam(":id", $this->id_canotta);

   
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

 

}


?>