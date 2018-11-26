<?php
/**
 * 地图工具
 * Date: 2018/11/2
 * Time: 10:29
 */
namespace Frameworks\Tool\Map;

use Frameworks\Tool\Http\CurlTool;
use Illuminate\Http\Request;

class MapTool
{
    protected $request = null;

    /**
     * MapTool constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 获取地址坐标
     * @param $address
     * @return array
     */
    public function getAddressPosition($address)
    {
        $emptyPosition = [0, 0];
        if (empty($address)) {
            return $emptyPosition;
        }
        $url = MapConfig::getPositionUrl($address);
        $result = (new CurlTool($this->request))->curl($url);
        if (empty($result)) {
            return $emptyPosition;
        }
        $data = json_decode($result, true);
        if (!is_array($data)) {
            return $emptyPosition;
        }
        if ($data['status'] == 0 && isset($data['result']['location'])) {
            return [array_get($data, 'result.location.lng'), array_get($data, 'result.location.lat')];
        }
        return $emptyPosition;
    }
}
