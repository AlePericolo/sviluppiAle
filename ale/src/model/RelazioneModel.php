<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 28/09/18
 * Time: 10.49
 */

require_once 'AbstractModel.php';
/**
 * @property string nomeTabella
 * @property string tableName
 */
class RelazioneModel extends AbstractModel {
    /** @var integer Chiave primaria della tabella*/
    protected $id;
    /** @var integer*/
    protected $id_richiedente;
    /** @var integer */
    protected $id_richiesto;
    /** @var integer */
    protected $amicizia;
    

    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'relazione';
        $this->tableName = 'relazione';
    }

    /**
     * find by tables' Primary Key:
     * @return Relazione|array|string|null
     */
    public function findByPk($id, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName USE INDEX(PRIMARY) WHERE ID=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResult($query, array($id), $typeResult);
    }

    /**
     * delete by tables' Primary Key:
     */
    public function deleteByPk($id)
    {
        $query = "DELETE FROM $this->tableName  WHERE ID=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id));
    }

    /**
     * Find all record of table
     * @return Relazione[]|array|string
     */
    public function findAll($distinct = false, $typeResult = self::FETCH_OBJ, $limit = -1, $offset = -1)
    {
        $distinctStr = ($distinct) ? 'DISTINCT' : '';
        $query = "SELECT $distinctStr * FROM $this->tableName";
        if ($this->whereBase) $query .= " WHERE $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        $query .= $this->createLimitQuery($limit, $offset);
        return $this->createResultArray($query, null, $typeResult);
    }

    /**
     * find by ID
     * @return Relazione[]
     */
    public function findById($id, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName WHERE ID=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        return $this->createResultArray($query, array($id), $typeResult);
    }


    /**
     * Transforms the object into a key array
     * @return array
     */
    public function createKeyArray()
    {
        $arrayValue = array();
        if (isset($this->id)) $arrayValue['id'] = $this->id;
        if (isset($this->id_richiedente)) $arrayValue['id_richiedente'] = $this->id_richiedente;
        if (isset($this->id_richiesto)) $arrayValue['id_richiesto'] = $this->id_richiesto;
        if (isset($this->amicizia)) $arrayValue['amicizia'] = $this->amicizia;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['relazione_id']))) {
            $this->id = (isset($keyArray['id'])) ? $keyArray['id'] : $keyArray['relazione_id'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_richiedente'])) || (isset($keyArray['relazione_id_richiedente']))) {
            $this->id = (isset($keyArray['id_richiedente'])) ? $keyArray['id_richiedente'] : $keyArray['relazione_id_richiedente'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_richiesto'])) || (isset($keyArray['relazione_id_richiesto']))) {
            $this->username = (isset($keyArray['id_richiesto'])) ? $keyArray['id_richiesto'] : $keyArray['relazione_id_richiesto'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['amicizia'])) || (isset($keyArray['relazione_amicizia']))) {
            $this->password = (isset($keyArray['amicizia'])) ? $keyArray['amicizia'] : $keyArray['relazione_amicizia'];
            $this->flagObjectDataValorized = true;
        }
    }

    /**
     * @return array
     */
    public function createKeyArrayFromPositional($positionalArray)
    {
        $values = array();
        $values['id'] = $positionalArray[0];
        $values['id_richiedente'] = $positionalArray[1];
        $values['id_richiesto'] = $positionalArray[2];
        $values['amicizia'] = $positionalArray[3];

        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = '';
        $values['id_richiedente'] = '';
        $values['id_richiesto'] = '';
        $values['amicizia'] = '';

        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'relazione.id as relazione_id, 
                relazione.id_richiedente as relazione_id_richiedente, 
                relazione.id_richiesto as relazione_id_richiesto,
                relazione.amicizia as relazione_amicizia';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE relazione` (
                      `id` INT NOT NULL AUTO_INCREMENT,
                      `id_richiedente` INT NOT NULL,
                      `id_richiesto` INT NOT NULL,
                      `amicizia` TINYINT NOT NULL,
                      PRIMARY KEY (`id`));"
        );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdRichiedente()
    {
        return $this->id_richiedente;
    }

    /**
     * @param int $id_richiedente
     */
    public function setIdRichiedente($id_richiedente)
    {
        $this->id_richiedente = $id_richiedente;
    }

    /**
     * @return int
     */
    public function getIdRichiesto()
    {
        return $this->id_richiesto;
    }

    /**
     * @param int $id_richiesto
     */
    public function setIdRichiesto($id_richiesto)
    {
        $this->id_richiesto = $id_richiesto;
    }

    /**
     * @return int
     */
    public function getAmicizia()
    {
        return $this->amicizia;
    }

    /**
     * @param int $amicizia
     */
    public function setAmicizia($amicizia)
    {
        $this->amicizia = $amicizia;
    }

}