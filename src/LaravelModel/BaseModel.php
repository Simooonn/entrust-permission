<?php

namespace HashyooEntrust\LaravelModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{

    use SoftDeletes;

    private static $instance;

    private function instance(){
        //检测当前类属性$instance是否已经保存了当前类的实例
        if (!isset(self::$instance) || (self::$instance === null)) {
            //如果没有,则创建当前类的实例

            self::$instance = self::child_model();
        }
        //如果已经有了当前类实例,就直接返回,不要重复创建类实例
        return self::$instance;
    }
    
    private function child_model(){
        $child_model_class = get_called_class();
        return new $child_model_class;
    }

    /**
     * 获取表字段
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    protected function table_field_keys()
    {
        $prefix =  \DB::getConfig('prefix');
        $table = self::child_model()->table;
        $s_full_table = $prefix . $table;
        $columns      = \DB::getDoctrineSchemaManager()
                           ->listTableColumns($s_full_table);
        $arr_data     = [];
        foreach ($columns as $column) {
            $arr_data[] = $column->getName();
        }
        return $arr_data;
    }
    /**
     * 基础config
     * @param $arr_option
     * @return mixed
     */
    private function enOption($arr_option)
    {
        $arr_common_config =
          [
            'with' => [],//默认关联
            'where' => [],//默认查询条件
            'orWhere' => [],//默认查询条件
            'field' => ['*'],//默认查询字段
            'order' => [
              'id' => 'desc',
            ],//默认排序方式
            'limit' => 10,//默认每页查询条数
            'withCount' => [],//默认关联统计
          ];

        if (!isset($arr_option['with'])) {
            $arr_option['with'] = $arr_common_config['with'];
        }
        if (!isset($arr_option['where'])) {
            $arr_option['where'] = $arr_common_config['where'];
        }
        if (!isset($arr_option['orWhere'])) {
            $arr_option['orWhere'] = $arr_common_config['orWhere'];
        }
        if (!isset($arr_option['field'])) {
            $arr_option['field'] = $arr_common_config['field'];
        }
        if (!isset($arr_option['order'])) {
            $arr_option['order'] = $arr_common_config['order'];
        }
        if (!isset($arr_option['limit'])) {
            $arr_option['limit'] = $arr_common_config['limit'];
        }
        if (!isset($arr_option['withCount'])) {
            $arr_option['withCount'] = $arr_common_config['withCount'];
        }

        return $arr_option;
    }

    /**
     * 条件查询
     *
     * @param       $query
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    private function en_query($query,$arr_option = []){
        $arr_with = $arr_option['with'];
        $arr_where = $arr_option['where'];
        $arr_whereIn = $arr_option['whereIn'];
        $arr_whereLike = $arr_option['whereLike'];
        $arr_orWhere = $arr_option['orWhere'];
        $arr_orWhereLike = $arr_option['orWhereLike'];
        $arr_select = $arr_option['field'];
        $arr_withCount = $arr_option['withCount'];
        $arr_order = $arr_option['order'];
        
        $result = $query;
        if(isset($arr_with)){
            /*案例*/
            /*$arr_option['with'] = ['aa','bb'];*/
            
