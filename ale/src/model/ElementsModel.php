<?php
/**
* Developed by: Alessandro Pericolo
* Date: 29/11/2018
* Time: 18:00
* Version: 0.1
**/

require_once 'AbstractModel.php';

class ElementsModel extends AbstractModel {

/** @var integer PrimaryKey */
protected $id;
/** @var string */
protected $descrizione;
/** @var DateTime */
protected $date;

/* CONSTRUCTOR ------------------------------------------------------------------------------------------------------ */

//constructor
function __construct($pdo){
	parent::__construct($pdo);
	$this->tableName = "elements";
}

/* FUNCTIONS -------------------------------------------------------------------------------------------------------- */

/** 
* find by PrimaryKey: 
* @return Elements|array|string|null
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
* @return Elements[]|array|string
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
	if (isset($this->id)) $keyArray["id"] = (int)$this->id;
	if (isset($this->descrizione)) $keyArray["descrizione"] = $this->descrizione;
	if (isset($this->date)) $keyArray["date"] = $this->date;
	return $keyArray;
}

/** 
* trasform the KeyArray into a Object 
* @param array $keyArray
**/
public function createObjKeyArray(array $keyArray){
	if (isset($keyArray["id"])) $this->id = $keyArray["id"];
	if (isset($keyArray["descrizione"])) $this->descrizione = $keyArray["descrizione"];
	if (isset($keyArray["date"]) && $keyArray["date"] != '') $this->date = date("Ymd", strtotime($keyArray["date"]));
}

/** 
* return the Object as an empty KeyArray 
* @return array
**/
public function getEmptyKeyArray(){
	$emptyKeyArray = array();
	$emptyKeyArray["id"] = "";
	$emptyKeyArray["descrizione"] = "";
	$emptyKeyArray["date"] = "";
	return $emptyKeyArray;
}

/** 
* return columns' list as string 
* @return string
**/
public function getListColumns(){
	return "id, descrizione, date";
}

/* CREATE TABLE ----------------------------------------------------------------------------------------------------- */

/** 
* DDL create table query 
**/
public function createTable(){
return $this->pdo->exec(
"CREATE TABLE `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(45) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1"
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
* @return string
**/
public function getDescrizione(){
	 return $this->descrizione;
}

/** 
* @param string $descrizione
* @param int $encodeType
 **/
public function setDescrizione($descrizione, $encodeType = self::STR_DEFAULT){
	 $this->descrizione = $this->decodeString($descrizione, $encodeType);
}

/** 
* @return DateTime
**/
public function getDate(){
	 return $this->date;
}

/** 
* @param DateTime $date
**/
public function setDate($date){
	 $this->date = $date;
}


} //close Class ElementsModel