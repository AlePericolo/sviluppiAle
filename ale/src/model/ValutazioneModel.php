<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 24/10/18
 * Time: 11.37
 */

require_once 'AbstractModel.php';

/**
 * @property string nomeTabella
 * @property string tableName
 */
class ValutazioneModel extends AbstractModel
{
    /** @var integer */
    protected $id;
    /** @var integer */
    protected $id_post;
    /** @var integer */
    protected $id_utente;
    /** @var integer */
    protected $valutazione;


    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'valutazione';
        $this->tableName = 'valutazione';
    }


    /**
     * find by tables' Primary Key:
     * @return Valutazione|array|string|null
     */
    public function findByPk($id, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName USE INDEX(PRIMARY) WHERE id=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResult($query, array($id), $typeResult);
    }

    /**
     * delete by tables' Primary Key:
     */
    public function deleteByPk($id)
    {
        $query = "DELETE FROM $this->tableName  WHERE id=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id));
    }

    /**
     * Find all record of table
     * @return Valutazione[]|array|string
     */
    public function findAll($distinct = false, $typeResult = self::FETCH_OBJ, $limit = -1, $offset = -1)
    {
        $distinctStr = ($distinct) ? 'DISTINCT' : '';
        $query = "SELECT $distinctStr * FROM $this->tableName ";
        if ($this->whereBase) $query .= " WHERE $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        $query .= $this->createLimitQuery($limit, $offset);
        return $this->createResultArray($query, null, $typeResult);
    }

    /**
     * find by tables' Key id_utente_idx:
     * @return Valutazione[]|array|string
     */
    public function findByid_postIdx($id_post, $typeResult = self::FETCH_OBJ, $limit = -1, $offset = -1)
    {
        $query = "SELECT * FROM $this->tableName USE INDEX(id_utente_idx) WHERE id_utente=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultArray($query, array($id_post), $typeResult);
    }

    /**
     * find by tables' Key FK_id_concessione_idx:
     * @return Valutazione[]|array|string
     */
    public function findByFkid_utenteIdx($id_utente, $typeResult = self::FETCH_OBJ, $limit = -1, $offset = -1)
    {
        $query = "SELECT * FROM $this->tableName USE INDEX(FK_id_concessione_idx) WHERE id_concessione=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultArray($query, array($id_utente), $typeResult);
    }

    /**
     * delete by tables' Key id_utente_idx:
     * @return boolean
     */
    public function deleteByid_postIdx($id_post, $typeResult = self::FETCH_OBJ)
    {
        $query = "DELETE FROM $this->tableName WHERE id_utente=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id_post));
    }

    /**
     * delete by tables' Key FK_id_concessione_idx:
     * @return boolean
     */
    public function deleteByFkid_utenteIdx($id_utente, $typeResult = self::FETCH_OBJ)
    {
        $query = "DELETE FROM $this->tableName WHERE id_concessione=? ";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id_utente));
    }

    /**
     * find by id
     * @return Valutazione[]
     */
    public function findById($id, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName WHERE id=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        return $this->createResultArray($query, array($id), $typeResult);
    }


    /**
     * find by id_utente
     * @return Valutazione[]
     */
    public function findByid_post($id_post, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_utente=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        return $this->createResultArray($query, array($id_post), $typeResult);
    }


    /**
     * find by id_concessione
     * @return Valutazione[]
     */
    public function findByid_utente($id_utente, $typeResult = self::FETCH_OBJ)
    {
        $query = "SELECT * FROM $this->tableName WHERE id_concessione=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
        return $this->createResultArray($query, array($id_utente), $typeResult);
    }


    /**
     * delete by id_utente
     * @return boolean
     */
    public function deleteByid_post($id_post)
    {
        $query = "DELETE FROM $this->tableName WHERE id_utente=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id_post));
    }

    /**
     * delete by id_concessione
     * @return boolean
     */
    public function deleteByid_utente($id_utente)
    {
        $query = "DELETE FROM $this->tableName WHERE id_concessione=?";
        if ($this->whereBase) $query .= " AND $this->whereBase";
        return $this->createResultValue($query, array($id_utente));
    }

    /**
     * Transforms the object into a key array
     * @return array
     */
    public function createKeyArray()
    {
        $arrayValue = array();
        if (isset($this->id)) $arrayValue['id'] = $this->id;
        if (isset($this->id_post)) $arrayValue['id_post'] = $this->id_post;
        if (isset($this->id_utente)) $arrayValue['id_utente'] = $this->id_utente;
        if (isset($this->valutazione)) $arrayValue['valutazione'] = $this->valutazione;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['valutazione_id']))) {
            $this->setId(isset($keyArray['id']) ? $keyArray['id'] : $keyArray['valutazione_id']);
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_post'])) || (isset($keyArray['valutazione_id_post']))) {
            $this->setIdPost(isset($keyArray['id_post']) ? $keyArray['id_post'] : $keyArray['valutazione_id_post']);
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_utente'])) || (isset($keyArray['valutazione_id_utente']))) {
            $this->setIdUtente(isset($keyArray['id_utente']) ? $keyArray['id_utente'] : $keyArray['valutazione_id_utente']);
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['valutazione'])) || (isset($keyArray['valutazione_valutazione']))) {
            $this->setValutazione(isset($keyArray['valutazione']) ? $keyArray['valutazione'] : $keyArray['valutazione_valutazione']);
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
        $values['id_post'] = $positionalArray[1];
        $values['id_utente'] = $positionalArray[2];
        $values['valutazione'] = $positionalArray[3];
        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = null;
        $values['id_post'] = null;
        $values['id_utente'] = null;
        $values['valutazione'] = null;
        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'valutazione.id as valutazione_id,valutazione.id_post as valutazione_id_post,valutazione.id_utente as valutazione_id_utente,valutazione.valutazione as valutazione_valutazione';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE `valutazione` (
                      `id` INT NOT NULL AUTO_INCREMENT,
                      `id_post` INT NOT NULL,
                      `id_utente` INT NOT NULL,
                      `valutazione` INT NOT NULL,
                      PRIMARY KEY (`id`),
                      INDEX `fk_valutazione_post_idx` (`id_post` ASC),
                      INDEX `fk_valutazione_utente_idx` (`id_utente` ASC),
                      CONSTRAINT `fk_valutazione_post`
                        FOREIGN KEY (`id_post`)
                        REFERENCES `post` (`id`)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION,
                      CONSTRAINT `fk_valutazione_utente`
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
    public function getIdPost()
    {
        return $this->id_post;
    }

    /**
     * @param int $id_post
     */
    public function setIdPost($id_post)
    {
        $this->id_post = $id_post;
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
     * @return int
     */
    public function getValutazione()
    {
        return $this->valutazione;
    }

    /**
     * @param int $valutazione
     */
    public function setValutazione($valutazione)
    {
        $this->valutazione = $valutazione;
    }
}