<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/22
 * Time: 下午1:41
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Loopeer\QuickCms\Models\Backend\System;
use Loopeer\QuickCms\Models\Backend\Permission;
use Loopeer\QuickCms\Models\Backend\PermissionRole;
use Loopeer\QuickCms\Services\Utils\GeneralUtil;

/**
 * 后台Controller基类
 * Class BaseController
 * @package App\Http\Controllers\Backend
 */
class BaseController extends Controller
{

    protected $systemConfig;

    public function __construct()
    {
        if(!Session::has('permissions') && Auth::admin()->get()->roles()->first() !== null) {
            $roles = Auth::admin()->get()->roles()->first();
            $permission_ids = PermissionRole::where('role_id', $roles->pivot->role_id)->lists('permission_id');
            $permissions = Permission::where('type', 1)->whereIn('id', $permission_ids)->get();
            Session::put('permissions', $permissions);
        }
        $this->systemConfig = Cache::rememberForever('system_config', function() {
            return System::get();
        });


        $system_title = System::where('key', 'title')->first();
        $system_logo = System::where('key', 'logo')->first();
        if (isset($system_title)) {
            view()->share('system_title', $system_title->value);
        }
        if (isset($system_logo)) {
            view()->share('system_logo', $system_logo->value);
        }

        foreach(GeneralUtil::allSelectorData() as $sk => $sv) {
            view()->share($sk, $sv);
        }
    }

    /**
     * 分页查询封装函数
     * @param array $show_column ['id','name','email']
     * @param object $model new Model
     * @param string $append_column
     * @param string $str_column
     * @return array
     */
    public function simplePage($show_column, $model, $append_column = NULL, $str_column = NULL) {
        $search = Input::get('search')['value'];
        $search = addslashes($search);
        $length = Input::get('length');
        self::setCurrentPage($length);
        if ($str_column == NULL) {
            $str_column = implode(',', $show_column);
        }

        $paginate = $model->selectRaw($str_column)
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->paginate($length);

        if(isset($append_column)) {
            $show_column[$append_column] = $append_column;
        }
        $ret = self::getPageDate($show_column, $paginate);
        return $ret;
    }

