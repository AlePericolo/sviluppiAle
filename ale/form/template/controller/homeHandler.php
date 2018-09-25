<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 22/11/17
 * Time: 15.25
 */

require_once '../../../conf/conf.php';
require_once '../../../src/function/functionLogin.php';
require_once '../../../src/function/functionDate.php';

require_once '../../../src/model/Carrello.php';

//==============================LOGIN FUNCTIONS=======================================

function getUtilityData($request){

    $result = array();

    $result['nomeUtente'] = getLoginDataFromSession('nomeUtente');
    $result['descrizioneDitta'] = getLoginDataFromSession('descrizioneDitta');
    $result['ragioneSociale'] = getLoginDataFromSession('ragioneSociale');
    $result['urlImage'] = URL_IMG;
    $result['urlImageNotFound'] = URL_IMG_NOT_FOUND;

    return json_encode($result);
}

function articoliCarrello($request){

    $params = array();
    $params['mag'] = getLoginDataFromSession('magazzino')[0]->codice;
    $params['causale'] = getLoginDataFromSession('causale');
    $carrello = new \Dsc\Carrello('V', getLoginDataFromSession('utente'), getLoginDataFromSession('conto'), getLoginDataFromSession('ditta'));
    $result['articoliCarrello'] = $carrello->articoliCarrello($params);

    return json_encode($result);
}

function effettuaLogout($request){
    return clearSession();
}

ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;