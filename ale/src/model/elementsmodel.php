<?php
/**
 * Created by Drakkar vers. 0.0.27(Hjortspring)
 * User: P.D.A. Srl
 * Date: 2018-04-04
 * Time: 11:23:39.198883
 */
require_once 'PdaAbstractModel.php';
/**
 * @property string nomeTabella
 * @property string tableName
 */
class ElementsModel extends PdaAbstractModel {
    /** @var integer Chiave primaria della tabella*/
    protected $id;
    /** @var string */
    protected $descrizione;


    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'elements';
        $this->tableName = 'elements';
    }

    /**
     * find by tables' Primary Key:
     * @return Elements|array|string|null
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
     * @return Elements[]|array|string
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
     * @return Elements[]
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
        if (isset($this->descrizione)) $arrayValue['descrizione'] = $this->descrizione;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['elements_id']))) {
            $this->id = (isset($keyArray['id'])) ? $keyArray['id'] : $keyArray['elements_id'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['descrizione'])) || (isset($keyArray['elements_descrizione']))) {
            $this->descrizione = (isset($keyArray['descrizione'])) ? $keyArray['descrizione'] : $keyArray['elements_descrizione'];
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
        $values['descrizione'] = $positionalArray[1];
        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = '';
        $values['descrizione'] = '';
        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'elements.id as elements_id, elements.descrizione as elements_descrizione';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE `elements` (
                        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Chiave primaria della tabella',
                        `descrizione` varchar(45) COLLATE latin1_general_ci NOT NULL DEFAULT ''
                      ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci "
        );
    }

    /**
     * @return integer
     */
    public function getId(){return $this->id;}
    /**
     * @param integer $id Id
     */
    public function setId($id){$this->id=$id;}

    /**
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @param string $descrizione
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }


}