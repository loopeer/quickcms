<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 15/12/22
 * Time: 上午10:21
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Redirect;
use Session;
use Input;
use View;
use Response;
use DB;
use Loopeer\QuickCms\Models\Selector;

class SelectorController extends BaseController {


    public function index() {
        $message = Session::get('message');
        return View::make('backend::selectors.index', compact('message'));
    }

    public function search() {
        $ret = self::simplePage(['id', 'name', 'enum_key', 'enum_value'], new Selector());
        return Response::json($ret);
    }

    public function create() {
        $selector = new Selector();
        return View::make('backend::selectors.create', compact('selector'));
    }

    public function edit($id) {
        $selector = Selector::find($id);
        return View::make('backend::selectors.create', compact('selector'));
    }

    public function destroy($id) {
        if (Selector::destroy($id)) {
            return array('result' => true, 'content' => '删除成功');
        } else {
            return array('result' => false, 'content' => '删除失败');
        }
    }

    public function preview() {
        $value = Input::get('data');
        $type = Input::get('type');
        $data = $this->parseSelector($type, $value);
        return $data;
    }

    public function store() {
        $data = Input::all();
        unset($data['_token']);
        unset($data['default_option']);
//        dd($data);
        if (isset($data['id'])) {
            $flag = Selector::find($data['id'])->update($data);
        } else {
            $flag = Selector::create($data);
        }
        if ($flag) {
            $message = array('result' => true, 'content' => '操作成功');
        } else {
            $message = array('result' => true, 'content' => '操作失败');
        }
        return Redirect::to('/admin/selector')->with('message', $message);
    }

    public function checkKey() {
        $enum_key = Input::get('enum_key');
        $count = Selector::where('enum_key', $enum_key)->count();
        if ($count > 0) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public static function parseSelector($type, $value) {
        if ($type == 0) {
            $tmp_data = DB::select($value);
            $result = array();
            foreach ($tmp_data as $k => $data) {
                $data = (array)$data;
                $keys = array_keys($data);
                $result['' . $data[$keys[0]]] = $data[$keys[1]];
            }
        } else {
            $result = $value;
            $result = json_decode($result);
        }
        return json_encode($result);
    }

}