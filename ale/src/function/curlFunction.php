<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 07/12/17
 * Time: 17.05
 */


function callWsGet($param, $toArray=true){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, URL_WS.$param);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(  "Cache-Control: no-cache"));

    if ($toArray)
        return json_decode(utf8_encode(curl_exec($curl)));
    else
        return utf8_encode(curl_exec($curl));
}

function callWsPost($function,$jsonKey,$jsonValues){

    $ch = curl_init(URL_WS.$function);

    //The JSON data.
    $app = array();
    $app[$jsonKey]=$jsonValues;
    $jsonData = array();
    $jsonData["Request"]=$app;

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //Execute the request
    $result = curl_exec($ch);

    return $result;
}

