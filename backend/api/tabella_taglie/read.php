<?php

// Permette l'accesso da qualsiasi origine
header("Access-Control-Allow-Origin: *");

//Non voglio una risposta con Content - type: text.html ma application/json
header("Content-Type: application/json; charset =UTF-8");


//Praticamente mi importo i pezzi di codice che ho scritto da un altra parte
include_once '../../config/database.php';
include_once '../../models/tabella_taglie.php';


// Mi creo l'oggetto per gestire il database e gli chiedi di aprirci una connessione attiva
$database = new Database();
$db = $database->getConnection(); //Connessione al server mysql e ritorno l'oggetto per interfacciarmi con il server mysql

// Creo l'oggetto "Canotta" e gli passo la connessione appena aperta
$taglia  = new Tabella_Taglie($db);

/*È un API di lettura, mi richiamo il metodo read() della classe canotta.php che restituiva proprio un oggetto $stmt*/
$stmt = $taglia->read(); 

//Conto quante righe (canotte) sono state trovate nel database
$num = $stmt->rowCount();

//Verifico quanti risultati restituisce la query e gestisco i vari casi
if($num > 0){

  //Creo un array normale e poi lo rendo associativo
  $taglia_arr = array();
  $taglia_arr["records"] = array();


  // Ciclo "while": finché ci sono righe nel database, continua a leggere
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

      extract($row);

      $taglia_item = array(
            "id_taglia"=> $id_taglia,
            "nome_taglia" => $nome_taglia
      );

      // Aggiungo ora la singola canotta alla lista generale
      array_push($taglia_arr["records"], $taglia_item);
  }

  http_response_code(200);

    //JSON_PRETTY_PRINT permette di visualizzare in maniera più pulita
  echo json_encode($taglia_arr, JSON_PRETTY_PRINT);

  // L'else viene eseguito se il database è vuoto o non ci sono risultati
}else{
    http_response_code(404);

    echo json_encode(
      array("message" => "Nessuna taglia trovata.")
    );
}




?>