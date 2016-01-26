<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 2015/9/24
 * Time: 12:14
 */

namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Loopeer\QuickCms\Services\Push\BPushService;
use Session;
use Loopeer\QuickCms\Models\Pushes;
use Input;
use Log;
use Response;

class PushesController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:admin.pushes');
        parent::__construct();
    }

    public function search() {
        $ret = self::simplePage(['id', 'account_id', 'app_channel_id', 'platform', 'updated_at'], new Pushes());
        return Response::json($ret);
    }

    public function index() {
        $message = Session::get('message');
        return view('backend::pushes.index', compact('message'));
    }

    public function batch() {
        return view('backend::pushes.batch');
    }

    public function save() {
        $content = Input::get('content');
        $account_ids = Input::get('account_ids');
        $push = new BPushService();
        $push->pushBatchMessage(explode(',', $account_ids), $content, ['notice_type' => 1]);
        $res = array('result' => true, 'content' => '推送成功');
        return $res;
    }
}