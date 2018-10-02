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

    function findPostByIdUtente($idUtente, $typeResult=self::FETCH_OBJ){

        $query = "SELECT 
                        post.*,
                        CONCAT (utente.nome, ' ', utente.cognome) AS nominativo,
                        utente.foto
                    FROM post 
                    INNER JOIN utente ON post.id_utente = utente.id
                    WHERE id_utente = ?";

        return $this->createResultArray($query, array($idUtente), $typeResult);
    }

}
