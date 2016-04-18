<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 2015/9/23
 * Time: 17:16
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use View;
use Session;
use Loopeer\QuickCms\Models\ActionLog;
use Response;

class LogController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:maintenance');
        $this->middleware('auth.permission:admin.logs');
        parent::__construct();
    }

    public function search() {
        $ret = self::simplePage(['id', 'user_id', 'content', 'created_at', 'client_ip'], new ActionLog());
        return Response::json($ret);
    }

    public function index() {
        $message = Session::get('message');
        return View::make('backend::logs.index', compact('message'));
    }


    public function destroy($log_id) {
        if (ActionLog::destroy($log_id)) {
            return array('result' => true, 'content' => '删除成功');
        } else {
            return array('result' => false, 'content' => '删除失败');
        }
    }

    public function emptyLogs() {
        ActionLog::truncate();
        $message['result'] = 1;
        $message['content'] = $message['result'] ? '清空日志成功' : '清空日志失败';
        return Redirect::to('admin/actionLogs/')->with('message', $message);
    }
}