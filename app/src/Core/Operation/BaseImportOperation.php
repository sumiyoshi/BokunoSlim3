<?php

namespace Core\Operation\Import;

use Core\Operation\AbstractOperation;
use Core\Service\FileObject;

abstract class BaseImportOperation extends AbstractOperation
{
    const FILE_KEY = 'FILE';

    /**
     * @param $file
     * @return array
     */
    protected function getCsvList($map, $file)
    {
        $list = array();
        foreach ($map as $key => $name) {
            if (isset($file[$key])) {
                $list[$name] = $file[$key];
            }
        }

        return $list;
    }


    /**
     * @return \SplFileObject
     */
    protected function getFileObject()
    {
        $file = $this->param(static::FILE_KEY);
        $fileObject = new FileObject();
        $tmp_file = $fileObject->putFile($file['tmp_name']);
        return $fileObject->getFileObject($tmp_file);
    }
}