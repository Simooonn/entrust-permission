<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */

namespace HashyooEntrust\LaravelModel;

class EntrustRoles extends BaseModel
{
    
    protected $table = 'entrust_roles';
    
    protected $guarded = [];
    
    //public $timestamps = false;
    
    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
    ];

    public function user_role(){
        return $this->belongsTo(EntrustUserRole::class,'id','role_id');
    }

    public function form_add($arr_input = []){
        
        //角色名称
        $n_length = mb_strlen($arr_input['name']);
        if($n_length <= 0){
            return yoo_hello_fail('角色名称不能为空');
        }
        return yoo_hello_success('成功');
    }


    
  
}