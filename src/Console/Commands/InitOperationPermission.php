<?php

namespace Loopeer\QuickCms\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Loopeer\QuickCms\Models\Backend\Permission;

class InitOperationPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quickcms:init_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'quickcms init operation permission default CURD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parent_permissions = Permission::where('type', 0)->where('route', '!=', '#')->where('route', '!=', '/admin/index')->get();
        $operation = array('create' => '新增', 'edit' => '编辑', 'delete' => '删除', 'detail' => '详情');
        $permission = [];
        foreach($parent_permissions as $parent_key => $parent_value) {
            foreach($operation as $operate_key => $operate_value) {
                $permission[] = array(
                    'name' => str_replace('.index', '', $parent_value->name) . '.' . $operate_key,
                    'display_name' => $operate_value, 
                    'route' => $parent_value->route . '/' . $operate_key, 
                    'type' => 1,
                    'parent_id' => $parent_value->id,
                );
            }
        }
	    DB::table('permissions')->insert($permission);	
    }
}
