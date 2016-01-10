<?php

namespace Generator\Lib;

use Twig_Environment;
use Twig_Loader_String;
use Zend\Config\Reader\Xml;

trait Builder
{
    /**
     * @param $path
     * @param null $table_name
     * @return array
     */
    protected function getFileData($path, $table_name = null)
    {
        $directory = dir($path);

        $fileList = null;
        while (($entry = $directory->read()) !== false) {
            if (strpos($entry, ".xml") !== false) {

                if ($table_name) {
                    if ($table_name . ".xml" == $entry) {
                        $fileList[] = $entry;
                    }
                } else {
                    $fileList[] = $entry;
                }
            }
        }

        $list = array();
        foreach ($fileList as $name) {
            $reader = new Xml();
            $list[] = $reader->fromFile($path . '/' . $name);
        }

        return $list;
    }

    /**
     * @return Twig_Environment
     */
    protected function getView()
    {
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);

        return $twig;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getTemplate($name)
    {
        return file_get_contents(GENERATOR_ROOT . "/src/Generator/view/{$name}");
    }

    /**
     * @param $path
     */
    protected function mkDir($path)
    {
        if ($path == '/') {
            return;
        }

        if (!file_exists($path)) {
            @mkdir($path);
        }
    }

    /**
     * @param $file
     * @param $code
     * @param bool|false $override
     */
    protected function output($file, $code, $override = false)
    {

        if (file_exists($file) && $override === false) {
            return;
        }

        $fp = fopen($file, 'w+');
        fwrite($fp, $code);
        fclose($fp);
    }

    /**
     * @param $list
     * @return array
     */
    protected function getPrimary($list)
    {
        $primary_key = array();

        foreach ($list as $row) {
            if ($row['primary_key']) {
                $primary_key[] = $row['name'];
            }
        }

        return $primary_key;
    }

    /**
     * @param $str
     * @return string
     */
    public function snakeToCamel($str)
    {
        $tokens = explode('_', $str);
        foreach ($tokens as $k => $v) {
            $tokens[$k] = ucfirst($v);
        }
        return implode($tokens);
    }
}