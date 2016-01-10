<?php

namespace Core\Traits;

/**
 * Class Singleton
 * @package Core\Traits
 */
trait Singleton
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}