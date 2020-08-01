<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */
namespace HashyooEntrust\LaravelService;

use HashyooEntrust\LaravelModel\EntrustPermissions;

class EntrustPermissionsService extends BaseService
{

    /**
     * 所有权限（包含已禁用的）
     *
     * @param array $arr_field
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function all_get($arr_option = []){
        $arr_option['order'] = [
          'sort'=>'asc',
          'id'=>'asc',
        ];
        $result = EntrustPermissions::en_get($arr_option)->toArray();
        return yoo_hello_success('查询成功',$result);
    }

    /**
     * 所有权限（只含未被禁用的）
     *
     * @param array $arr_field
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function normal_get($arr_option = []){
        $arr_option['where'][] = ['status'=>1];
        $arr_option['order'] = [
          'sort'=>'asc',
          'id'=>'asc',
        ];
        $result = EntrustPermissions::en_get($arr_option)->toArray();
        return yoo_hello_success('查询成功',$result);
    }
    
    public function all_menus($arr_option = []){
        if(!isset($arr_option['field'])){
            $arr_option['field'] = ['id', 'pid', 'name', 'route', 'alias', 'ico'];
        }
        if(!isset($arr_option['order'])){
            $arr_option['order'] = ['sort'=>'asc','id'=>'asc'];
        }
        $arr_option['where']['is_menu'] = 1;
        $result = EntrustPermissions::en_get($arr_option)->toArray();
        return yoo_hello_success('查询成功',$result);
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
    public function find($n_id = 0,$arr_field = ['*']){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }

        $arr_option['field'] = $arr_field;
        $result = EntrustPermissions::en_find($n_id,$arr_option);
        if(is_null($result)){
            return yoo_hello_fail('数据不存在');
        }
        
        $result = $result->toArray();
        return yoo_hello_success('查询成功',$result);


    }

    /**
     * 添加权限
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function add($arr_input = []){
        $result = EntrustPermissions::form_add($arr_input);
        if($result['state'] != 200){
            return $result;
        }

        //路由名称和路由别名不能重复
        $arr_option = ['where'=>yoo_array_remain($arr_input,['route','alias'])];
        $result = EntrustPermissions::en_count($arr_option);
        if($result > 0){
            return yoo_hello_fail('路由名称或路由别名重复');
        }
        
        //添加路由
        $result = EntrustPermissions::en_add($arr_input)->toarray();
        if(empty($result)){
            return yoo_hello_fail('添加失败');
        }
        else{
            return yoo_hello_success('添加成功');
        }

    }

    /**
     * 修改权限
     *
     * @param array $arr_input
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function edit($arr_input = []){
        $n_id = intval($arr_input['id']);
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }

        //路由名称和路由别名不能重复
        if(isset($arr_input['route'])){
            $arr_where['route'] = $arr_input['route'];
        }
        if(isset($arr_input['alias'])){
            $arr_where['alias'] = $arr_input['alias'];
        }
        if(count($arr_where) > 0){
            $arr_where[] = ['id','!=',$n_id];
            $arr_option = [
              'where'=>$arr_where
            ];
            $result = EntrustPermissions::en_count($arr_option);
            if($result > 0){
                return yoo_hello_fail('路由名称或路由别名重复');
            }
        }

        //修改路由
        $result = EntrustPermissions::en_edit($arr_input);
        if(!$result){
            return yoo_hello_fail('修改失败');
        }
        else{
            return yoo_hello_success('修改成功');
        }
      
    }

    /**
     * 删除权限-若有子级不能删除
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }

        //查询是否有子级 若有子级不能删除
        $arr_option = ['where'=>['pid'=>$n_id]];
        $result = EntrustPermissions::en_count($arr_option);
        if($result > 0){
            return yoo_hello_fail('当前数据有子数据，不能删除');
        }

        $result = EntrustPermissions::en_delete($n_id);
        if(!$result){
            return yoo_hello_fail('删除失败');
        }
        return  yoo_hello_success('删除成功');
    }

    /**
     * 删除权限-若有子级递归删除
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del_recur($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }

        //递归删除
        $result = EntrustPermissions::en_del_recur($n_id);
        if(!$result){
            return yoo_hello_fail('删除失败');
        }
        return  yoo_hello_success('删除成功');
    }

    /**
     * 开启权限
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function open($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }
        $arr_input = ['id'=>$n_id,'status'=>1];
        $result = EntrustPermissions::en_edit($arr_input);
        if(!$result){
            return yoo_hello_fail('开启失败');
        }
        return  yoo_hello_success('开启成功');
    }

    /**
     * 关闭权限
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function close($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }
        $arr_input = ['id'=>$n_id,'status'=>2];
        $result = EntrustPermissions::en_edit($arr_input);
        if(!$result){
            return yoo_hello_fail('关闭失败');
        }
        return  yoo_hello_success('关闭成功');
    }

    /**
     * 设置菜单
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function menu($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }
        $arr_input = ['id'=>$n_id,'is_menu'=>1];
        $result = EntrustPermissions::en_edit($arr_input);
        if(!$result){
            return yoo_hello_fail('设置菜单失败');
        }
        return  yoo_hello_success('设置菜单成功');
    }

    /**
     * 设置节点
     *
     * @param int $n_id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function node($n_id = 0){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }
        $arr_input = ['id'=>$n_id,'is_menu'=>2];
        $result = EntrustPermissions::en_edit($arr_input);
        if(!$result){
            return yoo_hello_fail('设置节点失败');
        }
        return  yoo_hello_success('设置节点成功');
    }
  
}