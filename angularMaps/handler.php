<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 18/04/19
 * Time: 10.24
 */

function timeoutLocalization($request) {
    return json_encode(array("latitude"=>$request->latitude,"longitude"=>$request->longitude));
}

ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;