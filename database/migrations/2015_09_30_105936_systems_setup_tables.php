<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemsSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //系统日志
        Schema::create('action_logs', function ($table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('content');
            $table->string('client_ip',20);
            $table->timestamps();
            $table->softDeletes();
        });

        // # feedbacks [意见反馈]
        Schema::create('feedbacks',function($table){
            $table->bigIncrements('id');
            $table->bigInteger('account_id')->unsigned()->nullable();//用户id
            $table->string('content', 200);//反馈文字内容
            $table->string('contact', 50)->nullable();//联系方式
            $table->string('version', 20)->nullable();//版本名称
            $table->bigInteger('version_code')->nullable();//版本号
            $table->string('device_id', 20)->nullable();//设备唯一id号
            $table->string('channel_id', 20)->nullable();//渠道编号
            // timestamp fields
            $table->timestamps();
            $table->softDeletes();
            // foreign key
        });

        // # versions [版本更新]
        Schema::create('versions',function($table){
            $table->bigIncrements('id');
            $table->string('platform', 20);//发表平台
            $table->bigInteger('version_code');//版本号
            $table->string('version', 20);//版本名称
            $table->string('url', 100);//下载地址
            $table->string('message', 200)->nullable();//消息提示
            $table->string('description', 255)->nullable();//版本描述
            $table->tinyInteger('status')->default(1);//版本状态
            // timestamp fields
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50); //网站标题
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_logs');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('versions');
        Schema::dropIfExists('systems');
    }
}
