<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-10-22
 * Time: 下午1:54
 */
namespace Loopeer\QuickCms\Http\Controllers;

class DashboardController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:admin.dashboard');
        parent::__construct();
    }

    /**
     * 数据面板
     * @return \Illuminate\View\View
     */
    public function index(){
        return view('backend::dashboard');
    }

}