<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); //L'API accetta e restituisce file .json
header("Access-Control-Allow-Methods: DELETE"); //Aggiorno il metodo consentito: devo eliminare, quindi DELETE
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../../config/database.php';
include_once '../../models/canotta.php';

$database = new Database();
$db = $database->getConnection();

$canotta = new Canotta($db);

$data = json_decode(file_get_contents("php://input"));


//Se l'ID NON è vuoto, allora entro in questo blocco
if( !empty($data->id_canotta) ) {
        
        //Se l'ID c'è, lo assegno all'oggetto canotta che ho creato alla riga 15
        $canotta->id_canotta = $data->id_canotta; 
        
        //Adesso provo ad eliminare la canotta a cui corrisponde l'id che ho passato
        if($canotta->delete()) {   
                //Eliminazione è andata a buon fine
                http_response_code(200); // 200 OK
                echo json_encode(array("message" => "Canotta eliminata con successo."));
        } else { 
                // Entro qui se c'è stato un problema lato server/database (es. query errata)
                http_response_code(503);  // 503 Service Unavailable
                echo json_encode(array("message" => "Non è stato possibile eliminare la canotta."));
        }

} else {
        //Entro in questo else se il client si è dimenticato di inviare l'id o lo ha lasciato vuoto
        http_response_code(400); // 400 Bad Request
        echo json_encode(array("message" => "Dati incompleti. Ricordati di inviare l'id_canotta per confermare l'eliminazione."));
}
?>