            $result = $result->with($arr_with);
        }
        if(isset($arr_where)){
            /*案例*/
            /*$arr_option['where'] = [
              'is_menu'=>1,//类型1
              ['status','!=',2],//类型2
            ];*/
            
            foreach ($arr_where as $key=>$value) {
                if(is_array($value)){
                    $result = $result->where($value[0],$value[1],$value[2]);
                }
                else{
                    $result = $result->where($key,$value);
                }
            }
        }
        if(isset($arr_whereIn)){
            /*案例*/
            /*$arr_option['whereIn'] = ['role_id'=>[6,8,22]];*/
            
            foreach ($arr_whereIn as $key=>$value) {
                $result = $result->whereIn($key,$value);
            }
        }
        if(isset($arr_whereLike)){
            /*案例*/
            /*$arr_option['whereLike'] = ['name'=>'手机'];*/
            
            foreach ($arr_whereLike as $key=>$value) {
                $result = $result->where($key,'like','%'.$value.'%');
            }
        }
        if(isset($arr_orWhere)){
            /*案例*/
            /*$arr_option['orWhere'] = [
              'is_menu'=>1,//类型1
              ['status','!=',2],//类型2
            ];*/
            
            foreach ($arr_orWhere as $key=>$value) {
                if(is_array($value)){
                    $result = $result->orWhere($value[0],$value[1],$value[2]);
                }
                else{
                    $result = $result->orWhere($key,$value);
                }
            }
        }
        if(isset($arr_orWhereLike)){
            /*案例*/
            /*$arr_option['orWhereLike'] = ['name'=>'手机'];*/
            
            foreach ($arr_orWhereLike as $key=>$value) {
                $result = $result->orWhere($key,'like','%'.$value.'%');
            }
        }
        if(isset($arr_select)){
            /*案例*/
            /*$arr_option['field'] = ['id','name','title'];*/
            
            $result = $result->select($arr_select);
        }
        if(isset($arr_withCount)){
            /*案例*/
            /*$arr_option['withCount'] = ['aa','bb'];*/
            
            $result = $result->withCount($arr_withCount);//必须放在select后面才会生效
        }
        if(isset($arr_order)){
            /*案例*/
            /*$arr_option['order'] = ['sort'=>'asc','id'=>'asc'];*/
            
            foreach ($arr_order as $key=>$value) {
                $result = $result->orderBy($key,$value);
            }
        }
        return $result;

    }

    /**
     * 添加一条数据
     *
     * @param       $instance
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_add($arr_input = [])
    {
        $instance = self::child_model();
        $arr_input = yoo_array_remain_trim($arr_input,self::table_field_keys());
        $result = $instance->create($arr_input);
        return $result;
    }

    /**
     * 修改一条数据
     *
     * @param       $instance
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_edit($arr_input = [])
    {
        $instance = self::child_model();
        $arr_input = yoo_array_remain_trim($arr_input,self::table_field_keys());
        $n_id = intval($arr_input['id']);
        $result = $instance->where(['id'=>$n_id])->update($arr_input);
        return $result;
    }

    /**
     * 根据条件查询count
     *
     * @param       $instance
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_count($arr_option = [])
    {
        $instance = self::child_model();
        $arr_remain = ['where','whereIn','orWhere'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);
        $result = $result->count();
        return $result;
    }

    /**
     * 查询一条数据(通过主键id查询)
     *
     * @param       $instance
     * @param int   $id
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_find( int $id, $arr_option = [])
    {
        $instance = self::child_model();
        $arr_remain = ['with','field','withCount'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);
        $result = $result->find($id);
        return $result;
    }

    /**
     * 查询一条数据(通过where或者orwhere条件查询)
     *
     * @param       $instance
     * @param array $arr_option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_get_one( $arr_option = [])
    {
        $instance = self::child_model();
        $arr_remain = ['with','where','whereIn','orWhere','field','order','withCount'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);
        $result = $result->first($id);
        return $result;
    }

    /**
     * 获取所有数据
     * @param $instance
     * @param array $arr_option
     * @return mixed
     */
    public function en_get( $arr_option = [])
    {
        $instance = self::child_model();
        $arr_remain = ['with','where','whereIn','whereLike','orWhere','orWhereLike','field','order','withCount'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);
        $result = $result->get();
        return $result;
    }

    /**
     * 获取列表数据-含分页
     * @param $instance
     * @param array $arr_option
     * @return mixed
     */
    public function en_list( $arr_option = [])
    {
        $instance = self::child_model();
        $arr_option = self::enOption($arr_option);
        $arr_remain = ['with','where','whereIn','whereLike','orWhere','orWhereLike','field','order','withCount','limit'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);
        $result = $result->paginate($arr_option['limit']);
        return $result;
    }

    /**
     * 删除数据
     * @param $instance
     * @param bool $bool
     * @return mixed
     */
    public function en_delete($n_id = 0, bool $bool = true)
    {
        $instance = self::child_model();
        if ($bool === true) {
            return $instance->where('id',$n_id)->forceDelete();  //物理删除
        } else {
            return $instance->where('id',$n_id)->delete();       //软删除
        }
    }

    /**
     * 删除数据
     * @param $instance
     * @param bool $bool
     * @return mixed
     */
    public function en_del($arr_option = [], bool $bool = true)
    {
        $instance = self::child_model();
        $arr_remain = ['where','orWhere','whereIn'];
        $arr_option = yoo_array_remain(self::enOption($arr_option),$arr_remain);
        $result = self::en_query($instance,$arr_option);

        if ($bool === true) {
            return $result->forceDelete();  //物理删除
        } else {
            return $result->delete();       //软删除
        }
    }

    /**
     * 递归删除数据
     * @param $instance
     * @param bool $bool
     * @return mixed
     */
    public function en_del_recur($n_id = 0,$arr_option = [], bool $bool = true)
    {
        //获取所有子级ID（包括当前数据id）
        if(!isset($arr_option['pk'])){
            $s_pk = 'id';
        }
        if(!isset($arr_option['pid_key'])){
            $s_pid_key = 'pid';
        }
        $arr_option['field'] = [$s_pk,$s_pid_key];
        $result = self::en_get($arr_option)->toArray();
        yoo_tree_child_ids($result,$arr_ids,$n_id,$s_pk,$s_pid_key);
        $arr_ids[] = $n_id;
        $arr_option = ['whereIn'=>['id'=>$arr_ids]];
        $result = self::en_del($arr_option,$bool);
        return $result;
    }








    /**
     * 添加一条数据
     *
     * @param       $query
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeEnAdd($query,$arr_input = [])
    {
        $arr_input = yoo_array_remain_trim($arr_input,self::table_field_keys());
        $result = $query->create($arr_input);
        return $result;
    }

    /**
     * 修改一条数据
     *
     * @param       $query
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeEnEdit($query,$arr_input = [])
    {
        $arr_input = yoo_array_remain_trim($arr_input,self::table_field_keys());
        $n_id = intval($arr_input['id']);
        $result = $query->where(['id'=>$n_id])->update($arr_input);
        return $result;
    }

    /**
     * 根据条件查询count
     *
     * @param       $query
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeEnCount($query,$arr_option = [])
    {
        $arr_option = self::enOption($arr_option);
        $arr_option = yoo_array_remain($arr_option,['where','orWhere']);
        $result = self::en_query($query,$arr_option);
        $result = $result->count();
        return $result;
    }

    /**
     * 查询一条数据(通过主键id查询)
     *
     * @param       $query
     * @param int   $id
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeEnFind($query, int $id, $arr_option = [])
    {
        $arr_option = self::enOption($arr_option);
        $arr_option = yoo_array_remain($arr_option,['with','field','withCount']);
        $result = self::en_query($query,$arr_option);
        $result = $result->find($id);
        return $result;
    }

    /**
     * 查询一条数据(通过where或者orwhere条件查询)
     *
     * @param       $query
     * @param array $arr_option
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeEnGetOne($query, $arr_option = [])
    {
        $arr_option = self::enOption($arr_option);
        $arr_option = yoo_array_remain($arr_option,['with','where','orWhere','field','order','withCount']);
        $result = self::en_query($query,$arr_option);
        $result = $result->first($id);
        return $result;
    }

    /**
     * 获取所有数据
     * @param $query
     * @param array $arr_option
     * @return mixed
     */
    public function scopeEnGet($query, $arr_option = [])
    {
        $arr_option = self::enOption($arr_option);
        $arr_option = yoo_array_remain($arr_option,['with','where','whereLike','orWhere','orWhereLike','field','order','withCount']);
        $result = self::en_query($query,$arr_option);
        $result = $result->get();
        return $result;
    }

    /**
     * 获取列表数据-含分页
     * @param $query
     * @param array $arr_option
     * @return mixed
     */
    public function scopeEnList($query, $arr_option = [])
    {
        $arr_option = self::enOption($arr_option);
        $n_limit = $arr_option['limit'];
        $arr_option = yoo_array_remain($arr_option,['with','where','whereLike','orWhere','orWhereLike','field','order','withCount']);
        $result = self::en_query($query,$arr_option);
        $result = $result->paginate($n_limit);
        return $result;
    }

    /**
     * 删除数据
     * @param $query
     * @param bool $bool
     * @return mixed
     */
    public function scopeEnDelete($query,$n_id = 0, bool $bool = true)
    {
        if ($bool === true) {
            return $query->where('id',$n_id)->forceDelete($n_id);  //物理删除
        } else {
            return $query->where('id',$n_id)->delete($n_id);       //软删除
        }
    }


}
