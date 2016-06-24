<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 16-4-28
 * Time: 下午3:44
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Loopeer\Lib\Sendcloud\SendcloudService;
use Route;
use Session;
use Response;
use Input;
use Redirect;
use Log;
use Cache;

class SendcloudController extends BaseController
{
    protected $api_user;
    protected $api_key;
    protected $sendcloud;

    public function __construct() {
        $this->middleware('auth.permission:admin.sendcloud.index');
        $this->api_key = config('quickcms.sendcloud_api_key');
        if(is_null(Cache::get('sendcloud_api_user'))) {
            $api_users = config('quickcms.sendcloud_api_users');
            Cache::forever('sendcloud_api_user', $api_users[0]);
        }
        $this->api_user = Cache::get('sendcloud_api_user');
        $this->sendcloud = new SendcloudService($this->api_key, $this->api_user);
        parent::__construct();
    }

    public function index() {
        $templates = $this->sendcloud->getTemplatesList();
        $message = Session::get('message');
        return view('backend::sendcloud.index', compact('templates', 'message'));
    }

    public function edit($invokeName) {
        $action = route('admin.sendcloud.store', array('is_edit' => true));
        $template = $this->sendcloud->getTemplateDetail($invokeName);
        $message = Session::get('message');
        return view('backend::sendcloud.create', compact('template', 'action', 'message'));
    }

    public function store() {
        $data = Input::all();
        $data['apiUser'] = $this->api_user;
        $data['apiKey'] = $this->api_key;
        $is_edit  = Input::get('is_edit', false);
        if ($is_edit) {
            $ret = json_decode($this->sendcloud->updateTemplate($data));
        } else {
            $ret = json_decode($this->sendcloud->addTemplate($data));
        }
        if($ret->result) {
            $message = array('result' => true , 'content' => '操作成功');
            return redirect('admin/sendcloud')->with('message', $message);
        } else {
            $message = array('result' => false , 'content' => '操作失败，' . $ret->message);
            return Redirect::back()->with('message', $message);
        }
    }

    public function create() {
        $action = route('admin.sendcloud.store');
        $message = Session::get('message');
        return view('backend::sendcloud.create', compact('action', 'message'));
    }

    public function destroy($invokeName) {
        $ret = json_decode($this->sendcloud->deleteTemplate($invokeName));
        if($ret->result) {
            $message = array('result' => true , 'content' => '删除模板成功');
        } else {
            $message = array('result' => false , 'content' => $ret->message);
        }
        return $message;
    }

    public function review($invokeName) {
        $ret = $this->sendcloud->submitTemplate($invokeName);
        $status = Input::get('status');
        if($ret->result) {
            $content = $status == 0 ? '撤销审核成功' : '提交审核成功';
            $message = array('result' => true , 'content' => $content);
        } else {
            $message = array('result' => false , 'content' => $ret->message);
        }
        return $message;
    }

    public function normal() {
        $message = Session::get('message');
        return view('backend::sendcloud.normal', compact('message'));
    }

    public function template() {
        $templates = $this->sendcloud->getTemplatesList();
        $message = Session::get('message');
        $group_templates = [];
        $trigger_templates = [];
        foreach($templates as $template) {
            if($template->templateType == 0) {
                $trigger_templates[] = $template;
            } elseif($template->templateType == 1) {
                $group_templates[] = $template;
            }
        }
        return view('backend::sendcloud.template', compact('group_templates', 'trigger_templates', 'message'));
    }

    public function changeApiUser() {
        $users = config('quickcms.sendcloud_api_users');
        return view('backend::sendcloud.user', compact('users'));
    }

    public function saveApiUser() {
        $api_user = Input::get('api_user');
        Cache::forever('sendcloud_api_user', $api_user);
        $message = array('result' => true , 'content' => 'API USER成功设置为' . $api_user);
        return $message;
    }
}