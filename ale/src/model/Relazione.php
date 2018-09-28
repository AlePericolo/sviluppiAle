<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 28/09/18
 * Time: 10.48
 */

require_once 'RelazioneModel.php';

class Relazione extends RelazioneModel
{
    /*-------------------*/
    /* METODI            */
    /*-------------------*/
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}