<?php

namespace HashyooEntrust\LaravelModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{

    use SoftDeletes;

    private function child_model()
    {
        $child_model_class = get_called_class();
        return new $child_model_class;
    }

    /**
     * $arr_option参数示例
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    private function demo()
    {
        $arr_option = [
          'with'        => ['aa', 'bb'],
          'where'       => [
            'is_menu' => 1,//类型1
            ['status', '!=', 2],//类型2
          ],
          'whereIn'     => [
            'role_id'   => [6, 8, 22],
            'user_name' => ['张三', '李四'],
          ],
          'whereLike'   => [
            'name'  => '手机',
            'title' => '手机壳',
          ],
          'orWhere'     => [
            'is_menu' => 1,//类型1
            ['status', '!=', 2],//类型2
          ],
          'orWhereLike' => [
            'name'  => '手机',
            'title' => '手机壳',
          ],
          'field'       => ['id', 'name', 'title'],
          'withCount'   => ['aa', 'bb'],
          //order排序，谁在前谁优先
          'orderBy'     => [
            'sort' => 'asc',
            'id'   => 'asc',
          ],
          'limit'       => 15,
          'sum'         => 'score',
          'whereHas'    => [
            'user.goods' => [
              'where'   => [
                'is_menu' => 1,//类型1
                ['status', '!=', 2],//类型2
              ],
              'whereIn' => [
                'role_id'   => [6, 8, 22],
                'user_name' => ['张三', '李四'],
              ],
              //... ...
            ],
          ],
        ];
    }

    /**
     * 获取表字段
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    protected function table_field_keys()
    {
        $prefix       = \DB::getConfig('prefix');
        $table        = self::child_model()->table;
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
     *
     * @param $arr_option
     *
     * @return mixed
     */
    private function en_option($arr_option)
    {
        $arr_option = array_change_key_case($arr_option, CASE_LOWER);
        $arr_common_config
                    = [
          'field' => ['*'],//默认查询字段
          'order' => [
            'id' => 'desc',
          ],//多个排序方式
          'limit' => 10,//默认每页查询条数
        ];
        if (!isset($arr_option['field'])) {
            $arr_option['field'] = $arr_common_config['field'];
        }
        if (!isset($arr_option['order'])) {
            $arr_option['order'] = $arr_common_config['order'];
        }
        if (!isset($arr_option['limit'])) {
            $arr_option['limit'] = $arr_common_config['limit'];
        }

        return $arr_option;
    }

    private function en_with($query, $arr_data) {
        $query = $query->with($arr_data);
        return $query;
    }

