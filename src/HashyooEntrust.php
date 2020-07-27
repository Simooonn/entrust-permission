<?php
/**
 * Created by PhpStorm.
 * User: wumengmeng <wu_mengmeng@foxmail.com>
 * Date: 2020/6/30 0030
 * Time: 18:00
 */

namespace HashyooEntrust;

use HashyooEntrust\LaravelModel\EntrustPermissions;
use HashyooEntrust\LaravelService\EntrustPermissionsService;

class HashyooEntrust
{

    /**
     * 所有权限（包含已禁用的）
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_all_list($arr_field = ['*']){
        $result = EntrustPermissionsService::all_list($arr_field);
        return $result;
    }

    /**
     * 所有权限（只含未被禁用的）
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_normal_list($arr_field = ['*']){
        $result = EntrustPermissionsService::normal_list($arr_field);
        return $result;
    }

    /**
     * 查询一条权限
     *
     * @param int   $n_id
     * @param array $arr_field
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_find_one($n_id = 0,$arr_field = ['*']){
        $result = EntrustPermissionsService::find($n_id,$arr_field);
        return $result;
    }

    /**
     * 添加权限
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_add($arr_input = []){
        $result = EntrustPermissionsService::add($arr_input);
        return $result;
    }

    /**
     * 修改权限
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_edit($arr_input = []){
        $result = EntrustPermissionsService::edit($arr_input);
        return $result;
    }

    /**
     * 删除权限
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_del($n_id = 0){
        $result = EntrustPermissionsService::del($n_id);
        return $result;
    }

    /**
     * 开启权限
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_open($n_id = 0){
        $result = EntrustPermissionsService::open($n_id);
        return $result;
    }

    /**
     * 关闭权限
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_close($n_id = 0){
        $result = EntrustPermissionsService::close($n_id);
        return $result;
    }

    /**
     * 设置菜单
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_menu($n_id = 0){
        $result = EntrustPermissionsService::menu($n_id);
        return $result;
    }

    /**
     * 设置节点
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_node($n_id = 0){
        $result = EntrustPermissionsService::node($n_id);
        return $result;
    }

}