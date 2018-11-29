<?php
/**
* Developed by: Alessandro Pericolo
* Date: 29/11/2018
* Time: 18:00
* Version: 0.1
**/

require_once 'ElementsModel.php';

class Elements extends ElementsModel {

/*CONSTRUCTOR*/
function __construct(PDO $pdo){
	parent::__construct($pdo);
}

} //close Class Elements