    public function fastQuery($model, $queryType = 'paginate', $redirect_value = null)
    {
        if ($queryType == 'all') {
            $columns = Cache::get('export_columns_' . Auth::admin()->get()->id);
            $orders = Cache::get('export_orders_' . Auth::admin()->get()->id);
        } else {
            $length = Input::get('length');
            $columns = Input::get('columns');
            $orders = Input::get('order');
            self::setCurrentPage($length);
            //缓存列表和排序参数
            if ($model->buttons['queryExport']) {
                $expiresAt = Carbon::now()->addMinutes(config('quickCms.login_lifetime', 60));
                Cache::put('export_columns_' . Auth::admin()->get()->id, $columns, $expiresAt);
                Cache::put('export_orders_' . Auth::admin()->get()->id, $orders, $expiresAt);
            }
        }
        $query = array_column($model->index, 'query');

        $relations = collect($model->create)->map(function ($create) {
            return $create['column'];
        })->filter(function ($column) {
            return strstr($column, '.') !== FALSE;
        })->map(function ($column) {
            $relation = explode('.', $column);
            return $relation[0];
        })->unique()->toArray();

        $builder = count($relations) > 0 ? $model->with($relations) : $model;

        if ($model->business_id && session('business_id')) {
            $builder = $builder->where($model->business_id, session('business_id'));
        }
        if ($model->redirect_column !== null) {
            $builder = $builder->where($model->redirect_column, $redirect_value);
        }
        if(count($model->where) > 0) {
            foreach ($model->where as $key => $value) {
                //$builder = $value == 'admin' ? $builder->where($key, Auth::admin()->get()->email) : $builder->where($key, $value);
                if ($value == 'admin') {
                    $builder = $builder->where($key, Auth::admin()->get()->email);
                } elseif ($value == 'admin_id') {
                    $builder = $builder->where($key, Auth::admin()->get()->id);
                } elseif (is_array($value)) {
                    if (!isset($value['where'])) {
                        $builder = $builder->whereIn($key, $value);
                    } else if($value['where'] == 'or'){
                        $builder = $builder->whereIn($key, $value['data']);
                    } else {
                        if ($value['where'] == '=') {
                            $builder = $builder->where($key, Auth::admin()->get()->id);
                        } else {
                            $bind = config('quickCms.admin_account_bind');
                            $reflectionClass = new \ReflectionClass($bind['model']);
                            $accountModel = $reflectionClass->newInstance();
                            $accountIds = $accountModel->where($bind['column'], Auth::admin()->get()->id)->lists('id');
                            $builder = $builder->whereIn($key, $accountIds);
                        }
                    }
                } else {
                    $builder = $builder->where($key, $value);
                }
            }
        }
        if (count($query) > 0) {
            foreach($columns as $column) {
                $value = $column['search']['value'];
                if ($value != null && $value != ',') {
                    $name = $column['name'];
                    foreach ($model->index as $qk => $qv) {
                        if ($name == $model->index[$qk]['column']) {
                            $builder = self::queryBuilder($builder, $model->index[$qk], $value, isset($model->table) ? $model->table : '');
                            break;
                        }
                    }
                }
            }
        }

        $selectRaw = '';
        foreach($columns as $column) {
            $column_name = $column['name'];
            $table = $model->table;
            foreach ($model->index as $qk => $qv) {
                if ($column_name == $model->index[$qk]['column']) {
                    $item = $model->index[$qk];

                    if (isset($item['join_column'])){
                        if(isset($item['join_table'])){
                            switch($item['format']){
                                case 'count':
                                    $selectRaw .= 'count('.$item['join_table'].'.id)'.' as '.$column_name.',';
                                    break;
                                case 'sum':
                                    $selectRaw .= 'sum('.$item['join_table'].'.'.$item['sum_column'].') as '.$column_name.',';
                            }
                        }else{
                            $selectRaw .= $table.'.'.$column_name.' as '.$column_name.',';
                        }
                    } else if(strpos($column_name, '.') === false && !isset($item['append'])){
                        $selectRaw .= '`'.$column_name."`,";
                    }
                }
            }
            if(isset($model->indexAppend)){
                foreach ($model->indexAppend as $item){
                    $selectRaw .= '`'.$item."`,";
                }
            }
        }
        $builder = $builder->select(DB::raw(rtrim($selectRaw, ',')));


        if(isset($model->join)){
            $join = $model->join;
            $builder->leftJoin($join['table'], function($query) use($join){
                foreach($join['on'] as $value){
                    $query->on($value['column'], $value['operate'], $value['ref']);
                }
                if(isset($join['where'])){
                    foreach($join['where'] as $where){
                        $query->where($where['column'], $where['operate'], $where['ref']);
                    }
                }
            });
        }


        if(isset($model->groupBy)){
            $groupBy = $model->groupBy;
            foreach ($groupBy as $value)
            {
                $builder->groupBy($value);
            }
        }

        if (count(array_column($model->index, 'order')) > 0) {
            foreach ($orders as $order) {
                $builder = $builder->orderBy($model->index[$order['column']]['column'], $order['dir']);
            }
        }
        if ($queryType == 'all') {
            return self::getAllData(array_column($model->index, 'column'), $builder->get());
        } else {
            if(isset($model->groupBy)){
                $totalCount = DB::selectOne("select count(*) as count from (" .$builder->toSql().') a', $builder->getBindings())->count;
                return self::getPageDate(array_column($model->index, 'column'), $builder->simplePaginate($length), '', $totalCount, 'simple');
            }
            return self::getPageDate(array_column($model->index, 'column'), $builder->paginate($length), '');
        }
    }

    private function getAllData($columns, $collection)
    {
        $data = array();
        foreach($collection as $item) {
            $obj = array();
            foreach($columns as $column) {
                if($item->$column instanceof Carbon) {
                    array_push($obj, $item->$column->format('Y-m-d H:i:s'));
                } elseif (strstr($column, '.') !== FALSE) {
                    $table_column = explode('.', $column);
                    array_push($obj, $item->{$table_column[0]}->{$table_column[1]});
                } else {
                    array_push($obj, $item->$column);
                }
            }
            array_push($data, $obj);
        }
        return $data;
    }


