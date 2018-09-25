<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 18/04/18
 * Time: 12.15
 */
/**
 * Funzione di connessione a PDO. La funzione consente di specificare il database a cui collegarsi.
 * Oltre questo, permette il passaggio dell'array indicizzato per i dati di connessione forzati composti da:
 * PWD - Password
 * USER - Username
 * SERVER - Indirizzo del server
 * ATTRIBUTE - attributi di connessione
 * CHARSET - Charset da utilizzare.
 * @param string|null $dbName
 * @param array|null
 * @return bool|PDO
 */
function connettiPdo($dbName = null, $server = null){

    //error_log("CONNETTI PDO");

    if (is_null($dbName)) $dbName = DBNAME;
    $attribute = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    if (is_null($server)) $server = SERVER;
    $user = DBUSER;
    $password = DBPASSWORD;
    $charset = DBCHARSET;

    $dsn = 'mysql:dbname='.$dbName.';host='.$server.';charset='.$charset;

    //try{
       return new PDO($dsn, $user, $password, $attribute);
    //}catch (PDOException $e){
    //    error_log('ERRORE CONNESSIONE A DB: ' .$e->getMessage());
        die;
    //}
    //return $pdo;
}

function findSchemas(){

    //error_log("FIND SCHEMAS");

    $attribute = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $server = SERVER;
    $user = DBUSER;
    $password = DBPASSWORD;
    $charset = DBCHARSET;

    $dsn = 'mysql:dbname=mysql;host='.$server.';charset='.$charset;

    //try{
        $pdo = new PDO($dsn, $user, $password, $attribute);

        $ambienti = array();

        $statement = $pdo->query("SHOW SCHEMAS");
        while($row = $statement->fetch(PDO::FETCH_COLUMN)){
            $ambienti[] = $row;
        }

        return $ambienti;

    //}catch (PDOException $e){
       // error_log('ERRORE CONNESSIONE A DB FIND SCHEMAS: ' .$e->getMessage());
        die;
    //}
}

function findServer(){
    return explode(',',SERVERS);
}

/**
 * @param $connessione PDO
 *
 */
function disconnettiPdo(&$connessione){
    $connessione=null;
}
/**
 * @param $pdo PDO
 * @param null $errore
 * @param int $codice
 */
function bloccaPdo($pdo,$errore=null,$codice=0){
    if ((isIstance($pdo)) and $pdo->inTransaction()) errorePdo($errore,$codice);
    return;
}

/**
 * Funzione per l'esecuzione delle query tramite prepare/execute
 * @param PDO|null $pdo classe di gestione database
 * @param string|PDOStatement $stringPrepared la stringa preparativa. Sostituire le variabili con ?
 * @param array $arrayElementi l'array mono/bidimensionale contenente i valori
 * (NOTA: per riga, DEVONO coincidere coi punti interrogativi!!!)
 * @param char|string $chrTipoRitorno variabile 'classica' (p=PDOStatement [default], b=blob, f=fetch/array, v=valore)
 * @param int $tipoFetch tipo di ritorno dato richiesto
 * @return PDOStatement|array|string ritorna il valore, uguale alla forma di queryPdo
 * ove $boolMatrice fosse true, ne sarebbe un'array di tali elementi.
 */
function queryPreparedPdo($pdo,$stringPrepared,$arrayElementi=null,$chrTipoRitorno='p',$tipoFetch=null){
    $boolConnettiLocale=(!($pdo instanceof PDO));
    if ($boolConnettiLocale) {
        $pdo=connettiPdo();
    }
    if (!$pdo instanceof PDO) {
        phpToAlert('Attezione! Connesione fallita!');
        die;
    }
    if (!$stringPrepared ){
        bloccaPdo($pdo);
    }
    /** @var $queryHandler PDOStatement */
    $queryHandler=($stringPrepared instanceof PDOStatement)?$stringPrepared:$pdo->prepare($stringPrepared);
    if (!is_null($arrayElementi) and !is_array($arrayElementi)){
        $arrayElementi = array($arrayElementi);
    }

    $queryHandler->execute($arrayElementi);
    if ('p' == strtolower($chrTipoRitorno)){
        return $queryHandler;
    }
    $fetching = (is_null($tipoFetch))?PDO::FETCH_ASSOC:$tipoFetch;
    switch (strtolower($chrTipoRitorno)){
        case 'b':
            $ritorno=$queryHandler->fetchAll($fetching);
            break;
        case 'f':
            $ritorno=$queryHandler->fetch($fetching);
            break;
        case 'v':
            $fetching = (is_null($tipoFetch))?0:$tipoFetch;
            $ritorno=$queryHandler->fetchColumn($fetching);
            break;
    }
    $queryHandler->closeCursor();
    if ($boolConnettiLocale) {
        $pdo=null;
    }
    return $ritorno;
}
/**
 * Semplice richiamo all'errore PDO
 * @param null $strMessaggio
 * @param int $intCodice
 * @throws PDOException
 */
