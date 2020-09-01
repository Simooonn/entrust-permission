<?php

namespace HashyooEntrust\LaravelModel;

use HashyooFast\LaravelModel;

class BaseModel extends LaravelModel
{


    /**
     * 删除数据
     *
     * @param int  $n_id
     * @param bool $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_delete($n_id = 0, $bool = true)
    {
        $option = ['where' => ['id' => $n_id]];
        $result = self::lara_del_true($option);
        return $result;
    }

    /**
     * 递归删除数据
     *
     * @param int   $n_id
     * @param array $option
     * @param bool  $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_del_recur($n_id = 0, $option = [], $bool = true)
    {
        //获取所有子级ID（包括当前数据id）
        if (!isset($option['pk'])) {
            $s_pk = 'id';
        }
        if (!isset($option['pid_key'])) {
            $s_pid_key = 'pid';
        }
        $option['field'] = [$s_pk, $s_pid_key];
        $result          = self::lara_all($option)
                               ->toArray();
        yoo_tree_child_ids($result, $arr_ids, $n_id, $s_pk, $s_pid_key);
        $arr_ids[] = $n_id;
        $option    = ['whereIn' => ['id' => $arr_ids]];
        $result    = self::lara_del_true($option);
        return $result;
    }


}
