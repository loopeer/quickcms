<?php

namespace Loopeer\QuickCms\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Hash;

class CreateBackendUser extends Command
{
    protected $signature = 'quickcms:create_backend_user';

    protected $description = 'quickcms backend create superadmin user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $username = $this->ask('please input your email');
        $password = $this->secret('please input your password');
        $id = DB::table('users')->insertGetId([
            'name' => $username,
            'email' => $username,
            'password' => Hash::make($password),
            'status' => 1,
        ]);
        DB::table('role_user')->insert(['user_id' => $id, 'role_id' => 1,]);
    }
}
