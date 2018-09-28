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

    static function findIdUtenteByIdLoginStatic($pdo, $idLogin){

        $app = new self($pdo);
        return $app->findIdUtenteByIdLogin($idLogin);
    }

    function cercaNuoviAmici($idLogin, $idRichiedente, $typeResult=self::FETCH_OBJ){

        $query = "SELECT id, CONCAT (nome, ' ', cognome) AS nominativo ,foto 
                  FROM utente 
                  WHERE id_login != ? AND id NOT IN (
	                SELECT id_richiesto 
	                FROM relazione 
	                WHERE id_richiedente = ? 
	                GROUP BY id_richiesto
                  );";

        return $this->createResultArray($query, array($idLogin, $idRichiedente), $typeResult);
    }

    function findRichiesteInAttesaByIdRichiedente($idRichiedente, $typeResult=self::FETCH_OBJ){

        $query = "SELECT  id, CONCAT (nome, ' ', cognome) AS nominativo ,foto 
                  FROM utente 
                  WHERE id IN (
                        SELECT id_richiesto 
                        FROM relazione 
                        WHERE id_richiedente = ?
                  );";

        return $this->createResultArray($query, array($idRichiedente), $typeResult);
    }

}