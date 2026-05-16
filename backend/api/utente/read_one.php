<?php
header("Access-Control-Allow-Origin: *"); //Consento l'accesso da tutti i browser
header("Content-Type: application/json; charset=UTF-8");  //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: GET"); 
header("Access-Control-Max-Age: 3600"); //Per quanti secondi il browser può memorizzare questa autorizzazione

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/utente.php';

$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);

if(isset($_GET['id_utente'])){ // Controlliamo se nell'URL della richiesta esiste il parametro "id": se avessi usato empty() mi restituiva false anche con l'id == 0
    $utente->id_utente = $_GET['id_utente']; // Se c'è, viene preso il valore dall'URL+e lo assegno alla proprietà dell'oggetto
}else{
  die(); // È un "blocco di sicurezza" per evitare query senza senso al database, ad esempio fornire id = 929942
}

if($utente->readOne()){
    // Se grazie all'id del controllo precedente viene trovato l'utente, creiamo il pacchetto JSON (DTO)
        $user_arr = array(
            "id_utente" => $utente->id_utente,
            "nome" => $utente->nome,
            "cognome" => $utente->cognome,
            "email" => $utente->email,
            "ruolo" => $utente->ruolo
        );

          http_response_code(200);
          echo json_encode($user_arr);

}else{
          http_response_code(404);
          echo json_encode(array("message" => "Utente non trovato."));
}


?>