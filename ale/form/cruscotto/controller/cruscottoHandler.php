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

function getDatiPagina($request){

    $result = array();

    $pdo = connettiPdo();

    $post = new Post($pdo);
    //recupero i miei post e quelli dei miei amici
    $result['elencoPost'] = $post->findPostUtenteAmiciByIdUtente(Utente::findIdUtenteByIdLoginStatic($pdo, getLoginDataFromSession('id')), Post::FETCH_KEYARRAY);

    return json_encode($result);
}


ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;
