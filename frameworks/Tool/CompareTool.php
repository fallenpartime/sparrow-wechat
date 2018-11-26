<?php
/**
 * 对比工具
 * Date: 2018/7/26
 * Time: 15:20
 */
namespace Frameworks\Tool;

class CompareTool
{
    const METHOD_IN_ARRAY = 'inArray';
    const COMPARE_OR_METHOD = 0;
    const COMPARE_AND_METHOD = 1;

    /**
     * in_array
     * @param $value
     * @param $compares
     * @return bool
     */
    public static function inArray($value, $compares)
    {
        return in_array($value, $compares);
    }

    /**
     * 数组内容对比
     * @param $method 0-or 1-and
     * @param $callback
     * @param $values
     * @param $compares
     * @return bool
     */
    public static function compareValues($method, $callback, $values, $compares)
    {
        return self::compareValuesWithClass($method, __CLASS__, $callback, $values, $compares);
    }

    /**
     * 数组内容对比
     * @param $method 0-or 1-and
     * @param $className
     * @param $callback
     * @param $values
     * @param $compares
     * @return bool
     */
    public static function compareValuesWithClass($method, $className, $callback, $values, $compares)
    {
        $outValidate = false;
        $compareList = [];
        foreach ($values as $item) {
            $itemRes = call_user_func_array([$className, $callback], [$item, $compares]);
            if ($method == self::COMPARE_OR_METHOD) {
                $compareList[] = $itemRes;
            } elseif ($itemRes == false) {
                return false;
            } else {
                $outValidate = true;
            }
        }
        if ($method == self::COMPARE_OR_METHOD) {
            foreach ($compareList as $compareItem) {
                $outValidate = $compareItem || $outValidate;
            }
        }
        return $outValidate;
    }
}