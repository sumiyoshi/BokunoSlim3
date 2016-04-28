<?php

ini_set("display_errors", 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define('SERVER_ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_ROOT', SERVER_ROOT . '/app');

require SERVER_ROOT . '/vendor/autoload.php';

# define
require APP_ROOT . '/config/define.php';

# run
include APP_ROOT . '/bootstrap.php';