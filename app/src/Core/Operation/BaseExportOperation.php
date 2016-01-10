<?php

namespace Core\Operation\Export;

use Core\Operation\AbstractOperation;
use Core\Util\ArrayUtil;

/**
 * Class BaseExportOperation
 * @package Component\Operation\Export
 */
abstract class BaseExportOperation extends AbstractOperation
{
    protected $map = array();
    protected $file_name = null;

    protected function output(array $list)
    {
        $csv = null;

        #region header部分
        foreach ($this->map as $value) {
            $csv .= '"' . $value . '",';
        }
        $csv .= "\n";
        #endregion

        #region body部分
        foreach ($list as $key => $items) {
            foreach (array_keys($this->map) as $column) {
                if (ArrayUtil::hasKey($column, $items)) {
                    $value = $items[$column];
                    $csv .= '"' . $this->escape($value) . '",';
                } else {
                    $csv .= '"",';
                }
            }
            $csv .= "\n";
        }
        #endregion

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $this->file_name . '.csv');
        echo mb_convert_encoding($csv, "SJIS", "UTF-8");
    }

    protected function escape($line)
    {
        $line = str_replace(',', '', $line);
        $line = str_replace('"', '', $line);

        return $line;
    }
}