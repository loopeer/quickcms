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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Loopeer\Lib\Sms\LuoSiMaoSms;
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
     * 密码登陆方式
     * @param $request
     * @return mixed
     */
    public function loginByPassword(Request $request) {
        if (!$this->validation->passes($this->validation->loginRules)) {
            return ApiResponse::validation($this->validation);
        }
        // 验证帐号
        if (isset($request->phone)) {
            $query = $this->model->where('phone', $request->phone);
        } else {
            $query = $this->model->where('email', $request->email);
        }
        $account = $query->first();
        if (is_null($account)) {
            return ApiResponse::errorPreCondition(trans('api::messages.account_not_exist'));
        }
        // 密码错误
        if (md5($request->password) != $account->password) {
            return ApiResponse::errorPreCondition(trans('api::messages.password_error'));
        }
        // 黑名单
        if ($account->status != 0) {
            return ApiResponse::responseFailure(config('quickcms.code_black_account'), trans('api::messages.black_account'));
        }
        // token
        $token = self::generateToken();
        $account->token = $token;
        $account->last_ip = $request->ip();
        $account->last_time = Carbon::now();
        $account->save();
        Auth::user()->setUser($account);
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 验证码登陆方式
     * @param $request
     * @return mixed
     */
    public function loginByCaptcha(Request $request) {
        if (!$this->validation->passes($this->validation->loginByCaptchaRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = $request->phone;
        // 验证码输入错误
        if(self::checkCaptcha($phone, $request->captcha)) {
            return ApiResponse::errorPreCondition(trans('api::messages.captcha_error'));
        }
        // 验证帐号
        $account = $this->model->where('phone', $phone)->first();
        // token
        $token = self::generateToken();
        if(is_null($account)) {
            $account = new $this->model;
            $account->phone = $phone;
        } else {
            // 黑名单
            if($account->status != 0) {
                return ApiResponse::responseFailure(config('quickcms.code_black_account'), trans('api::messages.black_account'));
            }
        }
        $account->token = $token;
        $account->last_ip = $request->ip();
        $account->last_time = Carbon::now();
        $account->save();
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 用户注册API
     * @param $request
     * @return mixed
     */
    public function register(Request $request) {
        if (!$this->validation->passes($this->validation->registerRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = $request->phone;
        $email = $request->email;
        if (isset($phone)) {
            $query = $this->model->where('phone', $phone);
            $data['phone'] = $phone;
        } else {
            $query = $this->model->where('email', $email);
            $data['email'] = $email;
        }
        $account = $query->first();
        // 已被注册
        if (!is_null($account)) {
            return ApiResponse::errorPreCondition(isset($phone) ? trans('api::messages.phone_is_register') : trans('api::messages.email_is_register'));
        }
        // 验证码输入错误
        if (self::checkCaptcha(isset($phone) ? $phone : $email, $request->captcha)) {
            return ApiResponse::errorPreCondition(trans('api::messages.captcha_error'));
        }
        $token = self::generateToken();

        if (config('quickcms.account_bind_im')) {
            $data['im_username'] = date('YmdHis') . rand(100000, 999999);
            $data['im_password'] = md5(rand(100000, 999999));
        }
        $data = array(
            'password' => md5($request->password),
            'token' => $token,
            'register_platform' => $request->header('platform'),
            'register_channel' => $request->header('channel_id'),
            'register_version' => $request->header('build'),
        );

        $account = $this->model->create($data);
        return ApiResponse::responseSuccess($account);
    }

    /**
     * 忘记密码API
     * @param $request
     * @return mixed
     */
    public function forgetPassword(Request $request) {
        if (!$this->validation->passes($this->validation->registerRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = $request->phone;
        $email = $request->email;
        if (isset($phone)) {
            $query = $this->model->where('phone', $phone);
        } else {
            $query = $this->model->where('email', $email);
        }
        // 验证码输入错误
        if (self::checkCaptcha(isset($phone) ? $phone : $email, $request->captcha)) {
            return ApiResponse::errorPreCondition(trans('api::messages.captcha_error'));
        }
        $account = $query->first();
        if ($account == null) {
            return ApiResponse::errorPreCondition(trans('api::messages.account_not_exist'));
        }
        $account->password = md5($request->password);
        $account->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * 修改密码API
     * @param $request
     * @return mixed
     */
    public function updatePassword(Request $request) {
        if (!$this->validation->passes($this->validation->updatePwdRules)) {
            return ApiResponse::validation($this->validation);
        }
        $account = Auth::user()->get();
        if ($account->password != md5($request->old_password)) {
            return ApiResponse::errorPreCondition(trans('api::messages.oldPassword_error'));
        }
        $account->password = md5($request->password);
        $account->save();
        return ApiResponse::responseSuccess();
    }

    /**
     * 获取验证码API
     * @param $request
     * @return mixed
     */
    public function captcha(Request $request) {
        if (!$this->validation->passes($this->validation->captchaRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = $request->phone;
        $email = $request->email;
        if (config('quickcms.sms_api_switch')) {
            $captcha = rand(1000, 9999);
            // 发送短信至用户
            if (isset($phone)) {
                $message = sprintf(config('quickcms.sms_captcha'), $captcha);
                $sms = new LuoSiMaoSms(config('quickcms.sms_api_key'));
                $sms->sendSms($phone, $message);
            } else {
                Mail::raw($captcha, function ($message) use ($email) {
                    $message->to(config('quickcms.account_send_email'));
                    $message->to($email);
                });
            }
        } else {
            $captcha = '1234';
        }
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put(isset($phone) ? $phone : $email, $captcha, $expiresAt);
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
     * @param string $key
     * @param string $captcha 验证码
     * @return bool
     */
    private function checkCaptcha($key, $captcha) {
        $captcha_service = Cache::get($key);
        if ($captcha != $captcha_service) {
            return true;
        }
        return false;
    }

    /**
     * 用户更新
     * @param $request
     * @return mixed
     */
    public function update(Request $request) {
        $account = Auth::user()->get()->update($request->all());
        return ApiResponse::responseSuccess($account);
    }
    
    /**
     * 获取语音验证码API
     * @param $request
     * @return mixed
     */
    public function verify(Request $request) {
        if (!$this->validation->passes($this->validation->phoneRules)) {
            return ApiResponse::validation($this->validation);
        }
        $phone = $request->phone;
        if(config('quickcms.sms_api_switch')) {
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

    /**
     * 根据username查询用户详情
     * @param $request
     * @return mixed
     */
    public function easeMobDetail(Request $request) {
        $account = $this->model->where('im_username', $request->im_username)->first();
        return ApiResponse::responseSuccess($account);
    }
}

