<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 2015/9/24
 * Time: 13:43
 */

namespace Loopeer\QuickCms\Http\Controllers;

use App\Http\Controllers\Backend;
use Input;
use View;
use Session;
use Loopeer\QuickCms\Models\System;
use Request;
use Storage;
use Cache;

class SystemController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:maintenance');
        $this->middleware('auth.permission:admin.system');
        parent::__construct();
    }

    public function index() {
        $system = System::findOrNew(1);
        return View::make('backend::systems.index', compact('system'));
    }

    public function store() {
        $title = Input::get('title');
        $build = Input::get('build');
        $app_review = Input::get('app_review');
        $android_download = Input::get('android_download');
        if ($system = System::find(1)) {
        } else {
            $system = new System();
        }
        $system->title = $title;
        $system->build = $build;
        $system->app_review = $app_review;
        $system->android_download = $android_download;
        if ($system->save()) {
            Cache::forever('system', $system);
            if (Cache::has('websiteTitle'))
                Cache::forget('websiteTitle');
            echo 1;
        } else {
            echo 0;
        }
    }

    public function uploadLogo() {

        if (Request::hasFile('logo')) {
            if (Input::file('logo')->isValid()) {
                $size = Input::file('logo')->getSize();
                $extension = Input::file('logo')->getClientOriginalExtension();
                $mime = Input::file('logo')->getMimeType();
                if ($size > 500*1024) {
                    echo 1;//尺寸太大
                    exit;
                }
                if ($extension != 'png') {
                    if ($mime != 'image/png') {
                        echo 2;
                        exit;
                    }
                    echo 2;//格式不正确
                    exit;
                }
                Input::file('logo')->move(base_path().'/public/loopeer/quickcms/img', 'logo.png');
                echo 1;
            } else {
                echo 0;//文件不合法
            }
        }

    }

    public function updateCode() {
        $base_path = base_path();
        $res = shell_exec('cd ' . $base_path . ' && git pull && composer dump-autoload');
        return $res;
    }
}