    /**
     * 自定义查询分页函数
     * @param array $show_column ['id','name','email']
     * @param array $paginate query sql
     * @return array
     */
    public function queryPage($show_column, $paginate, $appends = []) {
        $ret = self::getPageDate($show_column, $paginate, $appends);
        return $ret;
    }

    /**
     * 设置分页对象的当前页
     */
    public function setCurrentPage() {
        $start = Input::get('start');
        $length = Input::get('length');
        $page = ($start / $length) + 1;
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });
    }

    /**
     * 获取分页对象数据
     * @param array $show_column ['id','name','email']
     * @param array $paginate query sql
     * @return array
     */
    private function getPageDate($show_column, $paginate, $appends = [], $totalCount = '', $simple = '') {
        $draw = Input::get('draw');
        $data = array();

        foreach ($paginate->items() as $item) {
            $obj = array();
            foreach ($show_column as $column) {
                if($item->$column instanceof Carbon) {
                    array_push($obj, $item->$column->format('Y-m-d H:i:s'));
                } elseif (strstr($column, '.') !== FALSE) {
                    $table_column = explode('.', $column);
                    if (count($item->{$table_column[0]}) > 0) {
                        array_push($obj, $item->{$table_column[0]} instanceof Collection ? $item->{$table_column[0]}->first()->{$table_column[1]} : $item->{$table_column[0]}->{$table_column[1]});
                    } else {
                        array_push($obj, '无');
                    }
                } else {
                    array_push($obj, $item->$column);
                }
            }
            array_push($data, $obj);
        }
        $ret = array(
            'draw' => $draw,
            'recordsTotal' => $simple ? $totalCount : $paginate->total(),
            'recordsFiltered' => $simple ? $totalCount : $paginate->total(),
            'data' => $data,
        );
        return $ret;
    }

    /**
     * 设置自定义sql语句中得表名前缀
     * @param $column
     * @param array $searches
     * @return mixed
     */
    public function setTablePrefix($column, $searches = array()) {
        $prefix = config('database.connections.mysql.prefix');
        foreach($searches as $search) {
            $column = str_replace($search, $prefix . $search, $column);
        }
        return $column;
    }

    /**
     * 获取多表查询数据
     * @param $obj
     * @param $select_column
     * @param $show_column
     * @param $tables
     * @return mixed
     */
    public function getMultiTableData($obj, $select_column, $show_column, $tables){
        $search = Input::get('search')['value'];
        $search = addslashes($search);
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = self::setTablePrefix(implode(',', $select_column), $tables);
        self::setCurrentPage();
        $obj = $obj->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);
        $ret = self::queryPage($show_column, $obj);
        return response()->json($ret);
    }

    public function getSystemValue($key)
    {
        $collection = $this->systemConfig;
        $filtered = $collection->where('key', $key);
        return $filtered->first()['value'];
    }

    private function queryBuilder($builder, $item, $value, $table_name)
    {
        $type = isset($item['type']) ? $item['type'] : 'input';
        switch ($type) {
            case 'input':
                $builder = self::queryInput($builder, $item, $value, $table_name);
                break;
            case 'select':
                $builder = self::querySelector($builder, $item, $value, $table_name);
                break;
            case 'checkbox':
                $builder = self::queryCheckbox($builder, $item['column'], $value);
                break;
            case 'date':
                $builder = self::queryDate($builder, $item, $value, $table_name);
                break;
            case 'datetime':
                $builder = self::queryDateTime($builder, $item['column'], $value, $item['query']);
                break;
            case 'having_input':
                $builder = self::queryHavingInput($builder, $item, $value, $table_name);
                break;
            default:
                break;
        }
        return $builder;
    }

    private function queryInput($builder, $item, $value, $table_name)
    {
        $operator = $item['query'];
        $name = $item['column'];
        if (strstr($name, '.') !== FALSE) {
            $table_column = explode('.', $item['column']);
            if($operator == 'between'){

                $values = explode(',', $value);
                if ($values[0] != null && $values[1] != null) {
                    if(isset($item['format']) && $item['format'] == 'amount'){
                        $values[0] = $values[0] * 100;
                        $values[1] = $values[1] * 100;
                    }
                    return $builder->whereHas($table_column[0], function ($query) use ($table_column, $values) {
                        $query->whereRaw("$table_column[1] between '" . ($values[0]) . "' and '" . ($values[1]) . "'");
                    });
                }
            }
            return $builder->whereHas($table_column[0], function ($query) use ($table_column, $value, $operator) {
                $query->where($table_column[1], $operator, $operator == 'like' ? "%$value%" : $value);
            });
        }
        if(isset($item['join_column'])){
            $name = $table_name.'.'.$name;
        }
        if($operator == 'between')
        {
            $values = explode(',', $value);
            if ($values[0] != null && $values[1] != null) {
                if(isset($item['format']) && $item['format'] == 'amount'){
                    $values[0] = $values[0] * 100;
                    $values[1] = $values[1] * 100;
                }
                return $builder->whereRaw("$name between '" . ($values[0]) . "' and '" . ($values[1]) . "'");
            }
        }
        return $builder->where($name, $operator, $operator == 'like' ? "%$value%" : $value);
    }

    private function queryHavingInput($builder, $item, $value, $table_name)
    {
        $operator = $item['query'];
        $name = $item['column'];
        if($operator == 'between'){

            $values = explode(',', $value);
            if ($values[0] != null && $values[1] != null) {
                if(isset($item['format']) && $item['format'] == 'amount'){
                    $values[0] = $values[0] * 100;
                    $values[1] = $values[1] * 100;
                }
                return $builder->havingRaw("$name >= '$values[0]' and $name <= '$values[1]'");
            }
        }
    }

    private function querySelector($builder, $item, $value, $table_name)
    {
        $operator = $item['query'];
        $name = $item['column'];
        if (strstr($name, '.') !== FALSE) {
            $table_column = explode('.', $name);
            return $builder->whereHas($table_column[0], function ($query) use ($table_column, $value, $operator) {
                $query->where($table_column[1], $operator, $operator == 'like' ? "%$value%" : $value);
            });
        }

        if ($operator && $operator == 'scope') {
            return $builder->$name($value);
        }

        if(isset($item['join_column'])){
            $name = $table_name.'.'.$name;
        }

        if(isset($item['expend']))
        {
            return $builder->whereNotNull($item['expend'][$value]);
        }
        return $builder->where($name, $value);
    }

    private function queryCheckbox($builder, $name, $value)
    {
        return $builder->whereIn($name, explode(',', $value));
    }

    private function queryDate($builder, $item, $value, $table_name)
    {
        $operator = $item['query'];
        $name = $item['column'];
        if ($operator && $operator == 'between') {
            $values = explode(',', $value);
            if ($values[0] != null || $values[1] != null) {

                if (strstr($name, '.') !== FALSE) {
                    $table_column = explode('.', $name);
                    return $builder->whereHas($table_column[0], function ($query) use ($table_column, $values, $operator) {
                        $query->whereRaw("date($table_column[1]) between '" . ($values[0] ?: "0000-01-01") . "' and '" . ($values[1] ?: "9999-01-01") . "'");
                    });
                }
                if(isset($item['join_column'])){
                    $name = $table_name.'.'.$name;
                }
                return $builder->whereRaw("date($name) between '" . ($values[0] ?: "0000-01-01") . "' and '" . ($values[1] ?: "9999-01-01") . "'");
            }
        }
        return $builder->whereRaw("date($name) = '" . $value . "'");
    }

    private function queryDateTime($builder, $name, $value, $operator)
    {
        if ($operator && $operator == 'between') {
            $values = explode(',', $value);
            if ($values[0] != null || $values[1] != null) {

                if (strstr($name, '.') !== FALSE) {
                    $table_column = explode('.', $name);
                    return $builder->whereHas($table_column[0], function ($query) use ($table_column, $values, $operator) {
                        $query->whereRaw("$table_column[1] between '" . ($values[0] ?: "0000-01-01 00:00:00") . "' and '" . ($values[1] ?: "9999-01-01 00:00:00") . "'");
                    });
                }

                return $builder->whereRaw("$name between '" . ($values[0] ?: "0000-01-01 00:00:00") . "' and '" . ($values[1] ?: "9999-01-01 00:00:00") . "'");
            }
        }
        return $builder->whereRaw("$name = '" . $value . "'");
    }
}