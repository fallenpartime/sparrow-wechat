<?php
/**
 * sql工具
 * Date: 2018/10/3
 * Time: 23:17
 */
namespace Frameworks\Tool\DB;

class SqlTool
{
    public static function combineField($fields = '*')
    {
        $fieldString = $fields;
        if (is_array($fields)) {
            if (!empty($fields)) {
                $fieldString = implode(',', $fields);
            } else {
                $fieldString = '*';
            }
        } elseif (empty($fields)) {
            $fieldString = '*';
        }
        return $fieldString;
    }

    public static function combineWhere($where = '0=1')
    {
        $whereString = $where;
        if (is_array($where)) {
            $whereString = implode(' and ', $where);
        }
        return $whereString;
    }
}