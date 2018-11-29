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
        $result['ricercaAmici'] = $utente->cercaNuoviAmiciFiltro(
                                                getLoginDataFromSession('id'),
                                                Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),
                                                $request->filtro->nome,
                                                $request->filtro->cognome,
                                                $request->filtro->etaDa,
                                                $request->filtro->etaA,
                                                Utente::FETCH_KEYARRAY);
    else
        $result['ricercaAmici'] = $utente->cercaNuoviAmici(
                                                getLoginDataFromSession('id'),
                                                Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),
                                                Utente::FETCH_KEYARRAY);

    $result['ricercaRichiesteAmiciziaInAttesa'] = $utente->findRichiesteAmiciziaInAttesa(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')),Utente::FETCH_KEYARRAY);

    return json_encode($result);
}

function aggiungiAmico($request){

    $result = array();

    $pdo = connettiPdo();

    try{
        $pdo->beginTransaction();
        $relazione = new Relazione($pdo);
        $relazione->setId_Richiedente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
        $relazione->setId_Richiesto($request->idAmico);
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

function accettaAmicizia($request){

    $result = array();

    $pdo = connettiPdo();

    $appResponse1 = $appResponse2 = false;
    $appMessage1 = $appMessage2 = "";

    try{
        $pdo->beginTransaction();
        $relazione = new Relazione($pdo);
        $relazione->findRelazioneByIdRichiedenteAndRichiesto($request->idRichiedente, Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
        $relazione->setAmicizia(1);
        $relazione->saveOrUpdate();
        $pdo->commit();
        $appResponse1 = true;
    }catch (PDOException $e){
        $pdo->rollBack();
        $appMessage1 = $e->getMessage();
    }
    try{
        $pdo->beginTransaction();
        $relazione = new Relazione($pdo);
        $relazione->setId_Richiedente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
        $relazione->setId_Richiesto($request->idRichiedente);
        $relazione->setAmicizia(1);
        $relazione->saveOrUpdate();
        $pdo->commit();
        $appResponse2 = true;
    }catch (PDOException $e){
        $pdo->rollBack();
        $appMessage2 = $e->getMessage();
    }

    if($appResponse1 and $appResponse2)
        $result['response'] = 'OK';
    else
        $result['response'] = 'KO';
    $result['message'] = $appMessage1 . ' ' . $appMessage2;

    return json_encode($result);
}

function rimuoviRelazione($request){

    $result = array();

    $pdo = connettiPdo();

    //rimuovi richiesta
    if($request->tipo == 0){
        try{
            $pdo->beginTransaction();
            $relazione = new Relazione($pdo);
            $relazione->deleteRelazioneByIdRichiedenteAndRichiesto(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), $request->idAmico);
            $relazione->saveOrUpdate();
            $pdo->commit();
            $result['response'] = 'OK';
        }catch (PDOException $e){
            $pdo->rollBack();
            $result['response'] = 'KO';
            $result['message'] = $e->getMessage();
        }
    }
    //rimuovi amicizia
    else{
        $appResponse1 = $appResponse2 = false;
        $appMessage1 = $appMessage2 = "";
        try{
            $pdo->beginTransaction();
            $relazione = new Relazione($pdo);
            $relazione->deleteRelazioneByIdRichiedenteAndRichiesto(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), $request->idAmico);
            $relazione->saveOrUpdate();
            $pdo->commit();
            $appResponse1 = true;
        }catch (PDOException $e){
            $pdo->rollBack();
            $appMessage1 = $e->getMessage();
        }
        try{
            $pdo->beginTransaction();
            $relazione = new Relazione($pdo);
            $relazione->deleteRelazioneByIdRichiedenteAndRichiesto($request->idAmico, Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
            $relazione->saveOrUpdate();
            $pdo->commit();
            $appResponse2 = true;
        }catch (PDOException $e){
            $pdo->rollBack();
            $appMessage2 = $e->getMessage();
        }

        if($appResponse1 and $appResponse2)
            $result['response'] = 'OK';
        else
            $result['response'] = 'KO';
            $result['message'] = $appMessage1 . ' ' . $appMessage2;
    }

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