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

use Illuminate\Http\Request;
use Session;
use Response;
use Input;
use View;
use Redirect;

class GeneralController extends BaseController
{

    protected $model_class;
    protected $model_name;
    protected $route_name;

    public function __construct(Request $request) {
        $path = str_replace('admin/', '', $request->path());
        $path = str_replace('/create', '', $path);
        $this->route_name = preg_replace('/\/[0-9]+\/edit/', '', $path);
        $this->model_class = config('general.' . $this->route_name . '_model_class');
        $this->model_name = config('general.' . $this->route_name . '_model_name');
    }

    public function search()
    {
        $ret = self::simplePage(config('general.versions_index_column'), new $this->model_class());
        return Response::json($ret);
    }

    public function index()
    {
        $message = Session::get('message');
        $column_name = config('general.' . $this->route_name . '_index_column_name');
        $route_name = $this->route_name;
        $model_name = $this->model_name;
        $model_class = $this->model_class;
        return view('backend::generals.index', compact('message', 'column_name', 'route_name', 'model_name', 'model_class'));
    }

    /**
     * 删除记录
     * @param $id
     * @return int
     */
    public function destroy($id) {
        $model = $this->getModel();
        $result = $model::destroy($id);
        return $result ? 1 : 0;
    }

    /**
     * 添加记录
     * @return mixed
     */
    public function create() {
        $model = $this->getModel();
        $route_name = $this->route_name;
        return View::make('backend::generals.create', compact('model', 'route_name'));
    }

    public function show() {

    }

    /**
     * 保存记录
     * @return mixed
     */
    public function store() {
        $data = Input::all();
        $model = $this->getModel();
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
        $model = $this->getModel();
        $model_data = $model::find($id);
        $route_name = $this->route_name;
        return View::make('backend::generals.create', compact('route_name', 'model_data'));
    }

    protected function getModel() {
        $model_class = Input::get('model_class', $this->model_class);
        $model_class = str_replace('@', '\\', $model_class);
        $model = new $model_class();
        return $model;
    }
}