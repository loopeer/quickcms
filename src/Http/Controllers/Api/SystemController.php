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
use Input;
use Loopeer\QuickCms\Models\Document;
use Loopeer\QuickCms\Models\Version;
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

    /**
     * 注册推送设备
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function registerPush(Request $request) {
        $accountId = $request->header('account_id');
        $data = array(
            'account_id' => $accountId,
            'app_user_id' => $request->app_user_id,
            'app_channel_id' => $request->app_channel_id,
            'platform' => $request->header('platform'),
        );
        if (!$this->validation->passes($this->validation->registerPushRules)) {
            return ApiResponse::validation($this->validation);
        }
        $push = Pushes::where('account_id', $accountId)->first();
        if (isset($push)) {
            $push->update($data);
        } else {
            Pushes::create($data);
        }
        return ApiResponse::responseSuccess();
    }

    /**
     * 反馈
     * @param $request
     * @return mixed
     */
    public function feedback(Request $request) {
        if (!$this->validation->passes($this->validation->feedbackRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account_id = $request->header('account_id');
        $content = $request->content;
        $contact = $request->contact;
        $version = $request->header('version_name');
        $versionCode = $request->header('build');
        $deviceId = $request->header('device_id');
        $channelId = $request->header('channel_id');
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

    /**
     * 版本信息
     * @return mixed
     */
    public function version() {
        $version = Version::where('platform', 0)->where('status', 1)->orderBy('version_code', 'desc')->first();
        return ApiResponse::responseSuccess($version);
    }

    /**
     * 文档html
     * @param $key
     * @return mixed
     */
    public function document($key)
    {
        $document = Document::where('document_key', $key)->first();
        if ($document) {
            return view('backend::app.document', compact('document'));
        } else {
            return abort(404, 'Document not found.');
        }
    }
}
