<?php

require_once 'conf/conf.php';

$pageurl = "http://" . $_SERVER['HTTP_HOST'] .'/'.ROOT_FOLDER . '/anagrafica/login.php';
header("location: $pageurl");