<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/22
 * Time: 下午6:00
 */
namespace Loopeer\QuickCms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Loopeer\QuickCms\Models\Api\Feedback;
use Loopeer\QuickCms\Models\Api\Pushes;
use Loopeer\QuickCms\Models\Api\System;
use Loopeer\QuickCms\Models\Backend\Document;
use Loopeer\QuickCms\Models\Backend\Version;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;
use Loopeer\QuickCms\Services\Validators\SystemValidator as SystemValidator;
use Cache;

class SystemController extends BaseController {

    protected $validation;

    public function __construct(SystemValidator $validation) {
        $this->validation = $validation;
    }

    /**
     * 初始化参数
     * @return mixed
    */
    public function initialize(Request $request)
    {
        $upToken = QiniuUtil::buildUpToken();
        $version_code = $request->header('build');
        $appstore_reviewing = false;
        Cache::forget('review_system');
        Cache::forget('build_system');
        $review_system = Cache::rememberForever('review_system', function () {
            return System::where('key', 'app_review')->first();
        });
        $build_system = Cache::rememberForever('build_system', function () {
            return System::where('key', 'build')->first();
        });
        if (count($review_system) > 0 && $review_system->value == 'true') {
            if (count($build_system) > 0 && $build_system->value == $version_code) {
                $appstore_reviewing = true;
            }
        }
        $configs = array(
            'up_token' => $upToken,
            'appstore_reviewing' => $appstore_reviewing,
            'custom_data' => config('quickApi.custom_data', [])
        );
        return ApiResponse::responseSuccess($configs);
    }

    /**
     * 注册推送设备
     * @return mixed
     * @throws \Exception
     */
    public function registerPush(Request $request) {
        if (!$this->validation->passes($this->validation->registerPushRules)) {
            return ApiResponse::validation($this->validation);
        }
        $pushes = Pushes::firstOrNew(['account_id' => $request->header('account-id')]);
        $pushes->app_user_id = $request->get('app_user_id');
        $pushes->app_channel_id = $request->get('app_channel_id');
        $pushes->platform = $request->header('platform');
        $pushes->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * 反馈
     * @return mixed
     */
    public function feedback(Request $request) {
        if (!$this->validation->passes($this->validation->feedbackRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account_id = $request->header('account-id');
        $content = $request->get('content');
        $contact = $request->get('contact');
        $version = $request->header('version_name');
        $versionCode = $request->header('build');
        $deviceId = $request->header('device_id');
        $channelId = $request->header('channel_id', 'iOS');
        $feedback = new Feedback();
        $feedback->account_id = $account_id;
        $feedback->content = $content;
        $feedback->version = $version . '-' . $versionCode;
        $feedback->version_code = $versionCode;
        $feedback->device_id = $deviceId;
        $feedback->channel_id = $channelId;
        if (!is_null($contact)) {
            $feedback->contact = $contact;
        }
        $result = $feedback->save();
        if ($result) {
            return ApiResponse::responseSuccess();
        } else {
            return ApiResponse::responseFailure();
        }
    }

    /**
     * 版本信息
     * @return mixed
     */
    public function version() {
        $version = Version::where('platform', 0)->where('status', Version::PUBLISH_STATUS)->orderBy('version_code', 'desc')->first();
        return ApiResponse::responseSuccess($version);
    }

    /**
     * 文档html
     * @param $key
     * @return mixed
     */
    public function document($key)
    {
        $document = Document::where('key', $key)->first();
        if ($document) {
            return view('backend::app.document', compact('document'));
        } else {
            return abort(404, 'Document not found.');
        }
    }
}
