<?php

namespace Loopeer\QuickCms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitNotifyTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quickcms:init_notify_template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
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
      if (!Schema::hasTable('notify_templates')) {
          Schema::create('notify_templates', function (Blueprint $table) {
              $table->increments('id')->comment('主键');
              $table->string('name', 20)->comment('模板名称');
              $table->string('template_id', 50)->comment('模板id');
              $table->string('data', 1000)->comment('模板数据');
              $table->string('page', 100)->comment('跳转路径');
              $table->string('emphasis_keyword', 20)->nullable()->comment('放大关键词');
              $table->timestamps();
              $table->softDeletes();
            });
      } else {
          Schema::drop('notify_templates');
      }
    }
}
