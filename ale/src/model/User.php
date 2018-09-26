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

        $query = "SELECT id FROM users WHERE username = ? AND password = ?";

        return $this->createResultValue($query, array($username, $password));
    }

    function countUsersByUsernamePassword($username, $password){

        $query = "SELECT COUNT(id) FROM users WHERE username = ? and password = ?";

        return $this->createResultValue($query, array($username, $password));
    }

}