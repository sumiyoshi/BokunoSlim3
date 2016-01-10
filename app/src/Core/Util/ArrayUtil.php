<?php

namespace Core\Util;

/**
 * Class ArrayUtil
 * @package Core\Util
 */
class ArrayUtil
{
    /**
     * @param $key
     * @param array $data
     * @return bool
     */
    public static function hasKey($key, $data)
    {
        if (!is_array($data)) {
            return false;
        }

        return (isset($data[$key]) && !empty($data[$key]));
    }

    /**
     * @param array $data
     * @param $entity
     * @return array
     */
    public static function getEntityData(array $data, $entity, $unsetPrimaryKey = true)
    {

        $res = array();

        #region エンティティに登録されている値をセット
        foreach ($entity->getEntities() as $key => $val) {
            if (array_key_exists($key, $data)) {
                $res[$key] = $data[$key];
            }
        }
        #endregion

        #region プライマリーキーはアンセット
        if ($unsetPrimaryKey) {
            if (isset($res[$entity::$_id_column])) {
                unset($res[$entity::$_id_column]);
            }
        }
        #endregion

        return $res;

    }

    /**
     * @param $value
     * @return array|null
     */
    public static function getObjectVars($value)
    {
        if ($value == null) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        return get_object_vars($value);
    }

    /**
     * @param $arr
     * @return array
     */
    public static function deleteEmpty($arr)
    {
        $arr = array_filter($arr, "strlen");
        return $arr;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function shiftArray(array $data)
    {
        $res = array();
        foreach ($data as $key => $items) {
            $res[$key] = array_shift($items)[0];
        }

        return $res;
    }

    /**
     * @param array $origin_array
     * @param array $add_array
     * @return array
     */
    public static function arrayReplace(array $origin_array, array $add_array)
    {
        $array = array();

        foreach ($origin_array as $key => $val) {

            if (isset($add_array[$key])) {
                $array[$key] = $add_array[$key];
            } else {
                $array[$key] = $origin_array[$key];
            }
        }

        return $array;
    }

    /**
     * @param array $array
     * @param $target
     * @param $val
     * @return array
     */
    public static function select(array $array, $target, $val)
    {
        foreach ($array as $key => $row) {
            if (isset($row[$target]) && $row[$target] == $val) {
                return $array[$key];
            }
        }

        return array();
    }


    /**
     * @param $obj
     * @return array
     */
    public static function toArray($obj)
    {
        $data = array();
        foreach ($obj as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }

}