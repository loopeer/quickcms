<?php
/**
 * Created by PhpStorm.
 * User: yongbin
 * Date: 16/05/16
 * Time: 下午4:40
 */
namespace Loopeer\QuickCms\Services\Validators;

use Loopeer\QuickCms\Services\Validators\Validator;

class AccountValidator extends Validator {

    public $quickLoginRules = [
        'union_id' => 'required',
        'register_way' => 'required',
        'nickname' => 'required',
    ];

    public $avatarRules = [
        'avatar' => 'required'
    ];

    public $detailRules = [
        'scan_account_id' => 'required'
    ];

    public $loginRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
        'password' => 'required',
    ];

    public $registerRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
        'captcha' => 'required',
        'password' => array('required', 'min:6', 'max:16'),
        'nickname' => 'required',
    ];

    public $captchaRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/')
    ];

    public $forgetPwdRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
        'captcha' => 'required',
        'password' => array('required', 'min:6', 'max:16')
    ];

    public $updatePwdRules = [
        'old_password' => array('required'),
        'password' => array('required', 'min:6', 'max:16')
    ];

    public $updatePhoneRules = [
        'contact_phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
        'captcha' => 'required',
    ];
}
