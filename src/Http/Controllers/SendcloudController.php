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

use Route;
use Session;
use Response;
use Input;
use Redirect;
use Log;

class SendcloudController extends BaseController
{
    private $api_user;
    private $api_key;
    private $template_list_api;
    private $template_detail_api;
    private $template_create_api;
    private $template_update_api;
    private $template_delete_api;
    private $template_submit_api;

    public function __construct() {
        $this->middleware('auth.permission:admin.sendcloud');
        $this->api_user = config('sendcloud.api.user');
        $this->api_key = config('sendcloud.api.key');
        $this->template_list_api = 'https://sendcloud.sohu.com/apiv2/template/list?apiUser=' . $this->api_user . '&apiKey=' . $this->api_key;
        $this->template_detail_api = 'https://sendcloud.sohu.com/apiv2/template/get?apiUser=' . $this->api_user . '&apiKey=' . $this->api_key . '&invokeName=';
        $this->template_create_api = 'http://api.sendcloud.net/apiv2/template/add';
        $this->template_update_api = 'http://api.sendcloud.net/apiv2/template/update';
        $this->template_delete_api = 'http://api.sendcloud.net/apiv2/template/delete?apiUser=' . $this->api_user . '&apiKey=' . $this->api_key . '&invokeName=';
        $this->template_submit_api = 'http://api.sendcloud.net/apiv2/template/submit?apiUser=' . $this->api_user . '&apiKey=' . $this->api_key . '&invokeName=';
        parent::__construct();
    }

    public function index() {
        $templates = $this->getTemplatesList();
        $message = Session::get('message');
        return view('backend::sendcloud.index', compact('templates', 'message'));
    }

    public function edit($invokeName) {
        $action = route('admin.sendcloud.store', array('is_edit' => true));
        $template = $this->getTemplateDetail($invokeName);
        $message = Session::get('message');
        return view('backend::sendcloud.create', compact('template', 'action', 'message'));
    }

    public function store() {
        $data = Input::all();
        $data['apiUser'] = $this->api_user;
        $data['apiKey'] = $this->api_key;
        $is_edit  = Input::get('is_edit', false);
        if ($is_edit) {
            $ret = json_decode($this->updateTemplate($data));
        } else {
            $ret = json_decode($this->addTemplate($data));
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
        $ret = json_decode($this->deleteTemplate($invokeName));
        if($ret->result) {
            $message = array('result' => true , 'content' => '删除模板成功');
        } else {
            $message = array('result' => false , 'content' => $ret->message);
        }
        return $message;
    }

    public function review($invokeName) {
        $ret = $this->submitTemplate($invokeName);
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
        return view('backend::sendcloud.normal');
    }

    public function template() {
        $templates = $this->getTemplatesList();
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

    private function getTemplateDetail($invokeName) {
        $ret = json_decode(file_get_contents($this->template_detail_api . $invokeName));
        $data = $ret->info->data;
        return $data;
    }

    private function getTemplatesList() {
        $ret = json_decode(file_get_contents($this->template_list_api));
        $data = $ret->info->dataList;
        return $data;
    }

    private function addTemplate($data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->template_create_api);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Requested-With:XMLHttpRequest'));
        curl_setopt($ch, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $ret = curl_exec($ch);
        Log::info($ret);
        curl_close($ch);
        return $ret;
    }

    private function updateTemplate($data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->template_update_api);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Requested-With:XMLHttpRequest'));
        curl_setopt($ch, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $ret = curl_exec($ch);
        Log::info($ret);
        curl_close($ch);
        return $ret;
    }

    private function deleteTemplate($invokeName) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->template_delete_api . $invokeName);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        Log::info($ret);
        curl_close($ch);
        return $ret;
    }

    private function submitTemplate($invokeName) {
        $template = $this->getTemplateDetail($invokeName);
        if($template->templateStat == 0) {
            //撤销审核
            $ret = json_decode(file_get_contents($this->template_submit_api . $invokeName . '&cancel=1'));
        } else {
            //提交审核
            $ret = json_decode(file_get_contents($this->template_submit_api . $invokeName . '&cancel=0'));
        }
        return $ret;
    }
}