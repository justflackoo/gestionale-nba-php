<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: PUT"); //Aggiorno il metodo consentito: devo modificare, quindi PUT
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/utente.php';

$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);


// file_get_contents legge i dati grezzi della richiesta, json_decode li trasforma in un oggetto PHP
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_utente) &&
    !empty($data->nome) &&
    !empty($data->cognome) &&
    !empty($data->email) &&
    !empty($data->ruolo)){

    $utente->id_utente = $data->id_utente;
    $utente->nome = $data->nome;
    $utente->cognome = $data->cognome;
    $utente->email = $data->email;
    $utente->ruolo = $data->ruolo;

    if($utente->update()){
        http_response_code(200);
        echo json_encode(array("message" => "Utente aggiornato con successo."));
    }else{
        http_response_code(503);
        echo json_encode(array("message" => "Impossibile aggiornare i dati dell'utente."));
    }

}else{
   // Se l'utente si è dimenticato l'ID o qualche altro campo [21-29], restituiamo 400 (Bad Request)
        http_response_code(400);
        echo json_encode(array("message" => "Dati incompleti. Ricordati di inviare l'id_utente e tutti i campi da modificare."));
}



?>