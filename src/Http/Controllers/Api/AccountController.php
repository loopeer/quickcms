<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/5/16
 * Time: 上午8:24
 */
namespace Loopeer\QuickCms\Http\Controllers\Api;

use Carbon\Carbon;
use Loopeer\Lib\Sms\LuoSiMaoSms;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Loopeer\QuickCms\Services\Validators\QuickApiValidator;

class AccountController extends BaseController {

    protected $validation;
    protected $model;

    public function __construct(QuickApiValidator $validation) {
        $this->validation = $validation;
        $reflectionClass = new \ReflectionClass(config('quickcms.account_model_class'));
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * 手机号+密码登陆方式
     * @return mixed
     */
    public function loginByPassword() {
        if (!$this->validation->passes($this->validation->loginRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        $password = Input::get('password');
        // 验证帐号
        $account = $this->model->where('phone', $phone)->first();
        if (is_null($account)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_account_not_exist'));
        }
        // 密码错误
        if (md5($password) != $account->password) {
            return ApiResponse::errorPreCondition(config('quickcms.message_password_error'));
        }
        // 黑名单
        if ($account->status != 0) {
            return ApiResponse::responseFailure(config('quickcms.code_black_account'), config('api.message_black_account'));
        }
        // token
        $token = self::generateToken();
        $account->token = $token;
        $account->save();
        Auth::user()->setUser($account);
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 手机号+验证码登陆方式
     * @return mixed
     * @throws \Exception
     */
    public function loginByCaptcha() {
        if (!$this->validation->passes($this->validation->loginByCaptchaRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        $captcha = Input::get('captcha');
        // 验证码输入错误
        if(self::checkCaptcha($phone, $captcha)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_captcha_error'));
        }
        // 验证帐号
        $account = $this->model->where('phone', $phone)->first();
        // token
        $token = self::generateToken();
        if(is_null($account)) {
            $account = new $this->model;
            $account->phone = $phone;
            $account->token = $token;
            $account->save();
        } else {
            // 黑名单
            if($account->status != 0) {
                return ApiResponse::responseFailure(config('quickcms.code_black_account'), config('quickcms.message_black_account'));
            }
            $account->token = $token;
            $account->save();
        }
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 手机用户注册API
     * @return mixed
     */
    public function register() {
        if (!$this->validation->passes($this->validation->registerRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        $captcha = Input::get('captcha');
        $password = Input::get('password');

        // 该手机号已被注册
        $account_phone = $this->model->where('phone', $phone)->first();
        if (!is_null($account_phone)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_phone_is_register'));
        }
        // 验证码输入错误
        if (self::checkCaptcha($phone, $captcha)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_captcha_error'));
        }
        $token = self::generateToken();
        $account = $this->model->create(array(
            'phone' => $phone,
            'password' => md5($password),
            'token' => $token,
            'status' => 0,
        ));
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 忘记密码API
     * @return mixed
     */
    public function forgetPassword() {
        if (!$this->validation->passes($this->validation->forgetPwdRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        $captcha = Input::get('captcha');
        $password = Input::get('password');
        // 验证码输入错误
        if (self::checkCaptcha($phone, $captcha)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_captcha_error'));
        }
        $account = $this->model->where('phone', $phone)->first();
        if ($account == null) {
            return ApiResponse::errorPreCondition(config('quickcms.message_account_is_not_exist'));
        }
        $account->password = md5($password);
        $account->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * 修改密码API
     * @return mixed
     */
    public function updatePassword() {
        if (!$this->validation->passes($this->validation->updatePwdRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account_id = Input::header('account_id');
        $old_password = Input::get('old_password');

        $password = Input::get('password');
        // 旧密码输入错误
        $account = $this->model->find($account_id);
        if ($account->password != md5($old_password)) {
            return ApiResponse::errorPreCondition(config('quickcms.message_oldPassword_error'));
        }
        $account->password = md5($password);
        $account->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * 获取验证码API
     * @return mixed
     */
    public function captcha() {
        if (!$this->validation->passes($this->validation->phoneRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        if (config('quickcms.sms_api_switch')){
            $captcha = rand(1000, 9999);
            // 发送短信至用户
            $message = sprintf(config('quickcms.sms_captcha'), $captcha);
            $sms = new LuoSiMaoSms(config('quickcms.sms_api_key'));
            $sms->sendSms($phone, $message);
        } else {
            $captcha = '1234';
        }
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put($phone, $captcha, $expiresAt);
        return ApiResponse::responseSuccess();
    }

    /**
     * 个人详情
     * @return mixed
     */
    public function detail() {
        $account = Auth::user()->get();
        return ApiResponse::responseSuccess($account);
    }

    
    /**
     * 验证码是否一致
     * @param string $phone 电话号码
     * @param string $captcha 验证码
     * @return bool
     */
    private function checkCaptcha($phone, $captcha) {
        $captcha_service = Cache::get($phone);
        if ($captcha != $captcha_service) {
            return true;
        }
        return false;
    }

    /**
     * 上传头像
     * @return mixed
     */
    public function avatar() {
        if (!$this->validation->passes($this->validation->avatarRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account_id = Input::header('account_id');
        $avatar = Input::get('avatar');
        $account = $this->model->find($account_id);
        $account->avatar = $avatar;
        $account->save();
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 用户更新
     * @return mixed
     */
    public function update() {
        $account_id = Input::header('account_id');
        $datas = Input::except(['account_id']);
        $account = $this->model->find($account_id)->update($datas);
        return ApiResponse::responseSuccess($account);
    }
    
    /**
     * 获取语音验证码API
     * @return mixed
     */
    public function verify() {
        if (!$this->validation->passes($this->validation->phoneRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = Input::get('phone');
        if(config('quickcms.sms_api_switch')){
            $captcha = rand(1000, 9999);
            $sms = new LuoSiMaoSms(config('quickcms.sms_api_key_verify'));
            // 拨打语音电话至用户
            $sms->sendVerify($phone, $captcha);
        } else {
            $captcha = '1234';
        }
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put($phone, $captcha, $expiresAt);
        return ApiResponse::responseSuccess();
    }

}

