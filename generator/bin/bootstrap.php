<?php

define('SERVER_ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_ROOT', SERVER_ROOT.'/app');
define('GENERATOR_ROOT', SERVER_ROOT.'/generator');
define('CONIFG_ROOT', APP_ROOT . '/config/');

# autoload
require SERVER_ROOT . '/vendor/autoload.php';

# database
require APP_ROOT . '/config/database.php';
