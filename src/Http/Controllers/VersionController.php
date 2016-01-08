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
use View;
use Session;
use Loopeer\QuickCms\Models\Version;
use Input;
use Log;
use Response;

class VersionController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:maintenance');
        $this->middleware('auth.permission:admin.versions');
        parent::__construct();
    }

    /**
     * 改变版本状态
     * @return mixed
     */
    public function changeStatus($id) {
        $version = Version::find($id);
        if($version->status == 0) {
            $version->status = 1;
        } elseif($version->status == 1) {
            $version->status = 0;
        }
        if($version->save()) {
            $ret = true;
        } else {
            $ret = false;
        }

        return $ret ? 1 : 0;
    }
}