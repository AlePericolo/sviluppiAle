<?php
/**
* Developed by: Alessandro Pericolo
* Date: 29/11/2018
* Time: 17:02
* Version: 0.1
**/

require_once 'AbstractModel.php';

class UtenteModel extends AbstractModel {

/** @var integer PrimaryKey */
protected $id;
/** @var integer */
protected $id_login;
/** @var string */
protected $nome;
/** @var string */
protected $cognome;
/** @var string */
protected $sesso;
/** @var DateTime */
protected $data_nascita;
/** @var string */
protected $foto;

/* CONSTRUCTOR ------------------------------------------------------------------------------------------------------ */

//constructor
function __construct($pdo){
	parent::__construct($pdo);
	$this->tableName = "utente";
}

/* FUNCTIONS -------------------------------------------------------------------------------------------------------- */

/** 
* find by PrimaryKey: 
* @return Utente|array|string|null
**/
public function findByPk($id, $typeResult = self::FETCH_OBJ){
	$query = "SELECT * FROM $this->tableName USE INDEX(PRIMARY) WHERE ID=?";
	return $this->createResult($query, array($id), $typeResult);
}

/** 
* delete by PrimaryKey: 
**/
public function deleteByPk($id){
	$query = "DELETE FROM $this->tableName WHERE ID=?";
	return $this->createResultValue($query, array($id));
}

/** 
* find all record of table 
* @return Utente[]|array|string
**/
public function findAll($distinct = false, $typeResult = self::FETCH_OBJ, $limit = -1, $offset = -1){
	$distinctStr = ($distinct) ? "DISTINCT" : "";
	$query = "SELECT $distinctStr * FROM $this->tableName ";
	if ($this->whereBase) $query .= " WHERE $this->whereBase";
	if ($this->orderBase) $query .= " ORDER BY $this->orderBase";
	$query .= $this->createLimitQuery($limit, $offset);
	return $this->createResultArray($query, null, $typeResult);
}

/** 
* trasform the Object into a KeyArray 
* @return array
**/
public function createKeyArray(){
	$keyArray = array();
	if (isset($this->id)) $keyArray["id"] = $this->id;
	if (isset($this->id_login)) $keyArray["id_login"] = $this->id_login;
	if (isset($this->nome)) $keyArray["nome"] = $this->nome;
	if (isset($this->cognome)) $keyArray["cognome"] = $this->cognome;
	if (isset($this->sesso)) $keyArray["sesso"] = $this->sesso;
	if (isset($this->data_nascita)) $keyArray["data_nascita"] = $this->data_nascita;
	if (isset($this->foto)) $keyArray["foto"] = $this->foto;
	return $keyArray;
}

/** 
* trasform the KeyArray into a Object 
* @param array $keyArray
**/
public function createObjKeyArray(array $keyArray){
	if (isset($keyArray["id"])) $this->id = $keyArray["id"];
	if (isset($keyArray["id_login"])) $this->id_login = $keyArray["id_login"];
	if (isset($keyArray["nome"])) $this->nome = $keyArray["nome"];
	if (isset($keyArray["cognome"])) $this->cognome = $keyArray["cognome"];
	if (isset($keyArray["sesso"])) $this->sesso = $keyArray["sesso"];
	if (isset($keyArray["data_nascita"])) $this->data_nascita = $keyArray["data_nascita"];
	if (isset($keyArray["foto"])) $this->foto = $keyArray["foto"];
}

/** 
* return the Object as an empty KeyArray 
* @return array
**/
public function getEmptyKeyArray(){
	$emptyKeyArray = array();
	$emptyKeyArray["id"] = "";
	$emptyKeyArray["id_login"] = "";
	$emptyKeyArray["nome"] = "";
	$emptyKeyArray["cognome"] = "";
	$emptyKeyArray["sesso"] = "";
	$emptyKeyArray["data_nascita"] = "";
	$emptyKeyArray["foto"] = "";
	return $emptyKeyArray;
}

/** 
* return columns' list as string 
* @return string
**/
public function getListColumns(){
	return "id, id_login, nome, cognome, sesso, data_nascita, foto";
}

/* CREATE TABLE ----------------------------------------------------------------------------------------------------- */

/** 
* DDL create table query 
**/
public function createTable(){
return $this->pdo->exec(
"CREATE TABLE `utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_login` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `cognome` varchar(45) DEFAULT NULL,
  `sesso` varchar(1) DEFAULT NULL,
  `data_nascita` date DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_utente_login_idx` (`id_login`),
  CONSTRAINT `fk_utente_login` FOREIGN KEY (`id_login`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1"
);
}

/* GETTER & SETTER -------------------------------------------------------------------------------------------------- */

/** 
* @return integer
**/
public function getId(){
	 return $this->id;
}

/** 
* @param integer $id
**/
public function setId($id){
	 $this->id = $id;
}

/** 
* @return integer
**/
public function getId_Login(){
	 return $this->id_login;
}

/** 
* @param integer $id_login
**/
public function setId_Login($id_login){
	 $this->id_login = $id_login;
}

/** 
* @return string
**/
public function getNome(){
	 return $this->nome;
}

/** 
* @param string $nome
* @param int $encodeType
 **/
public function setNome($nome, $encodeType = self::STR_DEFAULT){
	 $this->nome = $this->decodeString($nome, $encodeType);
}

/** 
* @return string
**/
public function getCognome(){
	 return $this->cognome;
}

/** 
* @param string $cognome
* @param int $encodeType
 **/
public function setCognome($cognome, $encodeType = self::STR_DEFAULT){
	 $this->cognome = $this->decodeString($cognome, $encodeType);
}

/** 
* @return string
**/
public function getSesso(){
	 return $this->sesso;
}

/** 
* @param string $sesso
* @param int $encodeType
 **/
public function setSesso($sesso, $encodeType = self::STR_DEFAULT){
	 $this->sesso = $this->decodeString($sesso, $encodeType);
}

/** 
* @return DateTime
**/
public function getData_Nascita(){
	 return $this->data_nascita;
}

/** 
* @param DateTime $data_nascita
**/
public function setData_Nascita($data_nascita){
	 $this->data_nascita = $data_nascita;
}

/** 
* @return string
**/
public function getFoto(){
	 return $this->foto;
}

/** 
* @param string $foto
* @param int $encodeType
 **/
public function setFoto($foto, $encodeType = self::STR_DEFAULT){
	 $this->foto = $this->decodeString($foto, $encodeType);
}


} //close Class UtenteModel