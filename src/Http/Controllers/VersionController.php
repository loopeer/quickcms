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
     * 获取版本列表数据
     * @return mixed
     */
    public function search() {
        $ret = self::simplePage(
            [
                'id',
                'platform',
                'version_code',
                'version',
                'url',
                'message',
                'description',
                'status',
                'published_at',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            new Version()
        );
        return Response::json($ret);
    }

    /**
     * 版本列表
     * @return mixed
     */
    public function index() {
        $message = Session::get('message');
        return View::make('backend::versions.index', compact('message', 'version_list'));
    }

    /**
     * 版本删除
     * @param $version_id
     * @return int
     */
    public function destroy($version_id) {
        $result = Version::destroy($version_id);
        return $result ? 1 : 0;
    }

    /**
     * 添加版本
     * @return mixed
     */
    public function create() {
        $version = new Version();
        return View::make('backend::versions.create',compact('version'));
    }

    public function show() {

    }

    /**
     * 保存版本信息
     * @return mixed
     */
    public function store() {
        $data = Input::all();
        Log::info($data);
        if (isset($data['id'])) {
            $version = Version::find($data['id']);
        } else {
            $version = new Version();
        }

        $result = $this->saveVersion($version, $data);

        $message['result'] = $result ? true : false;
        $message['content'] = $message['result'] ? '操作成功' : '操作失败';

        return Redirect::to('admin/versions')->with('message', $message);
    }

    /**
     * 编辑版本
     * @param $version_id
     * @return mixed
     */
    public function edit($version_id) {
        $version = Version::find($version_id);

        return View::make('backend::versions.create', array('version' => $version));
    }

    /**
     * 保存版本信息
     * @param $version
     * @param $data
     * @return mixed
     */
    private function saveVersion($version, $data) {

        $version->platform = $data['platform'];
        $version->version_code = $data['version_code'];
        $version->version = $data['version'];
        $version->url = $data['url'];
        $version->message = $data['message'];
        $version->description = $data['description'];
        $result = $version->save();
        return $result;
    }

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