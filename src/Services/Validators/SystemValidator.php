<?php
/**
 * Created by PhpStorm.
 * User: yongbin
 * Date: 15/1/11
 * Time: 下午4:40
 */
namespace Loopeer\QuickCms\Services\Validators;

class SystemValidator extends Validator {

    public static $feedbackRules = [
        'content' => 'required|max:200',
    ];

    public static $checkVersionRules = [
        'version_code' => 'required',
        'platform' => 'required',
    ];

    public static $registerPushRules = [
        'app_channel_id' => 'required',
        'app_user_id' => 'required',
    ];

    public static $labelRules = [
        'label_type' => 'required',
    ];
}