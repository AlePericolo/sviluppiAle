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

function getDatiPagina($request){

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

//create/read session
ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;

