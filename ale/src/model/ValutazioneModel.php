<?php
/**
* Developed by: Alessandro Pericolo
* Date: 28/12/2018
* Time: 11:03
* Version: 0.1
**/

require_once 'AbstractModel.php';

class ValutazioneModel extends AbstractModel {

/** @var integer PrimaryKey */
protected $id;
/** @var integer */
protected $id_post;
/** @var integer */
protected $id_utente;
/** @var integer */
protected $valutazione;

/* CONSTRUCTOR ------------------------------------------------------------------------------------------------------ */

//constructor
function __construct($pdo){
	parent::__construct($pdo);
	$this->tableName = "valutazione";
}

/* FUNCTIONS -------------------------------------------------------------------------------------------------------- */

/** 
* find by PrimaryKey: 
* @return Valutazione|array|string|null
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
* @return Valutazione[]|array|string
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
	if (isset($this->id_post)) $keyArray["id_post"] = $this->id_post;
	if (isset($this->id_utente)) $keyArray["id_utente"] = $this->id_utente;
	if (isset($this->valutazione)) $keyArray["valutazione"] = $this->valutazione;
	return $keyArray;
}

/** 
* trasform the KeyArray into a Object 
* @param array $keyArray
**/
public function createObjKeyArray(array $keyArray){
	if (isset($keyArray["id"])) $this->id = $keyArray["id"];
	if (isset($keyArray["id_post"])) $this->id_post = $keyArray["id_post"];
	if (isset($keyArray["id_utente"])) $this->id_utente = $keyArray["id_utente"];
	if (isset($keyArray["valutazione"])) $this->valutazione = $keyArray["valutazione"];
}

/** 
* return the Object as an empty KeyArray 
* @return array
**/
public function getEmptyKeyArray(){
	$emptyKeyArray = array();
	$emptyKeyArray["id"] = "";
	$emptyKeyArray["id_post"] = "";
	$emptyKeyArray["id_utente"] = "";
	$emptyKeyArray["valutazione"] = "";
	return $emptyKeyArray;
}

/** 
* return columns' list as string 
* @return string
**/
public function getListColumns(){
	return "id, id_post, id_utente, valutazione";
}

/* CREATE TABLE ----------------------------------------------------------------------------------------------------- */

/** 
* DDL create table query 
**/
public function createTable(){
return $this->pdo->exec(
"CREATE TABLE `valutazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `valutazione` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_valutazione_post_idx` (`id_post`),
  KEY `fk_valutazione_utente_idx` (`id_utente`),
  CONSTRAINT `fk_valutazione_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_valutazione_utente` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
* @return integer
**/
public function getIdPost(){
	 return $this->id_post;
}

/** 
* @param integer $id_post
**/
public function setIdPost($id_post){
	 $this->id_post = $id_post;
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
* @return integer
**/
public function getValutazione(){
	 return $this->valutazione;
}

/** 
* @param integer $valutazione
**/
public function setValutazione($valutazione){
	 $this->valutazione = $valutazione;
}


} //close Class ValutazioneModel