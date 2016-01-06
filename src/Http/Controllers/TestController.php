<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-12-31
 * Time: 下午3:37
 */

namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Route;
use Session;
use Response;
use Input;
use View;
use Redirect;

class TestController extends BaseController {

    public function detail($id) {
        return view('welcome');
    }

    public function add($id) {
        return view('backend::test.form');
    }

    public function submitAdd() {
        $data = Input::all();
        Log::info($data);
        return Response::json(true);
    }


}