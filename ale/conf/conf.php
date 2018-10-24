<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 18/04/18
 * Time: 11.47
 */

if (!defined('SERVER')) {

    define("PROJECT_NAME", "sviluppiAle/ale");

    /* LOCALE */
    define('SERVER', 'mysqllocal');
    define('SERVERS', 'mysqllocal,mysqllocal');
    define('DBUSER', 'root');
    define('DBPASSWORD', 'alessandro');
    define('DBNAME', 'ale_test');
    define('DBCHARSET', 'latin1');
    define('LOCALE', true);

    define('COLOR', 'Orange');

    define("PATH_IMG_PROFILO", "/var/www/html/sviluppiAle/ale/grafica/img/fotoprofilo/");
    define("PATH_ICONE", "http://localhost/sviluppiAle/ale/grafica/img/icone/");
    define("PATH_IMG_PROFILO_SERVER", "http://localhost/sviluppiAle/ale/grafica/img/fotoprofilo/");
    define("PATH_IMG_PROFILO_DEFAULT_SERVER", "http://localhost/sviluppiAle/ale/grafica/img/fotoprofilo/utenteDefault.png");

}