<?php

ini_set('display_errors', 1);

define('SERVER_ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_ROOT', SERVER_ROOT . '/app');

require SERVER_ROOT . '/vendor/autoload.php';

# define
require APP_ROOT . '/config/define.php';
# session
require APP_ROOT . '/config/session.php';
# database
require APP_ROOT . '/config/database.php';

$app = \Core\CoreSlim::init();

# dependencies
include APP_ROOT . '/config/dependencies.php';

$app->run();

