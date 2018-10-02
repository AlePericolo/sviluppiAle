<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 02/10/18
 * Time: 15.31
 */

require_once 'AbstractModel.php';
/**
 * @property string nomeTabella
 * @property string tableName
 */
class PostModel extends AbstractModel
{
    /** @var integer Chiave primaria della tabella */
    protected $id;
    /** @var integer */
    protected $id_utente;
    /** @var string */
    protected $testo;
    /** @var */
    protected $data_pubblicazione;

    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'post';
        $this->tableName = 'post';
    }

    /**
     * find by tables' Primary Key:
     * @return Utente|array|string|null
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
     * @return Utente[]|array|string
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
     * @return Utente[]
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
        if (isset($this->id_utente)) $arrayValue['id_utente'] = $this->id_utente;
        if (isset($this->testo)) $arrayValue['testo'] = $this->testo;
        if (isset($this->data_pubblicazione)) $arrayValue['data_pubblicazione'] = $this->data_pubblicazione;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['post_id']))) {
            $this->id = (isset($keyArray['id'])) ? $keyArray['id'] : $keyArray['post_id'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_utente'])) || (isset($keyArray['post_id_utente']))) {
            $this->id = (isset($keyArray['id_utente'])) ? $keyArray['id_utente'] : $keyArray['post_id_utente'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['testo'])) || (isset($keyArray['post_testo']))) {
            $this->username = (isset($keyArray['testo'])) ? $keyArray['testo'] : $keyArray['post_testo'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['cognome'])) || (isset($keyArray['post_cognome']))) {
            $this->password = (isset($keyArray['cognome'])) ? $keyArray['cognome'] : $keyArray['post_cognome'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['sesso'])) || (isset($keyArray['post_sesso']))) {
            $this->password = (isset($keyArray['sesso'])) ? $keyArray['sesso'] : $keyArray['post_sesso'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['data_pubblicazione'])) || (isset($keyArray['post_data_pubblicazione']))) {
            $this->password = (isset($keyArray['data_pubblicazione'])) ? $keyArray['data_pubblicazione'] : $keyArray['post_data_pubblicazione'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['foto'])) || (isset($keyArray['post_foto']))) {
            $this->password = (isset($keyArray['foto'])) ? $keyArray['foto'] : $keyArray['post_foto'];
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
        $values['id_utente'] = $positionalArray[1];
        $values['testo'] = $positionalArray[2];
        $values['data_pubblicazione'] = $positionalArray[3];

        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = '';
        $values['id_utente'] = '';
        $values['testo'] = '';
        $values['data_pubblicazione'] = '';

        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'utente.id as post_id, 
                utente.id_utente as post_id_utente, 
                utente.testo as post_testo,
                utente.data_pubblicazione as post_data_pubblicazione';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE `post` (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `id_utente` INT NOT NULL,
                            `testo` TEXT NULL,
                            `data_pubblicazione` VARCHAR(45) NULL,
                        PRIMARY KEY (`id`),
                              INDEX `fk_post_utente_idx` (`id_utente` ASC),
                              CONSTRAINT `fk_post_utente`
                                FOREIGN KEY (`id_utente`)
                                REFERENCES `utente` (`id`)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION);"
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
    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * @param int $id_utente
     */
    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * @return string
     */
    public function getTesto()
    {
        return $this->testo;
    }

    /**
     * @param string $testo
     */
    public function setTesto($testo)
    {
        $this->testo = $testo;
    }

    /**
     * @return mixed
     */
    public function getDataPubblicazione()
    {
        return $this->data_pubblicazione;
    }

    /**
     * @param mixed $data_pubblicazione
     */
    public function setDataPubblicazione($data_pubblicazione)
    {
        $this->data_pubblicazione = $data_pubblicazione;
    }


}

