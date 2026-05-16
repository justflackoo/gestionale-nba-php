<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: PUT"); //Aggiorno il metodo consentito: devo modificare, quindi PUT
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/tabella_taglie.php';

$database = new Database();
$db = $database->getConnection();

$taglia = new Tabella_Taglie($db);

// file_get_contents legge i dati grezzi della richiesta, json_decode li trasforma in un oggetto PHP
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_taglia) && !empty($data->nome_taglia)){

        $taglia->id_taglia = $data->id_taglia; 
        
        //Valori aggiornati
        $taglia->nome_taglia = $data->nome_taglia;
                if($taglia->update()){   //Verifico lo stato della richiesta  
                        
                        http_response_code(200); //Modifica effettuate
                        echo json_encode(array("message" => "Valore taglia aggiornata con successo."));
                } else { 
                        //Entro in questo blocco se non è stato possibile aggiornare la richiesta
                        http_response_code(503);  
                        echo json_encode(array("message" => "Non è stato possibile aggiornare la taglia."));
                }

    } else {
        // Se l'utente si è dimenticato l'ID o qualche altro campo [21-29], restituiamo 400 (Bad Request)
        http_response_code(400);
        echo json_encode(array("message" => "Dati incompleti. Ricordati di inviare l'id_taglia e tutti i campi da modificare."));
}

?>