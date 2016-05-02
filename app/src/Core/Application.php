<?php

namespace Core;

/**
 * Class Application
 *
 * @package Core
 * @author sumiyoshi
 */
class Application
{

    use \Core\Traits\Singleton;

    /**
     * @var array
     */
    private $config = [];

    private function __construct()
    {
        $this->config = include APP_ROOT . '/config/global.php';

        if (file_exists(APP_ROOT . '/config/local.php')) {
            $local = include APP_ROOT . '/config/local.php';

            foreach ($local as $key => $row) {
                if (isset($this->config[$key])) {
                    $this->config[$key] = $row;
                }
            }
        }
    }

    /**
     * @param $name
     * @return array
     */
    public function get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return [];
        }

    }
}