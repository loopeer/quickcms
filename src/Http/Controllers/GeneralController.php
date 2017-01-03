<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/15
 * Time: 下午5:54
 */

namespace Loopeer\QuickCms\Http\Controllers;

use Loopeer\QuickCms\Models\Selector;
use Loopeer\QuickCms\Services\Utils\GeneralUtil;
use Route;
use Session;
use Response;
use Input;
use View;
use Redirect;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Log;

class GeneralController extends BaseController
{

    protected $model_class;
    protected $model_name;
    protected $route_name;
    protected $route_path;
    protected $index_column;
    protected $index_column_format;
    protected $index_column_name;
    protected $index_column_rename;
    protected $index_multi;
    protected $index_multi_column;
    protected $index_multi_join;
    protected $edit_column;
    protected $edit_column_name;
    protected $edit_column_detail;
    protected $edit_redirect_location;
    protected $actions;
    protected $curd_action;
    protected $model;
    protected $sort;
    protected $where;
    protected $edit_hidden;
    protected $edit_editor;
    protected $detail_column;
    protected $detail_column_rename;
    protected $detail_column_name;
    protected $detail_multi_column;
    protected $detail_multi_join;
    protected $detail_style;
    protected $custom_id_relation_column;
    protected $custom_id_back_url;
    protected $edit_hidden_business_id;
    protected $index_business_where;
    protected $custom_id_relation;
    protected $is_permission;
    protected $table_sort;
    protected $table_action_line;
    protected $table_column_width;
    protected $table_order;
    protected $detail_filter_empty;

