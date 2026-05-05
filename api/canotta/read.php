<?php

// Permette l'accesso da qualsiasi origine
header("Access-Control-Allow-Origin: *");

//Non voglio una risposta con Content - type: text.html ma application/json
header("Content-Type: application/json; charset=UTF-8");


//Praticamente mi importo i pezzi di codice che ho scritto da un altra parte
include_once '../../config/database.php';
include_once '../../models/canotta.php';


// Mi creo l'oggetto per gestire il database e gli chiedi di aprirci una connessione attiva
$database = new Database();
$db = $database->getConnection(); //Connessione al server mysql e ritorno l'oggetto per interfacciarmi con il server mysql

// Creo l'oggetto "Canotta" e gli passo la connessione appena aperta
$canotta  = new Canotta($db);

/*È un API di lettura, mi richiamo il metodo read() della classe canotta.php che restituiva proprio un oggetto $stmt*/
$stmt = $canotta->read(); 

//Conto quante righe (canotte) sono state trovate nel database
$num = $stmt->rowCount();

//Verifico quanti risultati restituisce la query e gestisco i vari casi
if($num > 0){

  //Creo un array normale e poi lo rendo associativo
  $canotta_arr = array();
  $canotta_arr["records"] = array();


  // Ciclo "while": finché ci sono righe nel database, continua a leggere
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

      extract($row);

      $canotta_item = array(
            "id_canotta"=> $id, // In models/canotta.php ho scritto Id_canotta AS id, quindi devo usare $id
            "giocatore" => $giocatore,
            "squadra" => $squadra,
            "numero"=> $numero,
            "tipo" => $tipo,
            "anno" => $anno,
            "prezzo_originale" => $prezzo_originale,
            "percentuale_sconto" => $percentuale_sconto
      );

      // Aggiungo ora la singola canotta alla lista generale
      array_push($canotta_arr["records"], $canotta_item);
  }

    http_response_code(200);

    //JSON_PRETTY_PRINT permette di visualizzare in maniera più pulita
    echo json_encode($canotta_arr, JSON_PRETTY_PRINT);

  // L'else viene eseguito se il database è vuoto o non ci sono risultati
}else{
    http_response_code(404);

    echo json_encode(array("message" => "Nessuna canotta trovata."));
}




?>