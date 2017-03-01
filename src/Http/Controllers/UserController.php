<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/10
 * Time: 上午10:12
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Loopeer\QuickCms\Models\RoleUser;
use Loopeer\QuickCms\Models\User;

class UserController extends FastController {

    public function storeUser()
    {
        $data = Input::all();
        if ($data['id']) {
            User::find($data['id'])->update($data);
            $roleUser = RoleUser::where('user_id', $data['id'])->first();
            $roleUser->role_id = $data['roles_id'];
            $roleUser->save();
        } else {
            $user = User::create($data);
            RoleUser::create(array(
                'user_id' => $user->id,
                'role_id' => $data['roles_id']
            ));
        }
        return redirect()->to('admin/users');
    }

    public function getProfile() {
        $user = Auth::admin()->get();
        $image = array(
            'column' => 'image',
            'min' => 1,
            'max' => 1,
            'min_error_msg' => '至少上传%s张图片',
            'max_error_msg' => '最多只允许上传%s张图片',
        );
        return view('backend::users.update', compact('user', 'image'));
    }

    public function saveProfile() {
        $data = Input::all();
        $data['avatar'] = $data['image'];
        unset($data['image']);
        unset($data['passwordConfirm']);
        User::find(Auth::admin()->get()->id)->update($data);
        $message = array('result' => true, 'content' => '保存成功');
        return redirect('admin/users/profile')->with('message', $message);
    }
}
