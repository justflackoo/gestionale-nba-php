<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: PUT"); //Aggiorno il metodo consentito: devo modificare, quindi PUT
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/magazzino.php';

$database = new Database();
$db = $database->getConnection();

$magazzino = new Magazzino ($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_giacenza) && !empty($data->id_canotta) && !empty($data->id_taglia) && isset($data->quantita_disponibile)){


      $magazzino->id_giacenza = $data->id_giacenza; 
      $magazzino->id_canotta = $data->id_canotta; 
      $magazzino->id_taglia = $data->id_taglia;
      $magazzino->quantita_disponibile = $data->quantita_disponibile;

      if($magazzino->update()){
                  http_response_code(200); //Modifica effettuate
                  echo json_encode(array("message" => "Giacenza in magazzino aggiornata con successo."));
      }else{
                  http_response_code(503); //Non è stato possibile aggiornare la giacenza
                  echo json_encode(array("message" => "Non è possibile aggiornare la giacenza in magazzino."));
      }
  
}else{
        http_response_code(400);
        echo json_encode(array("message" => "Dati incompleti, non è stato possibile modificare lo stato della giacenza."));
}



?>