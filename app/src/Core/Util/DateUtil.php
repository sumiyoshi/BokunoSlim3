<?php

namespace Core\Util;

/**
 * Class DateUtil
 * @package Core\Util
 */
class DateUtil
{
    /**
     * @param string $format
     * @return bool|string
     */
    public static function getDate($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    public static function getWeek($date)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $max_datetime = new \DateTime($date);
        $max_week = (int)$max_datetime->format('w');
        return $week[$max_week];
    }
}