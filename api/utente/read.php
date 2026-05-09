<?php
header("Access-Control-Allow-Origin: *"); //Consento l'accesso da tutti i browser
header("Content-Type: application/json; charset=UTF-8");  //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: GET"); 
header("Access-Control-Max-Age: 3600"); //Per quanti secondi il browser può memorizzare questa autorizzazione


include_once '../../config/database.php';
include_once '../../models/utente.php';


$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);

/*È un API di lettura, mi richiamo il metodo read() della classe utente.php che restituiva proprio un oggetto $stmt*/
$stmt = $utente->read(); 

//Conto quanti utenti sono stati trovati nel database
$num = $stmt->rowCount();

if($num > 0){ //Se ci sono elementi entro in questo blocco

              // Array che conterrà tutti gli utenti
              $utenti_arr = array();
              $utenti_arr["records"] = array();

              // Recuperiamo i contenuti della tabella
              // FETCH_ASSOC ci permette di usare i nomi delle colonne come chiavi
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                // Creiamo un DTO per ogni singola riga: mi conviene così gli elementi dell'array non avranno la password
                  $utente_item = array(
                      "id_utente" => $row['id_utente'],
                      "nome" => $row['nome'],
                      "cognome" => $row['cognome'],
                      "email" => $row['email'],
                      "ruolo" => $row['ruolo']
                  );

                  // Aggiungiamo l'utente all'array principale
                  array_push($utenti_arr["records"], $utente_item);


     http_response_code(200);
     echo json_encode($utenti_arr);

    }

}else{
    http_response_code(404); //404 --> Not Found, il server non è in grado di trovare la risorsa richiesta
    echo json_encode(array("message" => "Nessun utente trovato."));
}
?>