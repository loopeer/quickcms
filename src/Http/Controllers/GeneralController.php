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

use Illuminate\Support\Facades\Auth;
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
use Log;

class GeneralController extends BaseController
{

    protected $model_class;
    protected $model_name;
    protected $route_name;
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

    public function __construct(Request $request) {
        try {
            $this->route_name = preg_replace('/(\/)|(admin)|(create)|(search)|(edit)|(changeStatus)|(detail)|{\w*}/', '',
                Route::getCurrentRoute()->getPath());
            GeneralUtil::filterOperationPermission($request, null, $this->route_name);
            $general_name = 'generals.' . $this->route_name . '.';
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
            //foreach ($middleware as $value) {
            //$this->middleware('auth.permission:' . implode(',', $middleware));
            //}
        } catch (Exception $e) {
            Log::info($e->getMessage());
//            App::abort('403');
        }
        parent::__construct();
    }

    /**
     * 搜索
     * @param $dialog_id
     * @return mixed
     */
    public function search($dialog_id = null)
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
        if(isset($this->sort)) {
            foreach ($this->sort as $sort) {
                $model = $model->orderBy($sort[0], $sort[1]);
            }
        }
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
                            $ret['data'][$ret_key][$format_value['column']] = date($format_value['format'], time($ret_value[$format_value['column']]));
                        }
                        continue;
                    }
                    if($format_value['type'] == 'time') {
                        if(is_numeric($ret_value[$format_value['column']])) {
                            $ret['data'][$ret_key][$format_value['column']] = gmstrftime($format_value['format'], $ret_value[$format_value['column']]);
                        } else {
                            $ret['data'][$ret_key][$format_value['column']] = gmstrftime($format_value['format'], time($ret_value[$format_value['column']]));
                        }
                        continue;
                    }
                }
            }
        }
        return Response::json($ret);
    }

    /**
     * 列表
     * @return mixed
     */
    public function index()
    {
        $message = Session::get('message');
        $selector_data = [];
        if(isset($this->index_column_rename)) {
            foreach($this->index_column_rename as $key => $column_name) {
                if($column_name['type'] == 'selector') {
                    $selector = Selector::where('enum_key', $column_name['param'])->first();
                    $tmp_data = SelectorController::parseSelector($selector->type, $selector->enum_value);
                    $selector_data[$key] = $tmp_data;
                }
            }
        }

        $this->curd_action = GeneralUtil::curdAction($this->curd_action);
        $data = array(
            'index_column_name' => $this->index_column_name,
            'index_column_rename' => $this->index_column_rename,
            'selector_data' => $selector_data,
            'route_name' => $this->route_name,
            'model_name' => $this->model_name,
            'actions' => $this->actions,
            'curd_action' => $this->curd_action,
            'index_column' => $this->index_column,
            'message' => $message,
            'detail_style' => isset($this->detail_style) ? $this->detail_style : null,
        );
        $column_names = GeneralUtil::queryComment($this->model);
        $data['column_names'] = $column_names;
        return View::make('backend::generals.index', $data);
    }

    /**
     * 删除记录
     * @param $id
     * @return int
     */
    public function destroy($id) {
        $model = $this->model;
        if($model::destroy($id)) {
            $result = true;
        } else {
            $result = false;
        }
        return Response::json($result);
    }

    /**
     * 添加记录
     * @return mixed
     */
    public function create() {
        $model_data = $this->model;
        $data = self::getEditData($model_data);
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
                if ($column_name['type'] == 'html' && $column_name['language']) {
                    $reflectionClass = new \ReflectionClass(config('quickcms.language_model_class'));
                    $language_resource = $reflectionClass->newInstance();
                    $language_resource_editor_data = $language_resource::where('key', $data->$key)->get();
                }
            }
        }
        $column_names = GeneralUtil::queryComment($this->model);
        $data['column_names'] = $column_names;
        return view('backend::generals.detail', compact('data', 'columns', 'detail_column_name', 'column_names', 'renames',
            'rename_keys', 'selector_data', 'language_resource_data', 'language_resource_editor_data'));
    }

    /**
     * 保存记录
     * @return mixed
     */
    public function store() {
        $data = Input::all();
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
            return Redirect::to($this->edit_redirect_location)->with('message', $message);
        }
        return Redirect::to('admin/' . $this->route_name)->with('message', $message);
    }

    /**
     * 编辑记录
     * @param $id
     * @return mixed
     */
    public function edit($id) {
        $model = $this->model;
        if (!isset($id)) {
            $reflectionClass = new \ReflectionClass(config('quickcms.business_user_model_class'));
            $business_user = $reflectionClass->newInstance();
            $business_user = $business_user::where('admin_id', Auth::admin()->get()->id)->first();
            $id = $business_user->business_id;
        }
        $model_data = $model::find($id);
        $data = self::getEditData($model_data);
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
            $model_data->$data_key = $data_val;
        }
        if ($model_data->save()) {
            $ret = true;
        } else {
            $ret = false;
        }
        return $ret ? 1 : 0;
    }

    private function getEditData($model_data) {
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
        $data = array(
            'route_name' => $this->route_name,
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
        );
        return $data;
    }
}
