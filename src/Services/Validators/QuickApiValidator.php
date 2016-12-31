<?php
/**
 * Created by PhpStorm.
 * User: yongbin
 * Date: 16/05/16
 * Time: 下午4:40
 */
namespace Loopeer\QuickCms\Services\Validators;

class QuickApiValidator extends Validator {

    public $quickLoginRules = [
        'union_id' => 'required',
        'register_way' => 'required',
        'nickname' => 'required',
    ];

    public $avatarRules = [
        'avatar' => 'required'
    ];

    public $loginRules = [
        'phone' => array('sometimes', 'required', 'regex:/^1[0-9]\d{9}$/'),
        'email' => array('sometimes', 'required', 'email'),
        'password' => 'required',
    ];

    public $registerRules = [
        'phone' => array('sometimes', 'required', 'regex:/^1[0-9]\d{9}$/'),
        'email' => array('sometimes', 'required', 'email'),
        'password' => array('required', 'min:6', 'max:16'),
        'captcha' => 'required',
    ];

    public $phoneRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
    ];

    public $captchaRules = [
        'phone' => array('sometimes', 'required', 'regex:/^1[0-9]\d{9}$/'),
        'email' => array('sometimes', 'required', 'email'),
    ];

    public $loginByCaptchaRules = [
        'phone' => array('required', 'regex:/^1[0-9]\d{9}$/'),
        'captcha' => 'required',
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
