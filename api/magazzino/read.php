<?php
// Permette l'accesso da qualsiasi origine
header("Access-Control-Allow-Origin: *");

//Non voglio una risposta con Content - type: text.html ma application/json
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/magazzino.php';

$database = new Database();
$db = $database->getConnection();

$magazzino = new Magazzino($db);

/*È un API di lettura, mi richiamo il metodo read() della classe magazzino.php che restituiva proprio un oggetto $stmt*/
$stmt = $magazzino->read(); 

//Conto quante righe (giacenze in magazzino) sono state trovate nel database
$num = $stmt->rowCount();


if($num > 0){ //Se c'è almeno una riga nel magazzino eseguo questo blocco

       $magazzino_arr = array();
       $magazzino_arr["records"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

              extract($row);

              $magazzino_item = array(    
                "id_canotta" => $id_canotta,
                "giocatore" => $giocatore,
                "nome_taglia" => $nome_taglia,
                "quantita_disponibile" => $quantita_disponibile,
                "id_giacenza" => $id_giacenza);

        // Aggiungo ora la singola giacenza alla lista delle giacenze totali
        array_push($magazzino_arr["records"], $magazzino_item);

        }

        http_response_code(200);

        //JSON_PRETTY_PRINT permette di visualizzare in maniera più pulita
        echo json_encode($magazzino_arr, JSON_PRETTY_PRINT);


}else{

    http_response_code(404);
    echo json_encode(array("message" => "Nessuna giacenza in magazzino trovata."));

}

?>