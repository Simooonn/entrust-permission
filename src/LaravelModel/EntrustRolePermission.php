<?php
/**
 * common model file Created by PhpStorm.
 * User: wumengmeng
 * Date: 2019/08/20
 * Time: 06:01
 */

namespace HashyooEntrust\LaravelModel;

class EntrustRolePermission extends BaseModel
{
    
    protected $table = 'entrust_role_permission';
    
    protected $guarded = [];
    
    //public $timestamps = false;
    
    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
    ];



    
  
}