function errorePdo($strMessaggio=null,$intCodice=0){
    throw new PDOException($strMessaggio,$intCodice);
}

/**
 * Funzione di interrogazione per le query. Le transazione NON sono gestite, MA
 * se passato l'oggetto PDO gestente la transazione, la transazione viene effettuata
 * @param $strQuery
 * @param null $pdo
 * @param string $tipoResult
 * @param int $tipoFetch
 * @return mixed|string|PDOStatement
 */
function queryPdo($strQuery,$pdo=null,$tipoResult='b',$tipoFetch=PDO::FETCH_BOTH){
    if (!$strQuery) return;
    $boolConnessione=!($pdo instanceof PDO);
    if ($boolConnessione) {
        $pdo=connettiPdo();
    }
    if (!$pdo instanceof PDO) {
        phpToAlert('Attezione! Connesione fallita!');
        die;
    }

    $queryHandler=$pdo->query($strQuery);
    switch(strtolower($tipoResult)):
        case 'p':
            $ritorno=$queryHandler;
            break;
        case 'b':
            $ritorno=$queryHandler->fetchAll($tipoFetch);
            break;
        case 'f':
            $ritorno=$queryHandler->fetch($tipoFetch);
            break;
        case 'v':
            $ritorno=$queryHandler->fetchColumn();
            break;
        case 'c':
            $ritorno=$queryHandler->rowCount();
            break;
    endswitch;
    if ('p'!=strtolower($tipoResult)) $queryHandler->closeCursor();
    if ($boolConnessione) $pdo=null;
    return $ritorno;
}

/**
 * Funzione di insert con gestione di transazione, insert di più righe in un solo ciclo,
 * transazione completa del multi insert, con possibilita' di ignorare l'avvenire della transazione
 * @param string $strTabella
 * @param array|string $arrayCampi
 * @param array|matrice $arrayValori
 * @param null|PDO $pdo - oggetto connessione database. Se nullo, connetto
 * @param bool $boolUltimoInserito - se true, torno l'ultimo ID inserito
 * @param bool $boolMatrice - se true, $arrayValori verra' considerato matrice: ogni elemento e' una nuova riga di valori
 * @param bool $boolAlteraTransazione - se PDO e' nullo, ove presente, attiva la transizione
 * @return bool
 * @internal param int $intConnTrans
 */
