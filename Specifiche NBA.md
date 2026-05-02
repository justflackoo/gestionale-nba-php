Utenti: Amministratore, Negoziante e Cliente (in futuro aggiungere anche Fornitore)

Cosa differenzia l'amministratore dal Negoziante? L'amministratore può visualizzare lo storico delle vendite, se risulta in perdita o meno, può inserire nuovi prodotti (tramite una fattura)



Tutti devono prima registrarsi e poi fare login



L'idea di base è gestire un negozio di divise sportive, per ora solo NBA



Ogni canotta è caratterizzata da:

* ID Canotta
* Cognome
* Squadra (che poi sarà legata ad una Conference, così da poter filtrare per conference)
* Numero
* Tipo (Home, Away, Third, Retro)
* Anno
* Taglie disponibili (dalla S alla XXL) --> la si gestisce con una Tabella\_Taglie che unisce Canotta e Magazzino (l'insieme delle canotte)
* Giacenza (numero di items disponibili)
* Prezzo originale
* Percentuale sconto





La vendita, che coinvolge CLIENTE e FATTURA, deve essere divisa ulteriormente: Il cliente non possiede una CANOTTA, possiede invece una FATTURA che è caratterizzata da vari dettagli, e per questo una fattura è legata ad una DETTAGLIO\_FATTURA, che a sua volta è legata alla tabella MAGAZZINO/CANOTTA.

Questo approccio prende il nome di Header - Detail, schema che separa le informazioni generali (Header) dai dettagli specifici (Detail), ottimizzando la gestione.





Le entità che prevedo sono:

UTENTE, che grazie all'attributo RUOLO permette di distinguere tra Amministratore, Negoziante e Cliente (in futuro aggiungere anche Fornitore)



CANOTTA

TABELLA TAGLIE (per legare CANOTTA a MAGAZZINO)

MAGAZZINO (mette in contatto la Canotta con la Tabella\_Taglie)

FATTURA

DETTAGLIO FATTURA









### SPIEGAZIONE FK 

**MAGAZZINO - CANOTTA: FK id\_canotta REFERENCES ID\_canotta FROM CANOTTA**

**ON UPDATE: CASCADE**, Se l'ID di una maglia cambia nella tabella Canotta, il database aggiorna istantaneamente tutte le relative giacenze (taglia S, M, L, ecc.) nel Magazzino.

**ON DELETE: CASCADE**, Se cancelli una Canotta (es. un modello non più prodotto), il database rimuove automaticamente tutte le righe di quel modello nel Magazzino.



**MAGAZZINO - TABELLA\_TAGLIE: FK id\_taglia REFERENCES id\_taglia FROM TABELLA\_TAGLIE**

**ON UPDATE: CASCAD**E, Se l'ID di una taglia (ad esempio "XL") viene modificato nella tabella principale, il database aggiorna automaticamente quel valore in tutte le righe del Magazzino che la utilizzano.

**ON DELETE: RESTRICT**, Il database impedisce fisicamente la cancellazione di una taglia (es. "M") dalla Tabella\_Taglie finché esiste anche una sola maglia di quella taglia nel Magazzino.



**FATTURA - UTENTE: FK id\_cliente  REFERENCES id\_utente FROM Utente**

**ON DELETE: RESTRICT**, il database ti impedirà di cancellare l'utente finché esistono fatture a lui collegate

**ON UPDATE: CASCADE**, Se mai dovessi aggiornare l'ID di un utente per motivi tecnici, il database aggiornerà automaticamente il riferimento in tutte le sue fatture, mantenendo il legame intatto





**FATTURA - DETTAGLIO\_FATTURA: FK id\_fattura REFERENCES id\_fattura FROM DETTAGLIO\_FATTURA**

**ON DELETE: CASCADE**, Se eliminiAMO una Fattura, il database distrugge istantaneamente tutti i suoi Dettaglio\_Fattura.

**ON UPDATE: CASCADE**, Se cambiamo l'ID di una fattura o di un prodotto, il database aggiorna automaticamente tutti i record collegati nelle tabelle "figlie".



**DETTAGLIO\_FATTURA - MAGAZZINO: FK id\_giacenza REFERENCES id\_giacenza FROM MAGAZZINO**

**ON DELETE: CASCADE**, Se l'ID dello "scaffale" cambia nel Magazzino, il database aggiorna automaticamente il riferimento in tutti i Dettaglio\_Fattura passati.

**ON UPDATE: RESTRICT**, Impedisce la distruzione involontaria dello storico delle vendite. Qui il CASCADE è assolutamente vietato. Il database ti impedisce di eliminare un articolo dal Magazzino se questo è già stato venduto almeno una volta (ovvero se è presente in una fattura).

