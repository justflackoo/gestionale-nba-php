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

  function login(){ 
  //Verifichiamo se l'email che sta passando l'utente esiste nel mio DB
      $query = "SELECT id_utente, nome, cognome, email, password, ruolo
                FROM ".$this->table_name."
                WHERE email=:email
                LIMIT 0,1";
  
  //Preparo la query
  $stmt=$this->conn->prepare($query);

  // Sanitizzazione e Binding dell'email per questioni di sicurezza
    $this->email = htmlspecialchars(strip_tags($this->email));
    $stmt->bindParam(":email", $this->email);

  // Eseguiamo la query
    $stmt->execute();
    $num = $stmt->rowCount();

  //A questo punto parte il primo controllo: se num > 0 significa che effettivamente c'è una corrispondenza nel DB 
      if($num > 0){

        //La riga esiste, la estraggo dal database
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        /*Grazie al fetch(PDO::FETCH_ASSOC), $row non è una semplice variabile bensì un array associativo:
         Trasforma la riga del database in un array dove le etichette (chiavi) corrispondono ai nomi delle colonne, ad esempio:

          $row['id_utente'] conterrà il valore della colonna id_utente
          ...
          $row['password'] conterrà il valore della colonna password, ad esempio "$2y$10$vnUVLU...." */

          
        
                if(password_verify($this->password, $row['password'])) {//password_verify confronta la password in chiaro inviata dall'utente con l'hash salvato nel DB
                          
                          // 4. Se la sfida ha successo, popoliamo l'oggetto con i dati reali del DB
                          // Questo "identifica" ufficialmente l'oggetto Utente attuale
                          $this->id_utente = $row['id_utente'];
                          $this->nome = $row['nome'];
                          $this->cognome = $row['cognome'];
                          $this->ruolo = $row['ruolo'];

                          return true;
                      } 

                  }
        // Se num non è > 0 l'email non esiste, restituisco false
        return false;
}

//Questa funzione consente di avere le informazioni su tutti gli utenti presenti nel DB
function read(){

    $query = "SELECT id_utente, nome, cognome, email, ruolo FROM " . $this->table_name . " ORDER BY id_utente ASC";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;

}

function readOne(){

$query = "SELECT id_utente, nome, cognome, email, ruolo FROM ".$this->table_name." WHERE id_utente = ? LIMIT 0,1";

$stmt = $this->conn->prepare($query);

//Sanitizzazione: necessaria per evitare SQL Injection o caratteri indesiderati
$this->id_utente = htmlspecialchars(strip_tags($this->id_utente)); 

//Binding del parametro posizionale. Il "1" si riferisce al primo (e unico) "?" nella query
 $stmt->bindParam(1, $this->id_utente);

$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Se row non è false significa che è stato trovato l'utente nel DB, passo con il popolare l'oggetto attuale
if($row) {
            $this->nome = $row['nome'];
            $this->cognome = $row['cognome'];
            $this->email = $row['email'];
            $this->ruolo = $row['ruolo'];
            
            return true; //Esito positivo
         }

        
        return false;// Se non trovo nulla restituisco false
    }


function update(){

      $query = "UPDATE " .$this->table_name . "
                SET 
                nome =:nome,
                cognome =:cognome,
                email =:email,
                ruolo =:ruolo
                WHERE id_utente =:id_utente";

      $stmt = $this->conn->prepare($query);

      //Sanitizzazione per prevenire SQL Injection
      $this->nome=htmlspecialchars(strip_tags($this->nome));
      $this->cognome = htmlspecialchars(strip_tags($this->cognome));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->ruolo = htmlspecialchars(strip_tags($this->ruolo));
      $this->id_utente = htmlspecialchars(strip_tags($this->id_utente));

      // Binding dei dati
      $stmt->bindParam(':nome', $this->nome);
      $stmt->bindParam(':cognome', $this->cognome);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':ruolo', $this->ruolo);
      $stmt->bindParam(':id_utente', $this->id_utente);

      // Esecuzione
      if($stmt->execute()) {
          return true;
      }
      return false;
}

//Per aggiornare la password verrà implementato in futuro un metodo ad-hoc

function delete(){
  $query = "DELETE FROM " . $this->table_name." WHERE id_utente =:id_utente";
  
  $stmt = $this->conn->prepare($query);

  
    //Sanitizzazione
    $this->id_utente = htmlspecialchars(strip_tags($this->id_utente));

    //Binding dell' ID
    $stmt->bindParam(":id_utente", $this->id_utente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
}
  
}
?>