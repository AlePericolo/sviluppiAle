<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 02/10/18
 * Time: 15.33
 */

require_once 'PostModel.php';

class Post extends PostModel
{
    /*-------------------*/
    /* METODI            */
    /*-------------------*/
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

}
