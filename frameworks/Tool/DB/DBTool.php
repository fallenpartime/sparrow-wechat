<?php
/**
 * db工具
 * Date: 2018/10/3
 * Time: 23:31
 */
namespace Frameworks\Tool\DB;

use Illuminate\Support\Facades\DB;

class DBTool
{
    public static function getTableProcessor($table, $where)
    {
        $processor = DB::table($table);
        if (!empty($where)) {
            foreach ($where as $key => $item) {
                $processor->where($key, $item);
            }
        }
        return $processor;
    }

    public static function getList($table, $columns, $where, $limit)
    {
        $processor = self::getTableProcessor($table, $where);
        if (!empty($columns)) {
            $processor = $processor->select($columns);
        }
        if (is_array($limit)) {
            $countLimit = count($limit);
            if ($countLimit == 1) {
                $processor = $processor->limit($limit[0]);
            } else if($countLimit == 2) {
                $processor = $processor->offset($limit[0])->limit($limit[1]);
            }
        } else if(!empty($limit)) {
            $processor = $processor->limit($limit);
        }
        return $processor->get();
    }

    public static function getSingle($table, $columns, $where)
    {
        $processor = self::getTableProcessor($table, $where);
        if (!empty($columns)) {
            $processor = $processor->select($columns);
        }
        return $processor->first();
    }

    public static function count($table, $where)
    {
        return self::getTableProcessor($table, $where)->count();
    }
}