    private function en_where($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                $query = $query->where($value[0], $value[1], $value[2]);
            }
            else {
                $query = $query->where($key, $value);
            }
        }
        return $query;
    }

    private function en_wherein($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->whereIn($key, $value);
        }
        return $query;
    }

    private function en_wherelike($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->where($key, 'like', '%' . $value . '%');
        }
        return $query;
    }

    private function en_orwhere($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                $query = $query->orWhere($value[0], $value[1], $value[2]);
            }
            else {
                $query = $query->orWhere($key, $value);
            }
        }
        return $query;
    }

    private function en_orwherelike($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->orWhere($key, 'like', '%' . $value . '%');
        }
        return $query;
    }

    private function en_select($query, $arr_data) {
        $query = $query->select($arr_data);
        return $query;
    }

    private function en_withcount($query, $arr_data) {
        $query = $query->withCount($arr_data);
        return $query;
    }

    private function en_order($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query;
    }

    private function en_wherehas($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->whereHas($key,
              function ($qqqqq)
              use ($value) {
                  $value = array_change_key_case($value, CASE_LOWER);

                  foreach ($value as $kk => $vv) {
                      switch ($kk) {
                          case 'where':
                              $qqqqq = self::en_where($qqqqq,$vv);
                              break;
                          case 'wherein':
                              $qqqqq = self::en_wherein($qqqqq,$vv);
                              break;
                          case 'wherelike':
                              $qqqqq = self::en_wherelike($qqqqq,$vv);
                              break;
                          case 'orwhere':
                              $qqqqq = self::en_orwhere($qqqqq,$vv);
                              break;
                          case 'orwherelike':
                              $qqqqq = self::en_orwherelike($qqqqq,$vv);
                              break;
                          default:
                      }
                  }
              });
        }
        return $query;
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
    private function en_query($query, $arr_option = [])
    {
        $query = isset($arr_option['with']) ? self::en_with($query, $arr_option['with']) : $query;
        $query = isset($arr_option['where']) ? self::en_where($query, $arr_option['where']) : $query;
        $query = isset($arr_option['wherein']) ? self::en_wherein($query, $arr_option['wherein']) : $query;
        $query = isset($arr_option['wherelike']) ? self::en_wherelike($query, $arr_option['wherelike']) : $query;
        $query = isset($arr_option['orwhere']) ? self::en_orwhere($query, $arr_option['orwhere']) : $query;
        $query = isset($arr_option['orwherelike']) ? self::en_orwherelike($query, $arr_option['orwherelike']) : $query;
        $query = isset($arr_option['field']) ? self::en_select($query, $arr_option['field']) : $query;
        $query = isset($arr_option['withcount']) ? self::en_withcount($query, $arr_option['withcount']) : $query;
        $query = isset($arr_option['order']) ? self::en_order($query, $arr_option['order']) : $query;
        $query = isset($arr_option['wherehas']) ? self::en_wherehas($query, $arr_option['wherehas']) : $query;
        return $query;

    }

    private function where_option(){
        return yoo_array_value_lower(['where', 'whereIn', 'orWhere', 'orWhere', 'orWhereLike', 'whereHas']);
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
    public function en_add($arr_input = [])
    {
        $query  = self::child_model();
        $arr_input = yoo_array_remain_trim($arr_input, self::table_field_keys());
        $result    = $query->create($arr_input);
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
    public function en_edit($arr_input = [])
    {
        $query  = self::child_model();
        $arr_input = yoo_array_remain_trim($arr_input, self::table_field_keys());
        $n_id      = intval($arr_input['id']);
        $result    = $query->where(['id' => $n_id])
                              ->update($arr_input);
        return $result;
    }

    /**
     * 根据条件查询统计总量count
     *
     * @param       $query
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_count($arr_option = [])
    {
        $query   = self::child_model();
        $arr_remain = self::where_option();
        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->count();
        return $result;
    }

    /**
     * 根据条件查询统计总和sum
     *
     * @param       $query
     * @param array $arr_input
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function en_sum($arr_option = [])
    {
        $query   = self::child_model();
        $arr_remain = self::where_option();
        $arr_option = self::en_option($arr_option);
        $s_sum = $arr_option['sum'];
        $arr_option = yoo_array_remain($arr_option, $arr_remain);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->sum($s_sum);
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
    public function en_find(int $id, $arr_option = [])
    {
        $query   = self::child_model();
        $arr_remain = yoo_array_value_lower(['with', 'field', 'withCount']);
        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->find($id);
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
    public function en_get_one($arr_option = [])
    {
        $query   = self::child_model();
//        $arr_remain = ['with', 'where', 'whereIn', 'orWhere', 'orWhere', 'orWhereLike', 'field', 'order', 'withCount'];
//        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $arr_option = self::en_option($arr_option);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->first($id);
        return $result;
    }

    /**
     * 获取所有数据
     *
     * @param       $query
     * @param array $arr_option
     *
     * @return mixed
     */
    public function en_get($arr_option = [])
    {
        $query   = self::child_model();
//        $arr_remain = ['with', 'where', 'whereIn', 'whereLike', 'orWhere', 'orWhereLike', 'field', 'order', 'withCount'];
//        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $arr_option = self::en_option($arr_option);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->get();
        return $result;
    }

    /**
     * 获取列表数据-含分页
     * @param       $query
     * @param array $arr_option
     *
     * @return mixed
     */
    public function en_list($arr_option = [])
    {
        $query   = self::child_model();
        $arr_option = self::en_option($arr_option);
//        $arr_remain = ['with', 'where', 'whereIn', 'whereLike', 'orWhere', 'orWhereLike', 'field', 'order', 'withCount', 'limit'];
//        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $query     = self::en_query($query, $arr_option);
        $result     = $query->paginate($arr_option['limit']);
        return $result;
    }

    /**
     * 删除数据
     *
     * @param      $query
     * @param bool $bool
     *
     * @return mixed
     */
    public function en_delete($n_id = 0, bool $bool = true)
    {
        $query = self::child_model();
        if ($bool === true) {
            $result = $query->where('id', $n_id)
                            ->forceDelete();  //物理删除
        }
        else {
            $result = $query->where('id', $n_id)
                            ->delete();       //软删除
        }
        return $result;

    }

    /**
     * 删除数据
     *
     * @param      $query
     * @param bool $bool
     *
     * @return mixed
     */
    public function en_del($arr_option = [], bool $bool = true)
    {
        $query   = self::child_model();
        $arr_remain = self::where_option();
        $arr_option = yoo_array_remain(self::en_option($arr_option), $arr_remain);
        $query     = self::en_query($query, $arr_option);

        if ($bool === true) {
            $result = $query->forceDelete();  //物理删除
        }
        else {
            $result = $query->delete();       //软删除
        }
        return $result;
    }

    /**
     * 递归删除数据
     *
     * @param      $query
     * @param bool $bool
     *
     * @return mixed
     */
    public function en_del_recur($n_id = 0, $arr_option = [], bool $bool = true)
    {
        //获取所有子级ID（包括当前数据id）
        if (!isset($arr_option['pk'])) {
            $s_pk = 'id';
        }
        if (!isset($arr_option['pid_key'])) {
            $s_pid_key = 'pid';
        }
        $arr_option['field'] = [$s_pk, $s_pid_key];
        $result              = self::en_get($arr_option)
                                   ->toArray();
        yoo_tree_child_ids($result, $arr_ids, $n_id, $s_pk, $s_pid_key);
        $arr_ids[]  = $n_id;
        $arr_option = ['whereIn' => ['id' => $arr_ids]];
        $result     = self::en_del($arr_option, $bool);
        return $result;
    }



}