function insertPdo($strTabella,$arrayCampi,$arrayValori,$pdo=null,$boolUltimoInserito=false,$boolMatrice=false,$boolAlteraTransazione=true){
    //verifico che siano inseriti i campi e che abbiano un nome
    $boolConnessione=($pdo === null);
    if ($boolConnessione)$pdo=connettiPdo();
    if (!$arrayCampi) bloccaPdo($pdo,'Nessun campo passato');
    $arrayRichiesti=array();
    if (is_array($arrayCampi)): //se i campi sono un'array, verifico se l'ultimo elemento e' un array
        //in caso affermativo, lo considero come gruppo di elementi che NON possano essere zero
        $arrayRichiesti=end($arrayCampi);
        if (is_array($arrayRichiesti)){
            array_pop($arrayCampi);
            foreach ($arrayRichiesti as $id => $campo) $arrayRichiesti[$id]=array_search($campo,$arrayCampi);
        } else {
            $arrayRichiesti=array();
        }
    else:
        $arrayCampi=explode(',',$arrayCampi);
    endif;
    foreach ($arrayCampi as $nomeCampo) if (!$nomeCampo) bloccaPdo($pdo,'Non possono essere passati campi da nome nullo');
    if ($boolMatrice){ //se e' una matrice, verifico che TUTTE le righe da inserire si equivalgano in numero di elementi
        $intNumValori=count($arrayValori[0]);
        foreach($arrayValori as $rigaValori){
            if ($intNumValori != count($rigaValori)) bloccaPdo($pdo,'Le righe devono essere tutte lunghe uguali');
        }
    } else $intNumValori=count($arrayValori);
    //se il numero di campi e valori non corrisponde, torno indietro
    if ($intNumValori != count($arrayCampi)) {
        bloccaPdo($pdo, 'il numero di campi e di valori non corrispondono');
        var_dump($arrayCampi);
        var_dump($arrayValori);
    }
    //creo, in base ai campi, i 'jolly' da usare
    $strPosizioni=array();
    foreach($arrayCampi as $id) $strPosizioni[]='?';
    $strPosizioni=implode(',',$strPosizioni);
    //preparo la query
    $queryHandler=$pdo->prepare("INSERT INTO {$strTabella} (".implode(',',$arrayCampi).") VALUES ({$strPosizioni})");

    //piccolo accorgimento per non ricopiare la struttura in base alla matrice di insert
    $intNumRighe=($boolMatrice)?count($arrayValori):1;
    if ($boolConnessione and $boolAlteraTransazione){
        try{ //eseguo la transazione
            $pdo->beginTransaction();
            for ($i=0;$i<$intNumRighe;$i++):
                $arrayRigaCorrente=($boolMatrice)?$arrayValori[$i]:$arrayValori;
                foreach ($arrayRichiesti as $valoreRichiesto) if (!$arrayRigaCorrente[$valoreRichiesto]) bloccaPdo($pdo,"Il {$arrayCampi[$valoreRichiesto]} non &egrave; valido!");
                foreach ($arrayRigaCorrente as $posizione => $valore) if (null===$valore) $arrayRigaCorrente[$posizione]='';
                $queryHandler->execute($arrayRigaCorrente);
            endfor;
            $ultimoId= $pdo->lastInsertId();
            $pdo->commit();
        }catch (PDOException $e){
            $ultimoId=false;
            $pdo->rollBack();

        }
    }else{
        //se non intendo effettuare una transazione (o richiamo la procedura da un'altra in corso...)
        for ($i=0;$i<$intNumRighe;$i++):
            $arrayRigaCorrente=($boolMatrice)?$arrayValori[$i]:$arrayValori;
            foreach ($arrayRichiesti as $valoreRichiesto) if (!$arrayRigaCorrente[$valoreRichiesto])  bloccaPdo($pdo,"Il {$arrayCampi[$valoreRichiesto]} non &egrave; valido!");
            foreach ($arrayRigaCorrente as $posizione => $valore) if (null===$valore) $arrayRigaCorrente[$posizione]='';
            $queryHandler->execute($arrayRigaCorrente);
        endfor;
        $ultimoId= $pdo->lastInsertId();
    }
    if ($boolConnessione) $pdo=null;
    if ($boolUltimoInserito) return $ultimoId;
}

/**
 * Funzione per l'eliminazione di determinate righe da una tabella.
 * @param string $strTabella nome tabella
 * @param mixed|array $elementi valore o valori (come array o stringa separate da virgole) da eliminare
 * @param string $chiave nome colonna da verificare, se null cancella tutti gli elementi della tabella
 * @param null $pdo oggetto di connessione preesistente
 * @param bool $boolAlteraTransazione se attivo, altera la connessione
 */
function deletePdo($strTabella,$elementi,$chiave='ID',$pdo=null,$boolAlteraTransazione=true){
    $boolConnessione=($pdo === null);
    if ($boolConnessione)$pdo=connettiPdo();
    if ((!$chiave) && !is_null($chiave)) bloccaPdo($pdo,'La chiave di ricerca DEVE essere valida.');
    if (!is_array($elementi)) $elementi=explode(',',$elementi);
    $queryDelete =  "DELETE FROM {$strTabella} WHERE {$chiave}=?";
    if ($chiave == null)  $queryDelete = "DELETE FROM {$strTabella}";
    $queryHandler=$pdo->prepare($queryDelete);
    if ($boolConnessione and $boolAlteraTransazione):
        try{ //eseguo la transazione
            $pdo->beginTransaction();
            if (is_null($chiave)){
                $queryHandler->execute();
            }else{
                foreach($elementi as $id):
                    $arrayRigaCorrente=array($id);
                    $queryHandler->execute($arrayRigaCorrente);
                endforeach;
            }
            $pdo->commit();
        }catch (PDOException $e){
            //anche una sola query fallita, genera l'errore che torna al valore precedente
            $pdo->rollBack();
        }
    else:
        //se non intendo effettuare una transazione (o richiamo la procedura da un'altra in corso...)
        if (is_null($chiave)){
            $queryHandler->execute();
        }else{
            foreach($elementi as $id):
                $arrayRigaCorrente=array($id);
                $queryHandler->execute($arrayRigaCorrente);
            endforeach;
        }
    endif;
    if ($boolConnessione) $pdo=null;
}

