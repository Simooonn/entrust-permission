<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */

namespace HashyooEntrust\LaravelService;

use HashyooEntrust\LaravelModel\EntrustPermissions;
use HashyooEntrust\LaravelModel\EntrustRolePermission;

class EntrustRolePermissionService extends BaseService
{

    /**
     * 权限设置
     *
     * @param int   $n_id
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function insert($n_id = 0, $arr_ids = [])
    {
        if ($n_id <= 0) {
            return yoo_hello_fail('数据id不能为空');
        }
        //        if(count($arr_ids) <= 0){
        //            return yoo_hello_fail('请选择权限');
        //        }

        //删除之前的数据
        $option = ['where' => ['role_id' => $n_id]];
        $result = EntrustRolePermission::lara_del_true($option);
        if (!($result >= 0)) {
            return yoo_hello_fail('操作失败', '删除失败');
        }

        //重新添加新的数据
        $arr_data = [];
        foreach ($arr_ids as $value) {
            $arr_data[] = ['role_id' => $n_id, 'permission_id' => $value];
        }
        $result = EntrustRolePermission::lara_insert($arr_data);
        if (!$result) {
            return yoo_hello_fail('设置失败');
        }
        return yoo_hello_success('设置成功');
    }

    /**
     * 角色权限集合 ids集合
     *
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permission_ids($arr_ids = [])
    {
        if (count($arr_ids) <= 0) {
            return yoo_hello_fail('数据id不能为空');
        }

        $option = ['whereIn' => ['role_id' => $arr_ids], 'orderBy' => []];
        $result = EntrustRolePermission::lara_all($option)
                                       ->toarray();
        $result = array_column($result, 'permission_id');
        return yoo_hello_success('成功', $result);
    }

    /**
     * 角色权限集合 列表集合
     *
     * @param $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function permissions($arr_ids)
    {
        if (count($arr_ids) <= 0) {
            return yoo_hello_fail('数据id不能为空');
        }

        $option = [
          'whereHas' => [
            'role_permission' => [
              'whereIn' => ['role_id' => $arr_ids],
            ],
          ],
        ];
        $result = EntrustPermissions::lara_all($option)
                                    ->toarray();
        return yoo_hello_success('成功', $result);

    }


}