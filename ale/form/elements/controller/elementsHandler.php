<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 18/04/18
 * Time: 11.58
 */


require_once '../../../conf/conf.php';
require_once '../../../src/lib/pdo.php';
require_once '../../../src/lib/functions.php';

require_once '../../../src/model/elements.php';

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

function recuperaElemento($request){

    $result = array();

    $pdo = connettiPdo();
    $elements = new Elements($pdo);

    if($request->id == -1)
        $result['elemento'] = $elements->getEmptyDbKeyArray();
    else
        $result['elemento'] = $elements->findByPk($request->id, Elements::FETCH_KEYARRAY);

    return json_encode($result);
}

function salva($request){

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

function elimina($request){

    $result = array();

    $pdo = connettiPdo();
    try{
        $pdo->beginTransaction();
        $elements = new Elements($pdo);

        if($request->id == -1)
            $elements->truncateTable();
        else
            $elements->deleteByPk($request->id);

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

