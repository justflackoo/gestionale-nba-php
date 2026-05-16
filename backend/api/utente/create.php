<?php
header("Access-Control-Allow-Origin: *"); //Consento l'accesso da tutti i browser
header("Content-Type: application/json; charset=UTF-8");  //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: POST"); 
header("Access-Control-Max-Age: 3600"); //Per quanti secondi il browser può memorizzare questa autorizzazione

/*Dichiaro esplicitamente quali 'chiavi di lettura' il client può utilizzare per comunicare con l'API. 
Autorizzando Content-Type permetto l'invio di dati JSON, 
mentre con Authorization preparo l'infrastruttura per la gestione sicura degli accessi 
e dei ruoli utente. */

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/utente.php';

$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);

// Leggiamo i dati inviati nel body della richiesta (JSON) con json_decode
$data = json_decode(file_get_contents("php://input")); //la funzione file_get_contents(...) legge i dati in entrata (sono grezzi)

if(
          !empty($data->nome) && 
          !empty($data->cognome) && 
          !empty($data->email) && 
          !empty($data->password) && 
          !empty($data->ruolo)){

                  $utente->nome = $data->nome;
                  $utente->cognome = $data->cognome;
                  $utente->email = $data->email;
                  $utente->password = $data->password;
                  $utente->ruolo = $data->ruolo;
          
          if($utente->create()){ //Entro in questo blocco se create() ha successo
                http_response_code(201);
                echo json_encode(array("message" => "Utente registrato correttamente."));

          }else{//Entro in questo blocco se create() non ha successo
                http_response_code(503);
                echo json_encode(array("message" => "Non è stato possibile registrare l'utente."));}

}else{
  
        http_response_code(400); 
        echo json_encode(array("message" => "Dati incompleti (?)."));
}

?>