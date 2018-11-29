<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 26/09/18
 * Time: 15.26
 */

require_once 'AbstractModel.php';
/**
 * @property string nomeTabella
 * @property string tableName
 */
class UtenteModel extends AbstractModel {
    /** @var integer Chiave primaria della tabella*/
    protected $id;
    /** @var integer*/
    protected $id_login;
    /** @var string */
    protected $nome;
    /** @var string */
    protected $cognome;
    /** @var string */
    protected $sesso;
    /** @var  */
    protected $data_nascita;
    /** @var string */
    protected $foto;

    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'utente';
        $this->tableName = 'utente';
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
        if (isset($this->id_login)) $arrayValue['id_login'] = $this->id_login;
        if (isset($this->nome)) $arrayValue['nome'] = $this->nome;
        if (isset($this->cognome)) $arrayValue['cognome'] = $this->cognome;
        if (isset($this->sesso)) $arrayValue['sesso'] = $this->sesso;
        if (isset($this->data_nascita)) $arrayValue['data_nascita'] = $this->data_nascita;
        if (isset($this->foto)) $arrayValue['foto'] = $this->foto;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['utente_id']))) {
            $this->id = (isset($keyArray['id'])) ? $keyArray['id'] : $keyArray['utente_id'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['id_login'])) || (isset($keyArray['utente_id_login']))) {
            $this->id = (isset($keyArray['id_login'])) ? $keyArray['id_login'] : $keyArray['utente_id_login'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['nome'])) || (isset($keyArray['utente_nome']))) {
            $this->username = (isset($keyArray['nome'])) ? $keyArray['nome'] : $keyArray['utente_nome'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['cognome'])) || (isset($keyArray['utente_cognome']))) {
            $this->password = (isset($keyArray['cognome'])) ? $keyArray['cognome'] : $keyArray['utente_cognome'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['sesso'])) || (isset($keyArray['utente_sesso']))) {
            $this->password = (isset($keyArray['sesso'])) ? $keyArray['sesso'] : $keyArray['utente_sesso'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['data_nascita'])) || (isset($keyArray['utente_data_nascita']))) {
            $this->password = (isset($keyArray['data_nascita'])) ? $keyArray['data_nascita'] : $keyArray['utente_data_nascita'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['foto'])) || (isset($keyArray['utente_foto']))) {
            $this->password = (isset($keyArray['foto'])) ? $keyArray['foto'] : $keyArray['utente_foto'];
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
        $values['id_login'] = $positionalArray[1];
        $values['nome'] = $positionalArray[2];
        $values['cognome'] = $positionalArray[3];
        $values['sesso'] = $positionalArray[4];
        $values['data_nascita'] = $positionalArray[5];
        $values['foto'] = $positionalArray[6];
        
        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = '';
        $values['id_login'] = '';
        $values['nome'] = '';
        $values['cognome'] = '';
        $values['sesso'] = '';
        $values['data_nascita'] = '';
        $values['foto'] = '';
        
        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'utente.id as utente_id, 
                utente.id_login as utente_id_login, 
                utente.nome as utente_nome,
                utente.cognome as utente_cognome,
                utente.sesso as utente_sesso,
                utente.data_nascita as utente_data_nascita,
                utente.foto as utente_foto';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE `utente` (
                                `id` INT NOT NULL AUTO_INCREMENT,
                                `id_login` INT NOT NULL,
                                `nome` VARCHAR(45) NULL,
                                `cognome` VARCHAR(45) NULL,
                                `sesso` VARCHAR(1) NULL,
                                `data_nascita` DATE NULL,
                                `foto` VARCHAR(45) NULL,
                              PRIMARY KEY (`id`),
                              INDEX `fk_utente_login_idx` (`id_login` ASC),
                              CONSTRAINT `fk_utente_login`
                                FOREIGN KEY (`id_login`)
                                REFERENCES `ale_test`.`login` (`id`)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION)"
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
    public function getIdLogin()
    {
        return $this->id_login;
    }

    /**
     * @param int $id_login
     */
    public function setIdLogin($id_login)
    {
        $this->id_login = $id_login;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * @param string $cognome
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;
    }

    /**
     * @return string
     */
    public function getSesso()
    {
        return $this->sesso;
    }

    /**
     * @param string $sesso
     */
    public function setSesso($sesso)
    {
        $this->sesso = $sesso;
    }

    /**
     * @return mixed
     */
    public function getDataNascita()
    {
        return $this->data_nascita;
    }

    /**
     * @param mixed $data_nascita
     */
    public function setDataNascita($data_nascita)
    {
        $this->data_nascita = $data_nascita;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

}