/**
 * Funzione di UPDATE di una tabella.
 * ATTENZIONE: La funzione permette di impostare quali parametri sono da considerarsi OBBLIGATORI, ovvero non
 * accettati con valori nulli/falsi/zeri, Affinche' questo avvenga, ricordarsi due cose: i campi ed i valori
 * DEVONO essere passati come array (anche in caso siano uno solo), INOLTRE, l'ultimo elemento dei campi,
 * DEVE essere un'array dei campi non nulli.
 *
 * Affinche' la chiamata risulti corretta, passare il riferimento all'oggetto nelle variabili. Passare null per effettuare
 * una connessione interrogativa al volo.
 * @param string $strTabella tabella in cui inserire i valori
 * @param string|array $arrayCampi campi da aggiornare
 * @param string|array $arrayValori valori da scrivere
 * @param string|array $elementi ID (elementi) da modificare.
 * @param null|PDO $pdo
 * @param string $chiave
 * @param bool $boolAlteraTransazione
 */
function updatePdo($strTabella,$arrayCampi,$arrayValori,$elementi,$pdo=null,$chiave='ID',$boolAlteraTransazione=true){
    $boolConnessione=($pdo === null);
    if ($boolConnessione)  $pdo=connettiPdo();
    $arrayRichiesti=array();
    if (is_array($arrayCampi)): //se i campi sono un'array, verifico se l'ultimo elemento e' un array
        //in caso affermativo, lo considero come gruppo di elementi che NON possano essere zero
        $arrayRichiesti=end($arrayCampi);
        if (is_array($arrayRichiesti)){
            array_pop($arrayCampi);
            foreach ($arrayRichiesti as $id => $campo) $arrayRichiesti[$id]=array_search($campo,$arrayCampi);
            if (1==count($arrayCampi)) $arrayCampi=$arrayCampi[0];
        }
        else $arrayRichiesti=array();
    endif;
    if (is_array($arrayCampi) != is_array($arrayValori)) bloccaPdo($pdo,'Verificare che gli elementi campi e valori dello stesso tipo (array o singoli)');
    if (!is_array($arrayCampi)):

        if (!$arrayCampi) bloccaPdo($pdo,'Nessun campo fornito!');
        $arrayCampi=explode(',',$arrayCampi);
        $arrayValori=explode(',',$arrayValori);
    endif;
    if (count($arrayCampi)!= count($arrayValori)) bloccaPdo($pdo,'Il rapporto campo/valore non e\' soddisfatto.');
    //controllo ogni singolo campo dell'array. Non accetto valori bianchi
    foreach ($arrayCampi as $campo) if (!$campo)bloccaPdo($pdo,'Non possono esserci nomi vuoti tra i campi');
    //recupero dall'array campi, quali DEVONO avere valore diverso da zero!
    for ($i=0;$i<count($arrayCampi);$i++) $arrayCampi[$i].='= ?';
    $intNumElementi=count($arrayValori);
    $queryHandler=$pdo->prepare("UPDATE {$strTabella} SET ".implode(',',$arrayCampi)." WHERE {$chiave}=?");
    if (!is_array($elementi)) $elementi=explode(',',$elementi);
    if ($boolConnessione and $boolAlteraTransazione){
        try{ //eseguo la transazione
            $pdo->beginTransaction();
            for ($i=0;$i<count($elementi);$i++):
                foreach ($arrayRichiesti as $valoreRichiesto) if (!$arrayValori[$valoreRichiesto]) bloccaPdo($pdo,'il campo '.str_replace('?=',null,$arrayCampi[$valoreRichiesto]).' non &egrave; valido!');
                $arrayValori[$intNumElementi]=$elementi[$i];
                foreach ($arrayValori as $posizione => $valore) if (null===$valore) $arrayValori[$posizione]='';
                $queryHandler->execute($arrayValori);
            endfor;
            $pdo->commit();

        }catch (Exception $e){
            //anche una sola query fallita, genera l'errore che torna al valore precedente
            $pdo->rollBack();
        }
    }else{
        //se non intendo effettuare una transazione (o richiamo la procedura da un'altra in corso...)
        for ($i=0;$i<count($elementi);$i++):
            foreach ($arrayRichiesti as $valoreRichiesto) if (!$arrayValori[$valoreRichiesto]) bloccaPdo($pdo,'il campo '.str_replace('?=',null,$arrayCampi[$valoreRichiesto]).' non &egrave; valido!');
            $arrayValori[$intNumElementi]=$elementi[$i];
            foreach ($arrayValori as $posizione => $valore) if (null===$valore) $arrayValori[$posizione]='';
            $queryHandler->execute($arrayValori);
        endfor;
    }
    if ($boolConnessione) $pdo=null;
}

