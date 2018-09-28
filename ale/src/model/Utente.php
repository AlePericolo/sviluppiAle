<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 26/09/18
 * Time: 15.26
 */

require_once 'UtenteModel.php';

class Utente extends UtenteModel
{
    /*-------------------*/
    /* METODI            */
    /*-------------------*/
    function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    function findIdUtenteByIdLogin($idLogin){

        $query = "SELECT id FROM utente WHERE id_login = ?";

        return $this->createResultValue($query, array($idLogin));
    }

    function cercaNuoviAmici($idLogin, $typeResult=self::FETCH_OBJ){

        $query = "SELECT id, nome, cognome, foto FROM utente WHERE id_login != ?";

        return $this->createResultArray($query, array($idLogin), $typeResult);
    }

}