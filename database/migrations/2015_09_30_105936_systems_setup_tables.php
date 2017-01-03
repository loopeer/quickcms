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
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('user_id')->comment('用户id');
            $table->string('content')->comment('内容');
            $table->string('client_ip',20)->comment('客服端ip');
            $table->timestamps();
            $table->softDeletes();
        });

        // # feedbacks [意见反馈]
        Schema::create('feedbacks',function($table){
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('account_id')->unsigned()->nullable()->comment('用户id');
            $table->string('content', 1000)->comment('反馈文字内容');
            $table->string('contact', 50)->nullable()->comment('联系方式');
            $table->string('version', 100)->nullable()->comment('版本名称');
            $table->bigInteger('version_code')->nullable()->comment('版本号');
            $table->string('device_id', 100)->nullable()->comment('设备唯一id号');
            $table->string('channel_id', 100)->nullable()->comment('渠道编号');
            // timestamp fields
            $table->timestamps();
            $table->softDeletes();
            // foreign key
        });

        // # versions [版本更新]
        Schema::create('versions',function($table){
            $table->bigIncrements('id')->comment('主键');
            $table->string('platform', 50)->comment('发表平台');
            $table->bigInteger('version_code')->comment('版本号');
            $table->string('version', 50)->comment('版本名称');
            $table->string('url', 200)->comment('下载地址');
            $table->string('message', 200)->nullable()->comment('消息提示');
            $table->string('description', 255)->nullable()->comment('版本描述');
            $table->tinyInteger('status')->default(1)->comment('版本状态');
            // timestamp fields
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 系统配置表
        Schema::create('systems', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('system_key', 50)->comment('系统key');
            $table->string('system_value', 255)->nullable()->comment('系统value');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->timestamps();
            $table->softDeletes();
        });

        // 下拉枚举表
        Schema::create('selectors', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('name')->comment('枚举名称');
            $table->string('enum_key')->comment('枚举key');
            $table->tinyInteger('type')->comment('枚举类型');
            $table->string('enum_value', 1000)->comment('枚举值');
            $table->string('default_key')->nullable()->comment('默认key');
            $table->string('default_value')->nullable()->comment('默认值');
            $table->timestamps();
            $table->softDeletes();
        });

        // 推送表
        Schema::create('pushes', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('account_id')->comment('用户id');
            $table->bigInteger('app_channel_id')->comment('渠道id');
            $table->bigInteger('app_user_id')->comment('应用id');
            $table->string('platform', 50)->comment('平台');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('push_messages', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('content', 255)->nullable()->comment('推送内容');
            $table->text('account_ids')->nullable()->comment('推送用户id');
            $table->string('push_type', 20)->default(0)->comment('推送类型');//batch-指定推送、android-安卓、ios-iOS、all-全局推送
            $table->bigInteger('notice_id')->default(0)->comment('通知业务主键id');
            $table->tinyInteger('notice_type')->default(0)->comment('通知业务类型');
            $table->string('notice_url', 255)->nullable()->comment('通知业务跳转链接');
            $table->timestamps();
            $table->softDeletes();
        });

        // 统计表
        Schema::create('statistics', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键');
            $table->date('statistic_time')->comment('统计时间');
            $table->string('statistic_key', 50)->comment('统计key');
            $table->bigInteger('statistic_value')->default(0)->comment('统计value');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });

        // 文档表
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('document_key', 255)->comment('key');
            $table->string('title', 255)->comment('标题');
            $table->text('document_content')->nullable()->comment('文档内容');
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
        Schema::dropIfExists('selectors');
        Schema::dropIfExists('pushes');
        Schema::dropIfExists('push_messages');
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('documents');
    }
}
