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
use Request;
use Loopeer\QuickCms\Services\Validators\SystemValidator;
use Loopeer\QuickCms\Models\Api\Pushes;

class PushesController extends BaseController {

    public function submit() {
        $account_id = Input::get('account_id');
        $app_user_id = Input::get('app_user_id');
        $app_channel_id = Input::get('app_channel_id');
        $platform = Request::header('platform');
        $validation = new SystemValidator();
        if (!$validation->passes(SystemValidator::$registerPushRules)) {
            return ApiResponse::validation($validation);
        }
        $push = Pushes::select('id', 'account_id', 'app_channel_id', 'app_user_id', 'platform')
            ->where('account_id', $account_id)
            ->first();
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

}