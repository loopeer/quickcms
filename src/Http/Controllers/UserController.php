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

use Loopeer\QuickCms\Models\RoleUser;
use Input;
use Loopeer\QuickCms\Models\User;

class UserController extends FastController {

    public function storeUser()
    {
        $data = Input::all();
        if ($data['id']) {
            User::find($data['id'])->update($data);
            RoleUser::where('user_id', $data['id'])->first()->update(['role_id' => $data['role_id']]);
        } else {
            $user = User::create($data);
            RoleUser::create(array(
                'user_id' => $user->id,
                'role_id' => $data['role_id']
            ));
        }
        return redirect()->to('admin/users');
    }

}
