<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset = UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");

//Specifico gli header di richiesta consentiti
header("Access-Control-Allow-Headers: Content-Type");


include_once '../../config/database.php';
include_once '../../models/canotta.php';

$database = new Database();
$db = $database->getConnection();

$canotta  = new Canotta($db);


//Mi prendo i dati che mi arrivano dalla POST.
/*Serve per passare (leggere) lato server dei dati che ci arrivano in forma .json dal client */

/*file_get_contents prende un flusso di input e lo decodifica in json */
$data = json_decode(file_get_contents("php://input"));

//Se i valori esistono allora li vado a settare
if(
 
        !empty($data->giocatore) &&
        !empty($data->squadra) &&
        !empty($data->numero) &&
        !empty($data->tipo) &&
        !empty($data->anno) &&
        !empty($data->prezzo_originale) &&
        !empty($data->percentuale_sconto)
){
        
        $canotta->giocatore = $data->giocatore;
        $canotta->squadra = $data->squadra;
        $canotta->numero = $data->numero;
        $canotta->tipo = $data->tipo;
        $canotta->anno = $data->anno;
        $canotta->prezzo_originale = $data->prezzo_originale;
        $canotta->percentuale_sconto = $data->percentuale_sconto;

        if($canotta->create()){    
                http_response_code(201); //Setto il codice di risposta 201 --> CREATO
                echo json_encode(array("message" => "Canotta creata con successo."));//E lo comunico all'utente
        }else{ 
                http_response_code(503);//Entro in questo blocco se non sono riuscito a creare la canotta  
                echo json_encode(array("message" => "Non è stato possibile creare la canotta."));//E lo comunico all'utente :(
        }
}

?>