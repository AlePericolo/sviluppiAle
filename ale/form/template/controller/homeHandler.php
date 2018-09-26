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


//==============================LOGIN FUNCTIONS=======================================

function getUtilityData($request){

    $result = array();

    $result['username'] = getLoginDataFromSession('username');

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