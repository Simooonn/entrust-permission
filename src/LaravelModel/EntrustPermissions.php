<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */

namespace HashyooEntrust\LaravelModel;

class EntrustPermissions extends BaseModel
{
    
    protected $table = 'entrust_permissions';
    
    protected $guarded = [];
    
    //public $timestamps = false;
    
    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
    ];


    /**
     * 关联角色权限表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function role_permission(){
        return $this->hasOne(EntrustRolePermission::class,'permission_id');
    }

    public function form_add($arr_input = []){
        
        //路由名称
        $n_length = mb_strlen($arr_input['route']);
        if($n_length <= 0){
            return yoo_hello_fail('路由名称不能为空');
        }
//        if($n_length > 80){
//            return yoo_hello_fail('路由名称不能超过80字符');
//        }

        //路由别名
        $n_length = mb_strlen($arr_input['alias']);
        if($n_length <= 0){
            return yoo_hello_fail('路由别名不能为空');
        }
//        if($n_length > 80){
//            return yoo_hello_fail('路由别名不能超过80字符');
//        }
        return yoo_hello_success('成功');
    }


    
  
}