<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: DELETE"); //Aggiorno il metodo consentito: devo eliminare, quindi DELETE
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



include_once '../../config/database.php';
include_once '../../models/utente.php';

$database = new Database();
$db = $database->getConnection();

$utente = new Utente($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_utente)){ //Entro in questo blocco solo se l'id_utente (che ricevo in input) non è nullo

      $utente->id_utente = $data->id_utente;
      if($utente->delete()){
        http_response_code(200); // 200 OK
        echo json_encode(array("message" => "Utente eliminato con successo."));

      }else{
        http_response_code(503);// 503 Service Unavailable
        echo json_encode(array("message" => "Non è stato possibile eliminare l'utente."));
      }

}else{
   //Entro in questo else se il client si è dimenticato di inviare l'id o lo ha lasciato vuoto
   http_response_code(400); // 400 Bad Request
   echo json_encode(array("message" => "Dati incompleti. Ricordati di inviare l'id_utente per confermare l'eliminazione."));
}

?>