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
    public function all_list($arr_field = ['*']){
        $result = EntrustPermissions::select($arr_field)->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
        return $result;
    }

    /**
     * 所有权限（只含未被禁用的）
     *
     * @param array $arr_field
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function normal_list($arr_field = ['*']){
        $arr_where = ['status'=>1];
        $result = EntrustPermissions::where($arr_where)->select($arr_field)->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
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
    public function find($n_id = 0,$arr_field = ['*']){
        if($n_id <= 0){
            return yoo_hello_fail('数据id不能为空');
        }

        $result = EntrustPermissions::select($arr_field)->find($n_id);
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
        $result = EntrustPermissions::form_check($arr_input);
        if($result['state'] != 200){
            return $result;
        }

        $result = EntrustPermissions::form_add($arr_input);
        if($result['state'] != 200){
            return $result;
        }

        //路由名称和路由别名不能重复
        $arr_where = yoo_array_remain($arr_input,['route','alias']);
        $result = EntrustPermissions::orwhere($arr_where)->count();
        if($result > 0){
            return yoo_hello_fail('路由名称或路由别名重复');
        }
        
        //添加路由
        $result = EntrustPermissions::create($arr_input)->toarray();
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
        $result = EntrustPermissions::form_check($arr_input);
        if($result['state'] != 200){
            return $result;
        }
        
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
            $result = EntrustPermissions::orwhere($arr_where)->where('id','!=',$n_id)->count();
            if($result > 0){
                return yoo_hello_fail('路由名称或路由别名重复');
            }
        }

        //修改路由
        $result = EntrustPermissions::where(['id'=>$n_id])->update($arr_input);
        if(!$result){
            return yoo_hello_fail('修改失败');
        }
        else{
            return yoo_hello_success('修改成功');
        }
      
    }

    /**
     * 删除权限
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
        $arr_where = ['id'=>$n_id];
        $result = EntrustPermissions::where($arr_where)->forceDelete();
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
        $arr_where = ['id'=>$n_id];
        $arr_data = ['status'=>1];
        $result = EntrustPermissions::where($arr_where)->update($arr_data);
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
        $arr_where = ['id'=>$n_id];
        $arr_data = ['status'=>2];
        $result = EntrustPermissions::where($arr_where)->update($arr_data);
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
        $arr_where = ['id'=>$n_id];
        $arr_data = ['is_menu'=>1];
        $result = EntrustPermissions::where($arr_where)->update($arr_data);
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
    public function node($n_id = 0){
        $arr_where = ['id'=>$n_id];
        $arr_data = ['is_menu'=>2];
        $result = EntrustPermissions::where($arr_where)->update($arr_data);
        return $result;
    }
  
}