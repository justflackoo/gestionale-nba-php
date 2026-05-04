<?php
class Tabella_Taglie{
  private $conn;
  private $table_name = "tabella_taglie";

  public $id_taglia;
  public $nome_taglia;

   public function __construct($db){
    $this->conn = $db;
  }

  function create(){
    $query= "INSERT INTO "
            . $this->table_name .
            " SET nome_taglia =:nome_taglia";

    //Query creata, ora tocca prepararla
     $stmt = $this->conn->prepare($query);


     //Sanitizzazione
     $this->nome_taglia = htmlspecialchars(strip_tags($this->nome_taglia));

     // Binding dei parametri
    $stmt->bindParam(":nome_taglia", $this->nome_taglia);

    if($stmt->execute()){
          return true;
      }
      return false;

     
  }
  function update(){
        
    $query = "UPDATE " . $this->table_name . " SET nome_taglia = :nome_taglia WHERE id_taglia = :id_taglia";

    
    $stmt = $this->conn->prepare($query);

    //Sanitizzazione dei dati 
    $this->id_taglia = htmlspecialchars(strip_tags($this->id_taglia));
    $this->nome_taglia = htmlspecialchars(strip_tags($this->nome_taglia));

    //Binding dei parametri
    $stmt->bindParam(":id_taglia", $this->id_taglia);
    $stmt->bindParam(":nome_taglia", $this->nome_taglia);


    
    if ($stmt->execute()) {
        return true;
    }
    return false;

  }
  function read(){
    $query = "SELECT 
              id_taglia,
              nome_taglia
              FROM "
              . $this->table_name;

   $stmt = $this->conn->prepare($query);

   $stmt->execute();
        
   return $stmt;
  }
  function delete(){
       
    $query = "DELETE FROM " 
            . $this->table_name . 
            " WHERE id_taglia = :id_taglia";

   
    $stmt = $this->conn->prepare($query);

    //Sanitizzazione
    $this->id_taglia = htmlspecialchars(strip_tags($this->id_taglia));

    //Binding dell' ID
    $stmt->bindParam(":id_taglia", $this->id_taglia);

   
    if ($stmt->execute()) {
        return true;
    }
    return false;
  }
  
}
?>