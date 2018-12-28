<?php
/**
* Developed by: Alessandro Pericolo
* Date: 28/12/2018
* Time: 11:03
* Version: 0.1
**/

require_once 'AbstractModel.php';

class PostModel extends AbstractModel {

/** @var integer PrimaryKey */
protected $id;
/** @var integer */
protected $id_utente;
/** @var string */
protected $testo;
/** @var DateTime */
protected $data_pubblicazione;

/* CONSTRUCTOR ------------------------------------------------------------------------------------------------------ */

//constructor
function __construct($pdo){
	parent::__construct($pdo);
	$this->tableName = "post";
}

/* FUNCTIONS -------------------------------------------------------------------------------------------------------- */

/** 
* find by PrimaryKey: 
* @return Post|array|string|null
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
* @return Post[]|array|string
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
	if (isset($this->id_utente)) $keyArray["id_utente"] = $this->id_utente;
	if (isset($this->testo)) $keyArray["testo"] = $this->testo;
	if (isset($this->data_pubblicazione)) $keyArray["data_pubblicazione"] = $this->data_pubblicazione;
	return $keyArray;
}

/** 
* trasform the KeyArray into a Object 
* @param array $keyArray
**/
public function createObjKeyArray(array $keyArray){
	if (isset($keyArray["id"])) $this->id = $keyArray["id"];
	if (isset($keyArray["id_utente"])) $this->id_utente = $keyArray["id_utente"];
	if (isset($keyArray["testo"])) $this->testo = $keyArray["testo"];
	if (isset($keyArray["data_pubblicazione"]) && $keyArray["data_pubblicazione"] != "") $this->data_pubblicazione = date("Ymd", strtotime($keyArray["data_pubblicazione"]));
}

/** 
* return the Object as an empty KeyArray 
* @return array
**/
public function getEmptyKeyArray(){
	$emptyKeyArray = array();
	$emptyKeyArray["id"] = "";
	$emptyKeyArray["id_utente"] = "";
	$emptyKeyArray["testo"] = "";
	$emptyKeyArray["data_pubblicazione"] = "";
	return $emptyKeyArray;
}

/** 
* return columns' list as string 
* @return string
**/
public function getListColumns(){
	return "id, id_utente, testo, data_pubblicazione";
}

/* CREATE TABLE ----------------------------------------------------------------------------------------------------- */

/** 
* DDL create table query 
**/
public function createTable(){
return $this->pdo->exec(
"CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `testo` text,
  `data_pubblicazione` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_utente_idx` (`id_utente`),
  CONSTRAINT `fk_post_utente` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1"
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
public function getIdUtente(){
	 return $this->id_utente;
}

/** 
* @param integer $id_utente
**/
public function setIdUtente($id_utente){
	 $this->id_utente = $id_utente;
}

/** 
* @return string
**/
public function getTesto(){
	 return $this->testo;
}

/** 
* @param string $testo
* @param int $encodeType
 **/
public function setTesto($testo, $encodeType = self::STR_DEFAULT){
	 $this->testo = $this->decodeString($testo, $encodeType);
}

/** 
* @return DateTime
**/
public function getDataPubblicazione(){
	 return $this->data_pubblicazione;
}

/** 
* @param DateTime $data_pubblicazione
**/
public function setDataPubblicazione($data_pubblicazione){
	 $this->data_pubblicazione = $data_pubblicazione;
}


} //close Class PostModel