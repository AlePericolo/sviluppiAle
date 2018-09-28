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

}

function cercaAmici($request){

    $result = array();

    $pdo = connettiPdo();
    $utente = new Utente($pdo);

    if($request->filtro != null){
        //ricerca amici con filtri
    }else{
        $result['ricercaAmici'] = $utente->cercaNuoviAmici(getLoginDataFromSession('id'), Utente::FETCH_KEYARRAY);
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