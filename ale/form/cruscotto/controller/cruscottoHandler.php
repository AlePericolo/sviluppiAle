<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 27/08/18
 * Time: 17.44
 */

require_once '../../../conf/conf.php';
require_once '../../../src/lib/pdo.php';
require_once '../../../src/lib/functions.php';
require_once '../../../src/function/functionLogin.php';

require_once '../../../src/model/Post.php';
require_once '../../../src/model/Utente.php';
require_once '../../../src/model/Valutazione.php';

function getDatiPagina($request){

    $result = array();

    $pdo = connettiPdo();
    $post = new Post($pdo);

    //recupero i miei post e quelli dei miei amici
    $result['elencoPost'] = $post->findPostUtenteAmiciByIdUtente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), Post::FETCH_KEYARRAY);
    //per ogni post recupero la valutazione e il voto medio
    for($i=0; $i < sizeof($result['elencoPost']); $i++){
        $v = new Valutazione($pdo);
        //controllo se l'utente loggato ha già valutato questo post
        $valutazione = $v->findValutazioneByIdPostIdUtente($result['elencoPost'][$i]['id'], Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), Valutazione::FETCH_KEYARRAY);
        if($valutazione['valutazione'] != null) {
            $result['elencoPost'][$i]['valutazione'] = $valutazione;
        //altrimenti recupero la struttura della valutazione vuota e presetto l'id del post e dell'utente loggato
        }else{
            $valutazione = $v->getEmptyDbKeyArray();
            $valutazione['id_utente'] = Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id'));
            $valutazione['id_post'] = $result['elencoPost'][$i]['id'];
            $result['elencoPost'][$i]['valutazione'] = $valutazione;
        }
        $result['elencoPost'][$i]['votoMedio'] = Valutazione::getVotoMedioStatic($pdo, $result['elencoPost'][$i]['id']);
        $result['elencoPost'][$i]['numeroVoti'] = Valutazione::countNumeroVotiStatic($pdo, $result['elencoPost'][$i]['id']);
    }

    return json_encode($result);
}

function valutaPost($request){

    $result = array();

    $pdo = connettiPdo();
    try{
        $pdo->beginTransaction();
        $post = new Valutazione($pdo);
        $post->creaObjJson($request->valutazione, true);
        $post->saveOrUpdate();
        $pdo->commit();
        $result['response'] = 'OK';
    }catch (PDOException $e){
        $pdo->rollBack();
        $result['response'] = 'KO';
        $result['message'] = $e->getMessage();
    }

    return json_encode($result);
}


ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;
