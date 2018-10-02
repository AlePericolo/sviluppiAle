<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 26/09/18
 * Time: 15.01
 */

require_once '../../../conf/conf.php';
require_once '../../../src/lib/pdo.php';
require_once '../../../src/lib/functions.php';
require_once '../../../src/function/functionLogin.php';

require_once '../../../src/model/Utente.php';
require_once '../../../src/model/Post.php';

function getDatiPagina($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);
    $post = new Post($pdo);

    $id = $utente->findIdUtenteByIdLogin(getLoginDataFromSession('id'));

    //se esiste l'utente lo recupero con i suoi post e la struttura di un post vuoto
    if($id){
        $result['utente'] = $utente->findByPk($id, Utente::FETCH_KEYARRAY);
        $result['post'] = $post->getEmptyDbKeyArray();
        $result['elencoPost'] = $post->findPostByIdUtente($id, Post::FETCH_KEYARRAY);
    //altrimenti recupero la struttura vuota di un utente(per crearlo)
    }else{
        $result['utente'] = $utente->getEmptyDbKeyArray();
        $result['post'] = null;
        $result['elencoPost'] = [];
    }

    return json_encode($result);
}

/* ============================================= GESTIONE POST ====================================================== */

function salvaPost($request){

    $result = array();

    $pdo = connettiPdo();

    try{
        $pdo->beginTransaction();
        $post = new Post($pdo);
        $post->setIdUtente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')));
        $post->setTesto($request->post->testo);
        $post->setDataPubblicazione(date("Y-m-d"));
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

/* ======================================== GESTIONE DATI UTENTE ==================================================== */

function getDatiUtente($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);

    $id = $utente->findIdUtenteByIdLogin(getLoginDataFromSession('id'));

    if($id)
        $result['utente'] = $utente->findByPk($id, Utente::FETCH_KEYARRAY);
    else
        $result['utente'] = $utente->getEmptyDbKeyArray();

    return json_encode($result);
}

function salvaDatiUtente($request){

    $result = array();

    $pdo = connettiPdo();

    try{
        $pdo->beginTransaction();
        $utente = new Utente($pdo);
        //se mi arriva un id cerco il record e faccio l'update degli altri campi
        if ($request->utente->id)
            $utente->findByPk($request->utente->id);
        $utente->setIdLogin(getLoginDataFromSession('id'));
        $utente->setNome($request->utente->nome);
        $utente->setCognome($request->utente->cognome);
        $utente->setSesso($request->utente->sesso);
        $utente->setDataNascita(date("Ymd", strtotime($request->utente->data_nascita)));
        $utente->setFoto($request->utente->foto);
        $utente->saveOrUpdate();
        $pdo->commit();
        $result['response'] = 'OK';
    }catch (PDOException $e){
        $pdo->rollBack();
        $result['response'] = 'KO';
        $result['message'] = $e->getMessage();
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