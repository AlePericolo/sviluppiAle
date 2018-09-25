<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 18/04/18
 * Time: 11.58
 */


require_once '../../conf/conf.php';
require_once '../../src/lib/pdo.php';
require_once '../../src/lib/functions.php';

require_once '../../src/model/elements.php';

function getDatiPagina($request){

    $result = array();

    $pdo = connettiPdo();
    $elements = new Elements($pdo);
    $result['elements'] = $elements->findAll(false,Elements::FETCH_KEYARRAY);

    if($result['elements'] > 0){
        $result['response'] = 'OK';
    }else{
        $result['response'] = 'KO';
    }

    return json_encode($result);
}

function nuovoElemento($request){

    $result = array();

    $pdo = connettiPdo();
    $elements = new Elements($pdo);
    $result['newElement'] = $elements->getEmptyDbKeyArray();

    return json_encode($result);
}

function inserisciElemento($request){

    $result = array();

    $pdo = connettiPdo();
    try{
        $pdo->beginTransaction();
        $elements = new Elements($pdo);
        $elements->creaObjJson($request->elemento, true);
        $elements->saveOrUpdate();
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

