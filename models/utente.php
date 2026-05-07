<?php

class Utente{
  private $conn;
  private $table_name = "utente";

//Dichiaro gli attributi della classe utente e parto con la connessione al db
  public $id_utente;
  public $nome;
  public $cognome;
  public $email;
  public $password;
  public $ruolo;

  function __construct($db){
    $this->conn = $db;
  }

  function create(){

    //Creo la query
    $query = "INSERT INTO "
            .$this->table_name .
            " SET
            nome =:nome,
            cognome =:cognome,
            email =:email,
            password =:password,
            ruolo =:ruolo";


    //Preparo la query
    $stmt = $this->conn->prepare($query);

    //Sanitizzazione necessaria per evitare possibili attacchi che sfruttano i tag html
     $this->nome = htmlspecialchars(strip_tags($this->nome));
     $this->cognome = htmlspecialchars(strip_tags($this->cognome));
     $this->email = htmlspecialchars(strip_tags($this->email));
     $this->ruolo = htmlspecialchars(strip_tags($this->ruolo));

     // Utilizziamo l'algoritmo BCRYPT che genera una stringa di 60 caratteri
      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

     // Binding dei parametri per evitare le SQL Injection
    $stmt->bindParam(":nome", $this->nome);
    $stmt->bindParam(":cognome", $this->cognome);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $password_hash); // Salviamo l'hash (non la password in chiaro)
    $stmt->bindParam(":ruolo", $this->ruolo);

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