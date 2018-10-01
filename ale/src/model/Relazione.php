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

    function findIdRichiestiFromIdRichiedente($idRichiedente, $typeResult=self::FETCH_OBJ){

        $query = "SELECT id_richiesto FROM relazione WHERE id_richiedente = ?";
        return $this->createResultArray($query, array($idRichiedente), $typeResult);
    }

    function findRelazioneByIdRichiedenteAndRichiesto($idRichiedente, $idRichiesto, $typeResult=self::FETCH_OBJ){

        $query = "SELECT * FROM relazione WHERE id_richiedente = ? AND id_richiesto = ?";
        return $this->createResult($query, array($idRichiedente, $idRichiesto), $typeResult);
    }

    function deleteRelazioneByIdRichiedenteAndRichiesto($idRichiedente, $idRichiesto, $typeResult=self::FETCH_OBJ){

        $query = "DELETE FROM relazione WHERE id_richiedente = ? AND id_richiesto = ?";
        return $this->createResult($query, array($idRichiedente, $idRichiesto), $typeResult);
    }

}