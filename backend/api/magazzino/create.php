<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");


//Specifico gli header di richiesta consentiti
header("Access-Control-Allow-Headers: Content-Type");


include_once '../../config/database.php';
include_once '../../models/magazzino.php';

$database = new Database();
$db = $database->getConnection();

$magazzino = new Magazzino($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_canotta) && !empty($data->id_taglia) && isset($data->quantita_disponibile)){

      $magazzino->id_canotta = $data->id_canotta;
      $magazzino->id_taglia = $data->id_taglia;
      $magazzino->quantita_disponibile = $data->quantita_disponibile;

      if($magazzino->create()){
                http_response_code(201); //Setto il codice di risposta 201 --> CREATO
                echo json_encode(array("message" => "Giacenza inserita con successo."));//E lo comunico all'utente
      }else{
                http_response_code(503); //Setto il codice di risposta 503 --> NON CREATO
                echo json_encode(array("message" => "Non è stato possibile inserire la giacenza in magazzino."));
      }


}else{ //Entro in questo blocco se non è stato possibile inserire la giacenza per una canotta all'interno del magazzino
                http_response_code(400); //Setto il codice di risposta 201 --> CREATO
                echo json_encode(array("message" => "Dati incompleti, non è possibile inserire la giacenza."));

}


?>