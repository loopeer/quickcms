<?php

namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Loopeer\QuickCms\Models\Backend\System;

class SystemController extends BaseController
{
    public function setting()
    {
        $title = System::firstOrCreate(['key' => 'title']);
        $build = System::firstOrCreate(['key' => 'build']);
        $app_review = System::firstOrCreate(['key' => 'app_review']);
        $android_download = System::firstOrCreate(['key' => 'android_download']);
        $logo = System::firstOrCreate(['key' => 'logo']);
        return view('backend::system.setting', compact('title', 'build', 'app_review',
            'android_download', 'logo'));
    }

    public function saveSetting(Request $request)
    {
        $data = $request->all();
        collect($data)->each(function ($value, $key) {
            System::where('key', $key)->update(['value' => $value]);
        });
        $message = ['result' => true, 'content' => '成功保存系统设置'];
        return redirect('/admin/systems')->with('message', $message);
    }
}
