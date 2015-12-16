<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 2015/9/24
 * Time: 10:23
 */

namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use View;
use Session;
use Loopeer\QuickCms\Models\Feedback;

class FeedbackController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:maintenance');
        $this->middleware('auth.permission:admin.feedbacks');
        parent::__construct();
    }

    public function search() {
        $ret = self::simplePage(
            [
                'id',
                'account_id',
                'content',
                'contact',
                'version',
                'version_code',
                'device_id',
                'channel_id',
                'created_at',
            ],
            new Feedback()
        );
        return \Response::json($ret);
    }
    public function index() {
        $message = Session::get('message');
        return View::make('backend::feedbacks.index',compact('feedback_list', 'message'));
    }

    public function destroy($feedback_id) {
        $result = Feedback::destroy($feedback_id);
        return $result ? 1 : 0;
    }
}