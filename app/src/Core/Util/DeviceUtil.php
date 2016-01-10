<?php

namespace Core\Util;

/**
 * Class DeviceUtil
 * @package Core\Util
 */
class DeviceUtil
{
    /**
     * @return bool
     */
    public function isSp()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];

        if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
            return true;
        }

        return false;
    }

}