    public function __construct(Request $request) {
        try {
            $this->route_path = preg_replace('/(admin\/)|(\/create)|(\/search)|(\/edit)|(\/changeStatus)|(\/detail)|\/{(?!custom_id).*?}/', '',
                Route::getCurrentRoute()->getPath());
            $this->route_path = str_replace('{custom_id}', Route::getCurrentRoute()->parameter('custom_id'), $this->route_path);

            if (is_null(Route::getCurrentRoute()->getName())) {
                $this->route_name = preg_replace('/(\/\d*\/)|(\/)/', '.', $this->route_path);
            } else {
                $this->route_name = preg_replace('/(admin.)|(.\w*$)/', '', Route::getCurrentRoute()->getName());
            }
            $general_name = 'generals.' . $this->route_name . '.';
            $is_permission = true;
            if (config($general_name . 'disable_permission') === true) {
                $is_permission = false;
            }
            $this->is_permission = $is_permission;
            if ($is_permission) {
                GeneralUtil::filterOperationPermission($request, null, $this->route_name);
            }
            $this->index_column = config($general_name . 'index_column');
            $this->index_column_format = config($general_name . 'index_column_format');
            $this->index_column_name = config($general_name . 'index_column_name');
            $this->index_column_rename = config($general_name . 'index_column_rename', array());

            $this->edit_column = config($general_name . 'edit_column');
            $this->edit_column_label = config($general_name . 'edit_column_label');
            $this->edit_column_name = config($general_name . 'edit_column_name');
            $this->edit_column_detail = config($general_name . 'edit_column_detail');

            $this->model_class = config($general_name . 'model_class');
            $this->model_name = config($general_name . 'model_name');
            $this->actions = config($general_name . 'table_action');
            $this->sort = config($general_name . 'sort');
            $this->where = config($general_name . 'index_where');
            $this->table_sort = config($general_name . 'table_sort');
            $this->table_action_line = config($general_name . 'table_action_line');
            $this->table_column_width = config($general_name . 'table_column_width');
            $this->table_order = config($general_name . 'table_order');

            $this->edit_redirect_location = config($general_name . 'edit_redirect_location');
            $this->edit_hidden = config($general_name . 'edit_hidden');
            $this->edit_editor = config($general_name . 'edit_editor');
            $this->curd_action = config($general_name . 'curd_action');

            $this->index_multi = config($general_name . 'index_multi');
            $this->index_multi_column = config($general_name . 'index_multi_column');
            $this->index_multi_join = config($general_name . 'index_multi_join');

            $reflectionClass = new \ReflectionClass($this->model_class);
            $this->model = $reflectionClass->newInstance();
            $middleware = config($general_name . 'middleware', array());

            $this->detail_column = config($general_name . 'detail_column', array());
            $this->detail_column_name = config($general_name . 'detail_column_name', array());
            $this->detail_multi_join = config($general_name . 'detail_multi_join');
            $this->detail_multi_column = config($general_name . 'detail_multi_column');
            $this->detail_column_rename = config($general_name . 'detail_column_rename');
            $this->detail_style =  config($general_name . 'detail_style');
            $this->detail_filter_empty = config($general_name . 'detail_filter_empty');

            $this->custom_id_relation_column = config($general_name . 'custom_id_relation_column');
            $this->custom_id_back_url = config($general_name . 'custom_id_back_url');

            $this->edit_hidden_business_id = config($general_name . 'edit_hidden_business_id');
            $this->index_business_where = config($general_name . 'index_business_where');
            $this->custom_id_relation = config($general_name . 'custom_id_relation');
            //foreach ($middleware as $value) {
            //$this->middleware('auth.permission:' . implode(',', $middleware));
            //}
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
        parent::__construct();
    }

    /**
     * 搜索
     * @param $custom_id
     * @return mixed
     */
    public function search($custom_id = null)
    {
        $model = $this->model;
        if(isset($this->where)) {
            foreach($this->where as $value) {
                switch($value['operator']) {
                    case 'whereIn':
                        $model = $model->whereIn($value['column'], $value['value']);
                        break;
                    case 'whereNotIn':
                        $model = $model->whereNotIn($value['column'], $value['value']);
                        break;
                    case 'whereBetween':
                        $model = $model->whereBetween($value['column'], $value['value']);
                        break;
                    case 'whereNotBetween':
                        $model = $model->whereNotBetween($value['column'], $value['value']);
                        break;
                    case 'whereNull':
                        $model = $model->whereNull($value['column']);
                        break;
                    case 'whereNotNull':
                        $model = $model->whereNotNull($value['column']);
                        break;
                    default:
                        $model = $model->where($value['column'], $value['operator'], $value['value']);
                        break;
                }
            }
        }
//        if(isset($this->sort)) {
//            foreach ($this->sort as $sort) {
//                $model = $model->orderBy($sort[0], $sort[1]);
//            }
//        }
        if (isset($custom_id)) {
            if (isset($this->custom_id_relation_column)) {
                $model = $model->where($this->custom_id_relation_column, $custom_id);
            } elseif(isset($this->custom_id_relation)) {
                $reflectionClass = new \ReflectionClass($this->custom_id_relation['relation_model_class']);
                $relation_model = $reflectionClass->newInstance();
                $relation_ids = $relation_model->where($this->custom_id_relation['custom_id_column'], $custom_id)
                    ->lists($this->custom_id_relation['relation_id_column'])
                    ->all();
                $model = $model->whereIn('id', $relation_ids);
            }
        }
        if (isset($this->index_business_where)) {
            $reflectionClass = new \ReflectionClass(config('quickcms.business_user_model_class'));
            $business_user = $reflectionClass->newInstance();
            $business_user = $business_user::where('admin_id', Auth::admin()->get()->id)->first();
            if (isset($business_user)) {
                $model = $model->where($this->index_business_where[$business_user->type], $business_user->business_id);
            }
        }
        $order = Input::get('order')['0'];
        $order_sql = $this->index_column[$order['column']] . ' ' . $order['dir'];
        $model = $model->orderByRaw($order_sql);
        if(isset($this->index_multi_column)) {
            $search = Input::get('search')['value'];
            $length = Input::get('length');
            $str_columns = array();
            foreach($this->index_multi_column as $column) {
                $pos = stripos($column, ' as ');
                if($pos) {
                    $column = substr($column, 0, $pos);
                }
                $str_columns[] = $column;
            }
            $str_column = implode(',', $str_columns);
            self::setCurrentPage();
            $joins = $this->index_multi_join;
            foreach($joins as $join) {
                $model = $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $paginate = $model->select($this->index_multi_column)
                ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
                ->paginate($length);
            $ret = self::queryPage($this->index_column, $paginate);
        } else {
            $ret = self::simplePage($this->index_column, $model);
        }
        if($this->index_column_format) {
            foreach($this->index_column_format as $format_key => $format_value) {
                foreach($ret['data'] as $ret_key => $ret_value) {
                    if($format_value['type'] == 'amount') {
                        $ret['data'][$ret_key][$format_value['column']] /= 100;
                        continue;
                    }
                    if($format_value['type'] == 'date') {
                        if(is_numeric($ret_value[$format_value['column']])) {
                            $ret['data'][$ret_key][$format_value['column']] = date($format_value['format'], $ret_value[$format_value['column']]);
                        } else {
                            $ret['data'][$ret_key][$format_value['column']] = date($format_value['format'], strtotime($ret_value[$format_value['column']]));
                        }
                        continue;
                    }
                    if($format_value['type'] == 'time') {
                        if(is_numeric($ret_value[$format_value['column']])) {
                            $ret['data'][$ret_key][$format_value['column']] = gmstrftime($format_value['format'], $ret_value[$format_value['column']]);
                        } else {
                            $ret['data'][$ret_key][$format_value['column']] = gmstrftime($format_value['format'], strtotime($ret_value[$format_value['column']]));
                        }
                        continue;
                    }
                    if($format_value['type'] == 'function') {
                        $ret['data'][$ret_key][$format_value['column']] = $format_value['value']($ret_value[$format_value['column']]);
                        continue;
                    }
                }
            }
        }
        return Response::json($ret);
    }

    /**
     * 列表
     * @param $custom_id
     * @return mixed
     */
    public function index($custom_id = null)
    {
        $message = Session::get('message');
        $selector_data = [];
        if(isset($this->index_column_rename)) {
            foreach ($this->index_column_rename as $key => $column_name) {
                if ($column_name['type'] == 'selector') {
                    $selector = Selector::where('enum_key', $column_name['param'])->first();
                    $tmp_data = SelectorController::parseSelector($selector->type, $selector->enum_value);
                    $selector_data[$key] = $tmp_data;
                }
            }
        }
        if (isset($custom_id)) {
            $back_url = $this->custom_id_back_url;
            if (isset($this->custom_id_relation_column)) {
                $model = $this->model;
                $custom_data = $model::find($custom_id);
                $column = $this->custom_id_relation_column;
                if (isset($custom_data)) {
                    $back_url = str_replace('{custom_id}', $custom_data->$column, $this->custom_id_back_url);
                }
            }

        }
        $this->curd_action = GeneralUtil::curdAction($this->curd_action);
        $data = array(
            'index_column_name' => $this->index_column_name,
            'index_column_rename' => $this->index_column_rename,
            'selector_data' => $selector_data,
            'route_name' => $this->route_name,
//            'route_path' => '/' . str_replace('{custom_id}', $custom_id, Route::getCurrentRoute()->getPath()),
            'route_path' => $this->route_path,
            'model_name' => $this->model_name,
            'actions' => $this->actions,
            'curd_action' => $this->curd_action,
            'index_column' => $this->index_column,
            'message' => $message,
            'detail_style' => isset($this->detail_style) ? $this->detail_style : null,
            'custom_id_back_url' => isset($back_url) ? $back_url : null,
            'is_permission' => $this->is_permission,
            'table_sort' => $this->table_sort,
            'table_action_line' => $this->table_action_line,
            'table_column_width' => $this->table_column_width,
            'table_order' => $this->table_order,
        );
        $column_names = GeneralUtil::queryComment($this->model);
        $data['column_names'] = $column_names;
        if (isset($custom_id)) {
            $data['custom_id'] = $custom_id;
        }
        return View::make('backend::generals.index', $data);
    }

    /**
     * 删除记录
     * @param $custom_id
     * @param $id
     * @return int
     */
    public function destroy($custom_id = null, $id = null) {
        $model = $this->model;
        if($model::destroy(isset($id) ? $id : $custom_id)) {
            $result = true;
        } else {
            $result = false;
        }
        return Response::json($result);
    }

    /**
     * 添加记录
     * @param $custom_id
     * @return mixed
     */
    public function create($custom_id = null) {
        $model_data = $this->model;
        $data = self::getEditData($model_data, $custom_id);
        $selectors = $data['selectors'];
        $selector_data = [];
        foreach ($selectors as $k => $v) {
            $selector = Selector::where('enum_key', $v)->first();
            $tmp_data = SelectorController::parseSelector($selector->type, $selector->enum_value);
            $tmp_data = (array)json_decode($tmp_data);
            $temp = [];
            foreach ($tmp_data as $key=>$value) {
                if (!isset($temp[$selector->default_value])) {
                    $temp[$selector->default_value] = $selector->default_key;
                }
                $temp[$key] = $value;
            }
            $selector_data[$v] = $temp;
        }
        $data['selector_data'] = $selector_data;
        return View::make('backend::generals.create', $data);
    }

    /**
     * 详情
     * @param $id
     * @return mixed
     */
    public function show($id) {
        $model = $this->model;
        if(is_null($this->detail_multi_join)) {
            $data = $model;
        } else {
            $joins = $this->detail_multi_join;
            foreach($joins as $join) {
                $model = $model->leftJoin($join[0], $join[1], $join[2], $join[3]);
            }
            $data = $model->select($this->detail_multi_column);
        }
        $data = $data->find($id);
        $columns = $this->detail_column;
        $detail_column_name = $this->detail_column_name;
        $renames = $this->detail_column_rename;
        $rename_keys = isset($renames) ? array_keys($renames) : array();
        $selector_data = [];
        if(isset($renames)) {
            foreach($renames as $key => $column_name) {
                if($column_name['type'] == 'selector') {
                    $selector = Selector::where('enum_key', $column_name['param'])->first();
                    $selector_json =  SelectorController::parseSelector($selector->type, $selector->enum_value);
                    $selector_data[$key] = json_decode($selector_json, true);
                }
                if ($column_name['type'] == 'language') {
                    $reflectionClass = new \ReflectionClass(config('quickcms.language_model_class'));
                    $language_resource = $reflectionClass->newInstance();
                    $language_resource_data = $language_resource::where('key', $data->$key)->get();
                }
            }
        }
        $column_names = GeneralUtil::queryComment($this->model);
        $data['column_names'] = $column_names;
        $detail_filter_empty = $this->detail_filter_empty;
        return view('backend::generals.detail', compact('data', 'columns', 'detail_column_name', 'column_names', 'renames',
            'rename_keys', 'selector_data', 'language_resource_data', 'detail_filter_empty'));
    }

    /**
     * 保存记录
     * @param $custom_id
     * @return mixed
     */
    public function store($custom_id = null) {
        $data = Input::all();
        foreach($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(',', $value);
            }
        }
        if (isset($data['_token'])) {
            unset($data['_token']);
        }

        $model = $this->model;
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = implode(',', $value);
            }
        }
        if (isset($data['language_hidden'])) {
            $reflectionClass = new \ReflectionClass(config('quickcms.language_model_class'));
            $language_resource = $reflectionClass->newInstance();
        }
        if (isset($data['id'])) {
            $update_model = $model::find($data['id']);
            if (isset($data['language_hidden'])) {
                $language_hidden = explode(',', $data['language_hidden']);
                foreach ($language_hidden as $hidden_key => $hidden_value) {
                    foreach (config('quickcms.language') as $lang_key => $lang_value) {
                        $language_column = $hidden_value . '_' . $lang_key;
                        $language_resource_data = $language_resource::where('key', $update_model->$hidden_value)->where('language', $lang_key)->first();
                        $language_resource_data->value = $data[$language_column];
                        $language_resource_data->save();
                        if ($lang_key == 'zh' && $hidden_key == 0) {
                            $data = array_add($data, $hidden_value . '_resource', $data[$language_column]);
                        }
                        $data = array_except($data, [$language_column]);
                    }
                }
                $data = array_except($data, ['language_hidden']);
            }
            $result = $update_model->update($data);
        } else {
            if (isset($data['language_hidden'])) {
                $time = time();
                $language_hidden = explode(',', $data['language_hidden']);
                foreach ($language_hidden as $hidden_key => $hidden_value) {
                    $key = with($model)->getTable() . '_' . $hidden_value . '_' . $time;
                    foreach (config('quickcms.language') as $lang_key => $lang_value) {
                        $language_column = $hidden_value . '_' . $lang_key;
                        $language_resource::create(array(
                            'key' => $key,
                            'value' => $data[$language_column],
                            'language' => $lang_key,
                        ));
                        if ($lang_key == 'zh' && $hidden_key == 0) {
                            $data = array_add($data, $hidden_value . '_resource', $data[$language_column]);
                        }
                        $data = array_except($data, [$language_column]);
                    }
                    $data = array_add($data, $hidden_value, $key);
                }
            }
            $data = array_except($data, ['language_hidden']);
            $result = $model::create($data);
        }
        $message['result'] = $result ? true : false;
        $message['content'] = $message['result'] ? '操作成功' : '操作失败';
        if (isset($this->edit_redirect_location)) {
            return Redirect::to(str_replace('{id}', $data['id'], $this->edit_redirect_location))->with('message', $message);
        }
        if (isset($custom_id)) {
            $route_path = str_replace('{custom_id}', $custom_id, Route::getCurrentRoute()->getPath());
            $route_path = str_replace('/create', '', $route_path);
            $route_path = str_replace('/edit', '', $route_path);
            return Redirect::to($route_path)->with('message', $message);
        }
        return Redirect::to('admin/' . $this->route_name)->with('message', $message);
    }

    /**
     * 编辑记录
     * @param $custom_id
     * @param $id
     * @return mixed
     */
    public function edit($custom_id = null, $id = null) {
        $model = $this->model;
        if (!isset($custom_id) && !isset($id)) {
            $reflectionClass = new \ReflectionClass(config('quickcms.business_user_model_class'));
            $business_user = $reflectionClass->newInstance();
            $business_user = $business_user::where('admin_id', Auth::admin()->get()->id)->first();
            $id = $business_user->business_id;
        }
        $model_data = $model::find(isset($id) ? $id : $custom_id);
        if (!isset($id)) {
            $custom_id = null;
        }
        $data = self::getEditData($model_data, $custom_id);
        $selectors = $data['selectors'];
        $selector_data = [];
        foreach ($selectors as $k => $v) {
            $selector = Selector::where('enum_key', $v)->first();
            $tmp_data = SelectorController::parseSelector($selector->type, $selector->enum_value);
            $tmp_data = (array)json_decode($tmp_data);
            $temp = [];
            foreach ($tmp_data as $key=>$value) {
                if (!isset($temp[$selector->default_value])) {
                    $temp[$selector->default_value] = $selector->default_key;
                }
                $temp[$key] = $value;
            }
            $selector_data[$v] = $temp;
        }
        $data['selector_data'] = $selector_data;
        if (isset($business_user)) {
            $data['business_user'] = true;
        }
        $message = Session::get('message');
        $data['message'] = $message;
        return View::make('backend::generals.create', $data);
    }

    /**
     * 改变状态
     * @param $id
     * @return mixed
     */
    public function changeStatus($id) {
        $model = $this->model;
        $model_data = $model::find($id);
        $data = Input::all();
        foreach ($data as $data_key => $data_val) {
            if ($data_val == 'now') {
                $data_val = date('Y-m-d H:i:s', time());
            }
            if ($data_val == 'admin_id') {
                $data_val = Auth::admin()->get()->id;
            }
            $model_data->$data_key = $data_val;
        }
        if ($model_data->save()) {
            $ret = true;
        } else {
            $ret = false;
        }
        return $ret ? 1 : 0;
    }

    private function getEditData($model_data, $custom_id = null) {
        $image_config = false;
        $images = array();
        $selectors = [];
        foreach ($this->edit_column_detail as $k => $v) {
            if (!isset($v['type'])) {
                continue;
            }
            if ($v['type'] == 'image') {
                $image_config = true;
                $v['name'] = $k;
                $images[] = $v;
            }
            if ($v['type'] == 'selector') {
                $selectors[] = $v['selector_key'];
            }
            if ($v['type'] == 'file') {
                $file_config = true;
                $v['file_name'] = $k;
                $v['model'] = $model_data;
                $files[] = $v;
            }
            if ($v['type'] == 'language') {
                $reflectionClass = new \ReflectionClass(config('quickcms.language_model_class'));
                $language_resource = $reflectionClass->newInstance();
                $language_resource_data = $language_resource::where('key', $model_data->$k)->get();
            }
            if ($v['type'] == 'editor' && isset($v['language'])) {
                $reflectionClass = new \ReflectionClass(config('quickcms.language_model_class'));
                $language_resource = $reflectionClass->newInstance();
                $language_resource_editor_data = $language_resource::where('key', $model_data->$k)->get();
            }
        }
        $column_names = GeneralUtil::queryComment($this->model);
        $data['column_names'] = $column_names;
//        $route_path = str_replace('{custom_id}', $custom_id, Route::getCurrentRoute()->getPath());
//        $route_path = str_replace('/create', '', $route_path);
//        $route_path = str_replace('/edit', '', $route_path);
//        $route_path = str_replace('/{id}', '', $route_path);
        if (isset($this->edit_hidden_business_id)) {
            $reflectionClass = new \ReflectionClass(config('quickcms.business_user_model_class'));
            $business_user = $reflectionClass->newInstance();
            $business_user = $business_user::where('admin_id', Auth::admin()->get()->id)->first();
            $this->edit_hidden_business_id['value'] = $business_user->business_id;
        }
        $data = array(
            'route_name' => $this->route_name,
//            'route_path' => '/' . $route_path,
            'route_path' => $this->route_path,
            'model_name' => $this->model_name,
            'column_names' => $column_names,
            'edit_column' => $this->edit_column,
            'edit_column_name' => $this->edit_column_name,
            'edit_column_detail' => $this->edit_column_detail,
            'edit_hidden' => $this->edit_hidden,
            'edit_editor' => $this->edit_editor,
            'model_data' => $model_data,
            'image_config' => $image_config,
            'images' => $images,
            'selectors' => $selectors,
            'file_config' => isset($file_config) ? true : false,
            'files' => isset($files) ? $files : null,
            'language' => config('quickcms.language'),
            'language_resource' => isset($language_resource_data) ? $language_resource_data : null,
            'language_resource_editor' => isset($language_resource_editor_data) ? $language_resource_editor_data : null,
            'edit_column_label' => $this->edit_column_label,
            'custom_id_relation_column' => $this->custom_id_relation_column,
            'custom_id' => isset($custom_id) ? $custom_id : null,
            'edit_hidden_business_id' => $this->edit_hidden_business_id,
        );
        return $data;
    }
}