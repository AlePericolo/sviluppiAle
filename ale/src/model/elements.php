<?php

/**
 * Created by Mysql2Php vers. 0.8.16
 * User: P.D.A. Srl
 * Date: 05/05/2016
 * Time:12:01
 */
require_once 'elementsmodel.php';

class Elements extends ElementsModel
{
    /*-------------------*/
    /* METODI            */
    /*-------------------*/
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}

