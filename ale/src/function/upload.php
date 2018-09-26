<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 02/08/18
 * Time: 10.52
 */

require_once '../../conf/conf.php';

if (!empty($_FILES)) {

    $tempPath = $_FILES['file']['tmp_name'];
    $filePath = $_FILES['file']['name'];

    //genero il file name con l'estensione
    $file = date('Ymdhis').'.'.explode('.', $filePath)[1];

    if(move_uploaded_file($_FILES['file']['tmp_name'], UPLOAD_PATH_IMG_PROFILO . $file)){
        $answer = array('answer' => 'OK', 'message' => "File caricato correttamente", 'file' => $file);
    }else{
        $answer = array('answer' => 'KO', 'message' => "Il file non è stato caricato");
    }

    echo json_encode($answer);;
}