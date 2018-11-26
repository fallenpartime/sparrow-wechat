<?php
/**
 * 地图配置
 * Date: 2018/11/2
 * Time: 10:23
 */
namespace Frameworks\Tool\Map;

class MapConfig
{
    const MAP_AK = 'BzhKkTniDZS9bPzFpLGTAG2UDSTocLHm';
    const POSITION_URL = "http://api.map.baidu.com/geocoder/v2/?address=#address&output=json&ak=#ak";

    public static function getPositionUrl($address)
    {
        $url = self::POSITION_URL;
        $url = str_replace('#address', $address, $url);
        $url = str_replace('#ak', self::MAP_AK, $url);
        return $url;
    }
}
