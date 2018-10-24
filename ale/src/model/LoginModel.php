<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 25/09/18
 * Time: 17.47
 */

require_once 'AbstractModel.php';
/**
 * @property string nomeTabella
 * @property string tableName
 */
class LoginModel extends AbstractModel {
    /** @var integer Chiave primaria della tabella*/
    protected $id;
    /** @var string */
    protected $username;
    /** @var string */
    protected $password;


    function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->nomeTabella = 'login';
        $this->tableName = 'login';
    }

    /**
     * find by tables' Primary Key:
     * @return Login|array|string|null
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
     * @return Login[]|array|string
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
     * @return Login[]
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
        if (isset($this->username)) $arrayValue['username'] = $this->username;
        if (isset($this->password)) $arrayValue['password'] = $this->password;
        return $arrayValue;
    }

    /**
     * It transforms the keyarray in an $positionalArray[%s]object
     */
    public function createObjKeyArray(array $keyArray)
    {
        $this->flagObjectDataValorized = false;
        if ((isset($keyArray['id'])) || (isset($keyArray['login_id']))) {
            $this->id = (isset($keyArray['id'])) ? $keyArray['id'] : $keyArray['login_id'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['username'])) || (isset($keyArray['login_username']))) {
            $this->username = (isset($keyArray['username'])) ? $keyArray['username'] : $keyArray['login_username'];
            $this->flagObjectDataValorized = true;
        }
        if ((isset($keyArray['password'])) || (isset($keyArray['login_password']))) {
            $this->password = (isset($keyArray['password'])) ? $keyArray['password'] : $keyArray['login_password'];
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
        $values['username'] = $positionalArray[1];
        $values['password'] = $positionalArray[2];
        return $values;
    }

    /**
     * @return array
     */
    public function getEmptyDbKeyArray()
    {
        $values = array();
        $values['id'] = '';
        $values['username'] = '';
        $values['password'] = '';
        return $values;
    }

    /**
     * Return columns' list
     * @return string
     */
    public function getListColumns()
    {
        return 'login.id as login_id, login.username as login_username, login.password as login_password';
    }

    /**
     * DDL Table
     */
    public function createTable()
    {
        return $this->pdo->exec(
            "CREATE TABLE `login` (
                        `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Chiave primaria della tabella',
                        `username` varchar(45) COLLATE latin1_general_ci NOT NULL DEFAULT '',
                        `password` varchar(45) COLLATE latin1_general_ci NOT NULL DEFAULT ''
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

}