/**
 * Funzione per effettuare l'INSERT di un array $array[<campoDB>]=>valore.
 * Nota: ogni valore giunto null verra' eliminato.
 * @param PDO $pdo
 * @param string $strTabella
 * @param array $arrayKey
 * @param bool $boolMatrice
 *
 * @return null|string
 */
function insertKeyArrayPdo($pdo,$strTabella,$arrayKey,$boolMatrice=false){

    /* FixMe: rimosso debug di test di Claudio
     * $debugTrace = debug_backtrace();
    debug_print_backtrace();
    */

    if ( !boolArrayAssociativo($arrayKey) or !is_array($arrayKey) or !$arrayKey or $boolMatrice) return null;
    if (!$strTabella){
        bloccaPdo($pdo,'Errore grave. La tabella non e\' arrivata.');
        return 'Errore grave. La tabella non e\' arrivata.';
    }
    $boolConnetti = (!$pdo);
    if ($boolConnetti){
        $pdo=connettiPdo();
    }
    $arrayCampi=array();
    $intNumRigheMax=0;
    $arrayPosizioni=array();
    foreach($arrayKey as $key=>$value) {
        if (null===$value or ($boolMatrice and !$value)) {
            unset($arrayKey[$key]);
            continue;
        }
        $arrayCampi[]=$key;
        $arrayPosizioni[]=':'.$key;
    }
    if (!$arrayCampi) return null;
    if ($boolMatrice){
        foreach ($arrayKey as $value) if ($intNumRigheMax < count($value))$intNumRigheMax = count($value);

        foreach($arrayKey as $key => $value){
            if ($intNumRigheMax==count($value)) continue;
            for ($i=count($value);$i<$intNumRigheMax;$i++) $arrayKey[$key][$i]='';
        }
    }
    $insert=$pdo->prepare('INSERT INTO '.$strTabella.' ('.implode(',',$arrayCampi).') VALUES ('.implode(',',$arrayPosizioni).')');
    if ($boolMatrice):
        $arrayTemp=array();
        for ($i=0;$i<$intNumRigheMax;$i++):
            foreach($arrayKey as $key => $valore) $arrayTemp[$key]=(null===$valore[$i])?'':$valore[$i];
            $insert->execute($arrayTemp);
        endfor;
    else:
        $insert->execute($arrayKey);
    endif;
    $ritorno=$pdo->lastInsertId();
    if ($boolConnetti){
        $pdo = null;
    }
    return $ritorno;

}
/**
 * @param  PDO   $pdo
 * @param string  $strTabella
 * @param array  $arrayKey
 * @param mixed  $idValue
 * @param string $strIdColumn
 * @return null|string|int
 */
function updateKeyArrayPdo($pdo,$strTabella,$arrayKey,$idValue,$strIdColumn='ID'){
    $boolConnetti = (!$pdo);

    if ($boolConnetti){
        $pdo = connettiPdo();
    }
    if (!$strIdColumn or !$idValue  or !$strTabella){
        bloccaPdo($pdo,'Errore grave. L\'ID della riga, la colonna di riferimento o la tabella non sono arrivati.');
        return 'Errore grave. L\'ID della riga, la colonna di riferimento o la tabella non sono arrivati.';
    }
    $arrayCampi=array();
    foreach($arrayKey as $chiave => $valore) {
        if (null===$valore) {
            unset($arrayKey[$chiave]);
            continue;
        }
        $arrayCampi[]=$chiave.'=:'.$chiave;
    }
    if (!$arrayCampi) return;
    $update=$pdo->prepare('UPDATE '.$strTabella.' SET '.implode(',',$arrayCampi).' WHERE '.$strIdColumn.'=:ID__COLUMN');
    $arrayKey['ID__COLUMN']=$idValue;
    $update->execute($arrayKey);
    $nrecord = $update->rowCount();
    if ($boolConnetti){
        $pdo = null;
    }
    return $nrecord;

}


