<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 25/09/18
 * Time: 17.48
 */

require_once 'UserModel.php';

class User extends UserModel
{
    /*-------------------*/
    /* METODI            */
    /*-------------------*/
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    function findIdByUsernamePassword($username, $password){

        $query="SELECT id FROM users WHERE username = ? AND password = ?";

        $id = $this->createResultValue($query, array($username, $password));

        return $id;
    }

}