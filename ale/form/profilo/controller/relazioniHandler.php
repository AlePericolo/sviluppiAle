<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 28/09/18
 * Time: 11.26
 */

require_once '../../../conf/conf.php';
require_once '../../../src/lib/pdo.php';
require_once '../../../src/lib/functions.php';
require_once '../../../src/function/functionLogin.php';

require_once '../../../src/model/Relazione.php';
require_once '../../../src/model/Utente.php';

function getDatiPagina($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);
    $result['elencoAmici'] = $utente->findElencoAmici(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),Utente::FETCH_KEYARRAY);
    $result['richiesteAmiciziaInAttesa'] = $utente->findRichiesteAmiciziaInAttesa(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),Utente::FETCH_KEYARRAY);

    $result['pathIcone'] = PATH_ICONE;

    return json_encode($result);
}

function cercaAmici($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);
    if($request->filtro != null)
        $result['ricercaAmici'] = [];//ToDo: ricerca amici con filtri
    else
        $result['ricercaAmici'] = $utente->cercaNuoviAmici(getLoginDataFromSession('id'), Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),Utente::FETCH_KEYARRAY);

    return json_encode($result);
}

function aggiungiAmico($request){

    $result = array();

    $pdo = connettiPdo();

    try{
        $pdo->beginTransaction();
        $relazione = new Relazione($pdo);
        $relazione->setIdRichiedente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
        $relazione->setIdRichiesto($request->idAmico);
        $relazione->setAmicizia(0);
        $relazione->saveOrUpdate();
        $pdo->commit();
        $result['response'] = 'OK';
    }catch (PDOException $e){
        $pdo->rollBack();
        $result['response'] = 'KO';
        $result['message'] = $e->getMessage();
    }

    return json_encode($result);
}

function richiesteInAttesa($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);
    $result['richiesteInAttesa'] = $utente->findRichiesteInAttesaByIdRichiedente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), Utente::FETCH_KEYARRAY);

    return json_encode($result);
}

//create/read session
ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;