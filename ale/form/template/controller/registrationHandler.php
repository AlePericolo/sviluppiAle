<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 26/09/18
 * Time: 10.05
 */

require_once '../../../conf/conf.php';
require_once '../../../src/lib/pdo.php';
require_once '../../../src/lib/functions.php';

require_once '../../../src/model/Login.php';

function registraUtente($request){

    $pdo = connettiPdo();
    $login =  new Login($pdo);

    if($login->countUsersByUsernamePassword($request->registration->username, $request->registration->password) > 0){
        return json_encode(array("status"=>"KO", "message"=>"Attenzione! Utente già presente."));
    }else{

        $result = array();

        try{
            $pdo->beginTransaction();
            $login->creaObjJson($request->registration, true);
            $login->saveOrUpdate();
            $pdo->commit();
            $result['status'] = 'OK';
            $result['message'] = 'Utente registrato correttamente';
        }catch (PDOException $e){
            $pdo->rollBack();
            $result['status'] = 'KO';
            $result['message'] = $e->getMessage();
        }

        return json_encode($result);
    }
}


ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;