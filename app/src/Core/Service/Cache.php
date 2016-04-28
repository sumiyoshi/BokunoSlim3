<?php

namespace Core\Service;

/**
 * Class Cache
 *
 * @package Core\Service
 * @author sumiyoshi
 */
class Cache
{

    private $saveDir;

    private $baseDir;

    private $lifeTime;

    function __construct($lifeTime, $path)
    {
        $this->lifeTime = ($lifeTime) ? (int)$lifeTime : 60 * 60;

        $this->baseDir = $path;
        if (is_dir($this->baseDir) === false) {
            mkdir($this->baseDir, 0744, true);
        }

        $this->saveDir = $this->baseDir;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setDir($path)
    {
        $this->saveDir = $this->baseDir . $path;

        return $this;
    }

    /**
     * @param $data
     * @param $key
     *
     * @return int
     */
    public function save($data, $key)
    {
        $saveFile = $this->saveDir . "/" . $key;
        $serialData = serialize($data);

        return file_put_contents($saveFile, $serialData);
    }

    /**
     * @param $key
     *
     * @param bool $remove
     * @return bool|mixed
     */
    public function load($key, $remove = true)
    {
        $saveFile = $this->saveDir . "/" . $key;

        if (is_file($saveFile) === false) {
            return false;
        }

        if ($remove && (filemtime($saveFile) + $this->lifeTime < time())) {
            $this->remove($key);
            return false;
        }

        $serialData = file_get_contents($saveFile);
        return unserialize($serialData);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function remove($key)
    {
        return unlink($this->saveDir . "/" . $key);
    }
}