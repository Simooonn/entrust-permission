<?php
/**
 * Created by PhpStorm.
 * User: wumengmeng <wu_mengmeng@foxmail.com>
 * Date: 2020/6/30 0030
 * Time: 18:00
 */

namespace HashyooEntrust;

use HashyooEntrust\LaravelService\EntrustPermissionsService;
use HashyooEntrust\LaravelService\EntrustRolePermissionService;
use HashyooEntrust\LaravelService\EntrustRolesService;
use HashyooEntrust\LaravelService\EntrustUserRoleService;

class HashyooEntrust
{

    /**
     * 所有权限（包含已禁用的）
     *
     * @param array $option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_list($option = [])
    {
        $result = EntrustPermissionsService::all_get($option);
        return $result;
    }

    /**
     * 所有权限（只含未被禁用的）
     *
     * @param array $option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_normal_list($option = [])
    {
        $result = EntrustPermissionsService::normal_get($option);
        return $result;
    }

    /**
     * 用户菜单
     *
     * @param int   $n_userid
     * @param array $option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function user_menus($n_userid = 0, $option = [])
    {
        $result = EntrustPermissionsService::user_menus($n_userid, $option);
        return $result;
    }

    /**
     * 所有菜单
     *
     * @param array $option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function all_menus($option = [])
    {
        $result = EntrustPermissionsService::all_menus($option);
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
    public function permission_find_one($n_id = 0, $arr_field = ['*'])
    {
        $result = EntrustPermissionsService::find($n_id, $arr_field);
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
    public function permission_add($arr_input = [])
    {
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
    public function permission_edit($arr_input = [])
    {
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
    public function permission_del($n_id = 0)
    {
        $result = EntrustPermissionsService::del($n_id);
        return $result;
    }

    /**
     * 删除权限-递归删除
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_del_recur($n_id = 0)
    {
        $result = EntrustPermissionsService::del_recur($n_id);
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
    public function permission_open($n_id = 0)
    {
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
    public function permission_close($n_id = 0)
    {
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
    public function permission_menu($n_id = 0)
    {
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
    public function permission_node($n_id = 0)
    {
        $result = EntrustPermissionsService::node($n_id);
        return $result;
    }

    /**
     * 所有角色（包含已禁用的）
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_list($option = [])
    {
        $result = EntrustRolesService::all_get($option);
        return $result;
    }

    /**
     * 所有角色（只含未被禁用的）
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_normal_list($option = [])
    {
        $result = EntrustRolesService::normal_get($option);
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
    public function role_find_one($n_id = 0, $arr_field = ['*'])
    {
        $result = EntrustRolesService::find($n_id, $arr_field);
        return $result;
    }

    /**
     * 添加角色
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_add($arr_input = [])
    {
        $result = EntrustRolesService::add($arr_input);
        return $result;
    }

    /**
     * 修改角色
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_edit($arr_input = [])
    {
        $result = EntrustRolesService::edit($arr_input);
        return $result;
    }

    /**
     * 删除角色
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_del($n_id = 0)
    {
        $result = EntrustRolesService::del($n_id);
        return $result;
    }

    /**
     * 删除角色-递归删除
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_del_recur($n_id = 0)
    {
        $result = EntrustRolesService::del_recur($n_id);
        return $result;
    }

    /**
     * 开启角色
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_open($n_id = 0)
    {
        $result = EntrustRolesService::open($n_id);
        return $result;
    }

    /**
     * 关闭角色
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_close($n_id = 0)
    {
        $result = EntrustRolesService::close($n_id);
        return $result;
    }

    /*角色权限集合*/

    /**
     * 角色权限集合 列表集合
     *
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_permissions($arr_ids = [])
    {
        $result = EntrustRolePermissionService::permissions($arr_ids);
        return $result;
    }

    /**
     * 角色权限集合 ids集合
     *
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_permission_ids($arr_ids = [])
    {
        $result = EntrustRolePermissionService::permission_ids($arr_ids);
        return $result;
    }

    /**
     * 角色-权限设置
     *
     * @param int   $n_id
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_permission_set($n_id = 0, $arr_ids = [])
    {
        $result = EntrustRolePermissionService::insert($n_id, $arr_ids);
        return $result;
    }


    /**
     * 用户-角色设置
     *
     * @param int   $n_id
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function user_role_set($n_id = 0, $arr_ids = [])
    {
        $result = EntrustUserRoleService::insert($n_id, $arr_ids);
        return $result;
    }


}