<?php
// Permettiamo l'accesso da qualsiasi origine (fondamentale per lo sviluppo)
header("Access-Control-Allow-Origin: *");

//Le risposte dell'API saranno in formato JSON
header("Content-Type: application/json; charset=UTF-8");

//Questo endpoint accetta solo richieste di tipo POST: stiamo inviando dati sensibili, POST è l'unico sicuro e corretto
header("Access-Control-Allow-Methods: POST");

//Durata (in secondi) della cache per queste autorizzazioni
header("Access-Control-Max-Age: 3600");

//Lista delle tipologie di header che il client può inviare
// Authorization è fondamentale se in futuro verranno implementati i Token (JWT)
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/utente.php';

$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);

// Leggiamo i dati inviati nel body della richiesta (JSON) con json_decode
$data = json_decode(file_get_contents("php://input"));

//Step 1, verifichiamo che effettivamente email e password non siano campi vuoti
if(!empty($data->email) && !empty($data->password)){

//Step 2: assegno i valori ricevuti alle proprietà dell'oggetto utente
           $utente->email = $data->email;
          $utente->password = $data->password ;

//Step 3: gestisco il caso di login andato a buon fine o meno
        if($utente->login()){

          //Entro in questo blocco se le credenziali inserite sono corrette

            // Creo un DTO per isolare i dati necessari al frontend (id, nome, ruolo)
            // ed escludere informazioni sensibili come l'hash della password.
            $user_auth = array(
                "id_utente" => $utente->id_utente,
                "nome" => $utente->nome,
                "cognome" => $utente->cognome,
                "email" => $utente->email,
                "ruolo" => $utente->ruolo
            );

            /*Grazie al DTO precedentemente sviluppato,
              ho una netta separazione tra il modello dati interno e la risposta API. */

            http_response_code(200); // OK
            echo json_encode(array(
            "message" => "Login effettuato con successo.",
            "user" => $user_auth
        ));

        }else{

          //Entro in questo blocco se le credenziali inserite sono errate
          http_response_code(401);
          echo json_encode(array("message" => "Accesso negato. Email o password errate."));
        }

}else{
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Dati incompleti. Inserire email e password."));
}


?>