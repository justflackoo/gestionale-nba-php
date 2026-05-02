<?php

class Database{

private $db_host = '127.0.0.1'; 
private $db_name = 'database';
private $db_username = 'root';
private $db_password ='';

/*Questa funzione restituisce l'oggetto PDO che costituisce la connessione al DB.
  La rendo pubblica così da poterla chiamare in ogni punto della mia applicazione.
*/

public function getConnection(){
    try{
         $conn = new PDO('mysql:host='  .$this->db_host.  ';dbname='  .$this->db_name, $this->db_username, $this->db_password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         return $conn;
    }
    catch(PDOException $e){
          echo "Connection error ".$e->getMessage();
          exit;
    }
  }
}

?>