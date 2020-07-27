<?php

namespace HashyooEntrust\LaravelModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{

    use SoftDeletes;
    
    private function child_model(){
        $child_model_class = get_called_class();
        return new $child_model_class;
    }

    /**
     * 获取表字段
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    protected function table_field_keys()
    {
        $prefix =  \DB::getConfig('prefix');
        $table = self::child_model()->table;
        $s_full_table = $prefix . $table;
        $columns      = \DB::getDoctrineSchemaManager()
                           ->listTableColumns($s_full_table);
        $arr_data     = [];
        foreach ($columns as $column) {
            $arr_data[] = $column->getName();
        }
        return $arr_data;
    }
}
