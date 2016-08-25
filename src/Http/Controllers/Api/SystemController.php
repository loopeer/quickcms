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

use Input;
use Loopeer\QuickCms\Models\Version;
use Request;
use Loopeer\QuickCms\Services\Validators\SystemValidator as SystemValidator;
use Loopeer\QuickCms\Models\Pushes;
use Loopeer\QuickCms\Models\Feedback;
use Loopeer\QuickCms\Models\System;
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
    public function initialize()
    {
        $bucket = config('quickcms.qiniu_bucket');
        $accessKey = config('quickcms.qiniu_access_key');
        $secretKey = config('quickcms.qiniu_secret_key');
        $policy = [
            'scope' => $bucket,
            'deadline' => time() + 604800, // 7 * 24 * 3600
        ];
        $mac = new \Qiniu\Mac($accessKey, $secretKey);
        $upToken = $mac->signWithData(json_encode($policy));
        $version_code = Request::header('build');
        $appstore_reviewing = false;
        $review_system = Cache::rememberForever('review_system', function () {
            return System::where('system_key', 'app_review')->first();
        });
        $build_system = Cache::rememberForever('build_system', function () {
            return System::where('system_key', 'build')->first();
        });
        if (count($review_system) > 0 && $review_system->system_value == 1) {
            if (count($build_system) > 0 && $build_system->system_value == $version_code) {
                $appstore_reviewing = true;
            }
        }
        $configs = array(
            'up_token' => $upToken,
            'appstore_reviewing' => $appstore_reviewing,
            'custom_data' => config('quickcms.custom_data', [])
        );
        return ApiResponse::responseSuccess($configs);
    }

    public function registerPush() {
        $account_id = Input::get('account_id');
        $app_user_id = Input::get('app_user_id');
        $app_channel_id = Input::get('app_channel_id');
        $platform = Request::header('platform');
        if (!$this->validation->passes($this->validation->registerPushRules)) {
            return ApiResponse::validation($this->validation);
        }
        $push = Pushes::where('account_id', $account_id)->first();
        if (!isset($push)) {
            $push = new Pushes();
        }
        $push->account_id = $account_id;
        $push->app_channel_id = $app_channel_id;
        $push->app_user_id = $app_user_id;
        $push->platform = $platform;
        $push->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * POST setting/feedback
     * return mixed
     */
    public function feedback() {
        if (!$this->validation->passes($this->validation->feedbackRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account_id = Input::header('account_id');
        $content = Input::get('content');
        $contact = Input::get('contact');
        $version = Request::header('version');
        $versionCode = Request::header('build');
        $deviceId = Request::header('device_id');
        $channelId = Request::header('channel_id');
        $feedback = new Feedback;
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
            $message = config('quickcms.message_feedback_success');
            if(!empty($message)) {
                return ApiResponse::responseSuccessWithMessage($message);
            }
            return ApiResponse::responseSuccess();
        } else {
            return ApiResponse::responseFailure();
        }
    }

    public function version() {
        $version = Version::select('id', 'version_code', 'url', 'message', 'description')->where('platform', 0)->where('status', 1)->orderBy('version_code', 'desc')->first();
        return ApiResponse::responseSuccess($version);
    }

}
