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
use Loopeer\QuickCms\Http\Controllers\SelectorController;

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
    protected $actions;
    protected $curd_action;
    protected $model;
    protected $sort;
    protected $where;
    protected $edit_hidden;
    protected $edit_editor;

    public function __construct() {
        $this->route_name = preg_replace('/(\/)|(admin)|(create)|(search)|(edit)|(changeStatus)|{\w*}/', '',
            Route::getCurrentRoute()->getPath());
        $general_name = 'generals.' . $this->route_name . '.';
        $this->index_column = config($general_name . 'index_column');
        $this->index_column_format = config($general_name . 'index_column_format');
        $this->index_column_name = config($general_name . 'index_column_name');
        $this->index_column_rename = config($general_name . 'index_column_rename', array());
        $this->edit_column = config($general_name . 'edit_column');
        $this->edit_column_name = config($general_name . 'edit_column_name');
        $this->edit_column_detail = config($general_name . 'edit_column_detail');
        $this->model_class = config($general_name . 'model_class');
        $this->model_name = config($general_name . 'model_name');
        $this->actions = config($general_name . 'table_action');
        $this->sort = config($general_name . 'sort');
        $this->where = config($general_name . 'index_where');
        $this->edit_hidden = config($general_name . 'edit_hidden');
        $this->edit_editor = config($general_name . 'edit_editor');
        $this->curd_action = config($general_name . 'curd_action');
        $this->index_multi = config($general_name . 'index_multi');
        $this->index_multi_column = config($general_name . 'index_multi_column');
        $this->index_multi_join = config($general_name . 'index_multi_join');
        $reflectionClass = new \ReflectionClass($this->model_class);
        $this->model = $reflectionClass->newInstance();
        $middleware = config($general_name . 'middleware', array());
        foreach ($middleware as $value) {
            $this->middleware('auth.permission:' . $value);
        }
        parent::__construct();
    }

    /**
     * 搜索
     * @return mixed
     */
    public function search()
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
           $model = $model->orderBy($this->sort[0], $this->sort[1]);
        }
        if($this->index_multi) {
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
                        $ret['data'][$ret_key][$format_value['column']] = date($format_value['format'], time($ret_value[$format_value['column']]));
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
        foreach($this->index_column_rename as $key => $column_name) {
            if($column_name['type'] == 'selector') {
                $selector = Selector::where('enum_key', $column_name['param'])->first();
                $selector_data[$key] = $selector->enum_value;
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
            'message' => $message
        );
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
    public function show() {

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
        if (isset($data['id'])) {
            $result = $model::find($data['id'])->update($data);
        } else {
            $result = $model::create($data);
        }
        $message['result'] = $result ? true : false;
        $message['content'] = $message['result'] ? '操作成功' : '操作失败';

        return Redirect::to('admin/' . $this->route_name)->with('message', $message);
    }

    /**
     * 编辑记录
     * @param $id
     * @return mixed
     */
    public function edit($id) {
        $model = $this->model;
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
        foreach($data as $data_key => $data_val) {
            if($data_val == 'now') {
                $data_val = date('Y-m-d H:i:s', time());
            }
            $model_data->$data_key = $data_val;
        }
        if($model_data->save()) {
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
            $tmp = explode(':', $v['type']);
            if ($tmp[0] == 'selector') {
                $selectors[] = $tmp[1];
            }
        }
        $data = array(
            'route_name' => $this->route_name,
            'model_name' => $this->model_name,
            'edit_column' => $this->edit_column,
            'edit_column_name' => $this->edit_column_name,
            'edit_column_detail' => $this->edit_column_detail,
            'edit_hidden' => $this->edit_hidden,
            'edit_editor' => $this->edit_editor,
            'model_data' => $model_data,
            'image_config' => $image_config,
            'images' => $images,
            'selectors' => $selectors
        );
        return $data;
    }
}