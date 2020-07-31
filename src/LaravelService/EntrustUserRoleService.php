<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */
namespace HashyooEntrust\LaravelService;

use HashyooEntrust\LaravelModel\EntrustUserRole;

class EntrustUserRoleService extends BaseService
{

    /**
     * 用户角色设置
     *
     * @param int   $n_id
     * @param array $arr_ids
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function insert($n_id = 0,$arr_ids = []){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }
//        if(count($arr_ids) <= 0){
//            return yoo_hello_fail('请选择权限');
//        }

        //删除之前的数据
        $arr_option = ['where'=>['user_id'=>$n_id]];
        $result = EntrustUserRole::en_del($arr_option);
        if(!($result >= 0)){
            return yoo_hello_fail('操作失败','删除失败');
        }

        //重新添加新的数据
        $arr_data = [];
        foreach ($arr_ids as $value)
        {
            $arr_data[] = ['user_id'=>$n_id,'role_id'=>$value];
        }
        $result = EntrustUserRole::insert($arr_data);
        if( !$result ){
            return yoo_hello_fail('设置失败');
        }
        return  yoo_hello_success('设置成功');
    }




}