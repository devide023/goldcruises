<?php


namespace App\Code;


trait MyArrayTool
{
    public function array_columns($input, $column_keys = null, $index_key = null)
    {
        $result = array();
        $keys = isset($column_keys) ? explode(',', $column_keys) : array();
        if ($input) {
            foreach ($input as $k => $v) {
                // 指定返回列
                if ($keys) {
                    $tmp = array();
                    foreach ($keys as $key) {
                        $tmp[$key] = $v->$key;
                    }
                } else {
                    $tmp = $v;
                }
                // 指定索引列
                if (isset($index_key)) {
                    $result[$v->$index_key] = $tmp;
                } else {
                    $result[] = $tmp;
                }
            }
        }
        return $result;
    }
}
