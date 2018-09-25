<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 27/08/18
 * Time: 17.44
 */

require_once '../../../conf/conf.php';
require_once '../../../src/function/curlFunction.php';
require_once '../../../src/function/functionLogin.php';


ob_start();
session_start();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$function = $request->function;
$r = $function($request);
echo $r;
