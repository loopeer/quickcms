<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 16-7-25
 * Time: 下午6:13
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Input;
use Log;
use Session;
use Response;
use DB;
use Redirect;
use Auth;
use Hash;
use Cache;

class SystemController extends GeneralController {

    public function store($custom_id = null) {
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
            $system = $model::find($data['id']);
            $result = $system->update($data);
            if ($system->system_key == 'app_review') {
                Cache::forever('review_system', $system);
            } elseif ($system->system_key == 'build') {
                Cache::forever('build_system', $system);
            }
        } else {
            $result = $model::create($data);
        }
        Cache::forget("system_config");
        $message['result'] = $result ? true : false;
        $message['content'] = $message['result'] ? '操作成功' : '操作失败';

        return Redirect::to('admin/' . $this->route_name)->with('message', $message);
    }
}