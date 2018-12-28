<?php
/**
* Developed by: Alessandro Pericolo
* Date: 28/12/2018
* Time: 11:03
* Version: 0.1
**/

require_once 'AbstractModel.php';

class RelazioneModel extends AbstractModel {

/** @var integer PrimaryKey */
protected $id;
/** @var integer */
protected $id_richiedente;
/** @var integer */
protected $id_richiesto;
/** @var integer */
protected $amicizia;

/* CONSTRUCTOR ------------------------------------------------------------------------------------------------------ */

//constructor
function __construct($pdo){
	parent::__construct($pdo);
	$this->tableName = "relazione";
}

/* FUNCTIONS -------------------------------------------------------------------------------------------------------- */

/** 
* find by PrimaryKey: 
* @return Relazione|array|string|null
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
* @return Relazione[]|array|string
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
	if (isset($this->id_richiedente)) $keyArray["id_richiedente"] = $this->id_richiedente;
	if (isset($this->id_richiesto)) $keyArray["id_richiesto"] = $this->id_richiesto;
	if (isset($this->amicizia)) $keyArray["amicizia"] = $this->amicizia;
	return $keyArray;
}

/** 
* trasform the KeyArray into a Object 
* @param array $keyArray
**/
public function createObjKeyArray(array $keyArray){
	if (isset($keyArray["id"])) $this->id = $keyArray["id"];
	if (isset($keyArray["id_richiedente"])) $this->id_richiedente = $keyArray["id_richiedente"];
	if (isset($keyArray["id_richiesto"])) $this->id_richiesto = $keyArray["id_richiesto"];
	if (isset($keyArray["amicizia"])) $this->amicizia = $keyArray["amicizia"];
}

/** 
* return the Object as an empty KeyArray 
* @return array
**/
public function getEmptyKeyArray(){
	$emptyKeyArray = array();
	$emptyKeyArray["id"] = "";
	$emptyKeyArray["id_richiedente"] = "";
	$emptyKeyArray["id_richiesto"] = "";
	$emptyKeyArray["amicizia"] = "";
	return $emptyKeyArray;
}

/** 
* return columns' list as string 
* @return string
**/
public function getListColumns(){
	return "id, id_richiedente, id_richiesto, amicizia";
}

/* CREATE TABLE ----------------------------------------------------------------------------------------------------- */

/** 
* DDL create table query 
**/
public function createTable(){
return $this->pdo->exec(
"CREATE TABLE `relazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_richiedente` int(11) NOT NULL,
  `id_richiesto` int(11) NOT NULL,
  `amicizia` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1"
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
public function getIdRichiedente(){
	 return $this->id_richiedente;
}

/** 
* @param integer $id_richiedente
**/
public function setIdRichiedente($id_richiedente){
	 $this->id_richiedente = $id_richiedente;
}

/** 
* @return integer
**/
public function getIdRichiesto(){
	 return $this->id_richiesto;
}

/** 
* @param integer $id_richiesto
**/
public function setIdRichiesto($id_richiesto){
	 $this->id_richiesto = $id_richiesto;
}

/** 
* @return integer
**/
public function getAmicizia(){
	 return $this->amicizia;
}

/** 
* @param integer $amicizia
**/
public function setAmicizia($amicizia){
	 $this->amicizia = $amicizia;
}


} //close Class RelazioneModel