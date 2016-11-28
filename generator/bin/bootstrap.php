<?php

define('SERVER_ROOT', dirname(dirname(dirname(__FILE__))));
define('APP_ROOT', SERVER_ROOT.'/app');
define('GENERATOR_ROOT', SERVER_ROOT.'/generator');
define('CONIFG_ROOT', APP_ROOT . '/config/');

# autoload
require SERVER_ROOT . '/vendor/autoload.php';

/** @var \Core\Service\Config $config */
$config = \Core\Service\Config::getInstance();

#region DB
if ($database = $config->get('database')) {

    $host = $database['host'];
    $db = $database['db'];
    $username = $database['username'];
    $password = $database['password'];

    \ORM::configure("mysql:host={$host};dbname={$db};charset=utf8");
    \ORM::configure('username', $username);
    if ($password) {
        \ORM::configure('password', $password);
    }
    \ORM::configure('caching', true);
    \ORM::configure('caching_auto_clear', true);
    \ORM::configure('default_charset', "utf-8");
}

\Model::$auto_prefix_models = '\\Component\\Data\\Model\\';
#endregion
