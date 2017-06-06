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
        // 操作日志
        if(! Schema::hasTable('action_logs')) {
            Schema::create('action_logs', function ($table) {
                $table->bigIncrements('id')->comment('主键');
                $table->bigInteger('user_id')->comment('用户id');
                $table->string('user_name', 50)->comment('用户名称');
                $table->string("browser", 150)->comment("浏览器");
                $table->string("system", 150)->comment("操作系统");
                $table->string("url", 150)->comment('url');
                $table->string('ip', 50)->comment('ip');
                $table->string('primary_key', 100)->nullable()->comment('业务主键');
                $table->string('module_name', 100)->nullable()->comment('模块名称');
                $table->tinyInteger('type')->default(0)->comment('类型0-创建 1-修改 2-删除 3-登录');
                $table->text('content')->comment('内容');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 异常日志
        if(! Schema::hasTable('exception_logs')) {
            Schema::create('exception_logs', function ($table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('message', 1000)->comment('消息');
                $table->string('code', 50)->comment('异常码');
                $table->string("file", 255)->comment('异常文件');
                $table->string("line", 50)->comment('异常行');
                $table->text("data")->comment('内容');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // # feedbacks [意见反馈]
        if(! Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function ($table) {
                $table->bigIncrements('id')->comment('主键');
                $table->bigInteger('account_id')->unsigned()->nullable()->comment('用户id');
                $table->string('content', 2000)->comment('反馈文字内容');
                $table->string('contact', 200)->nullable()->comment('联系方式');
                $table->string('version', 200)->nullable()->comment('版本名称');
                $table->bigInteger('version_code')->nullable()->comment('版本号');
                $table->string('device_id', 200)->nullable()->comment('设备唯一id号');
                $table->string('channel_id', 200)->nullable()->comment('渠道编号');
                // timestamp fields
                $table->timestamps();
                $table->softDeletes();
                // foreign key
            });
        }

        // # versions [版本更新]
        if(! Schema::hasTable('versions')) {
            Schema::create('versions', function ($table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('platform', 100)->comment('发表平台');
                $table->bigInteger('version_code')->comment('版本号');
                $table->string('version', 100)->comment('版本名称');
                $table->string('url', 200)->comment('下载地址');
                $table->string('message', 200)->nullable()->comment('消息提示');
                $table->string('description', 255)->nullable()->comment('版本描述');
                $table->tinyInteger('status')->default(0)->comment('版本状态');
                // timestamp fields
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 系统配置表
        if(! Schema::hasTable('systems')) {
            Schema::create('systems', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('key', 50)->comment('系统key');
                $table->string('value', 255)->nullable()->comment('系统value');
                $table->string('description', 255)->nullable()->comment('描述');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 下拉枚举表
        if(! Schema::hasTable('selectors')) {
            Schema::create('selectors', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('name')->comment('枚举名称');
                $table->string('enum_key')->comment('枚举key');
                $table->tinyInteger('type')->comment('枚举类型');
                $table->string('enum_value', 1000)->comment('枚举值');
                $table->string('default_key')->nullable()->comment('默认key');
                $table->string('default_value')->nullable()->comment('默认值');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 推送表
        if(! Schema::hasTable('pushes')) {
            Schema::create('pushes', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->bigInteger('account_id')->comment('用户id');
                $table->string('app_channel_id', 100)->comment('渠道id');
                $table->string('app_user_id', 100)->comment('应用id');
                $table->string('platform', 50)->comment('平台');
                $table->tinyInteger('status')->default(0)->comment('状态');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('push_messages')) {
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
        }

        // 统计表
        if(! Schema::hasTable('statistics')) {
            Schema::create('statistics', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->date('statistic_time')->comment('统计时间');
                $table->string('statistic_key', 50)->comment('统计key');
                $table->bigInteger('statistic_value')->default(0)->comment('统计value');
                $table->integer('sort')->default(0)->comment('排序');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 文档表
        if(! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('key', 255)->comment('key');
                $table->string('title', 255)->comment('标题');
                $table->text('content')->nullable()->comment('文档内容');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('adverts')) {
            Schema::create('adverts', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('image', 255)->nullable();
                $table->tinyInteger('rel_type')->default(0)->comment('关联类型(0-xx 1-xx 2-xx)');
                $table->string('rel_value', 255)->nullable()->comment('关联值');
                $table->tinyInteger('index')->default(0)->comment('广告位置(0-xx 1-xx)');
                $table->string('remark', 255)->nullable()->comment('备注');
                $table->integer('sort')->default(0)->comment('顺序');
                $table->tinyInteger('status')->default(0)->comment('状态(0-下线 1-上线)');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('name', 200)->comment('名称');
                $table->bigInteger('parent_id')->default(0)->comment('父id');
                $table->tinyInteger('level')->default(0)->comment('级别');
                $table->integer('sort')->default(0)->comment('排序');
                $table->tinyInteger('status')->default(0)->comment('状态(0-下线 1-上线)');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('labels')) {
            Schema::create('labels', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('主键');
                $table->string('name', 200)->comment('名称');
                $table->tinyInteger('type')->default(0)->comment('类型(0-xx 1-xx)');
                $table->integer('sort')->default(0)->comment('排序');
                $table->tinyInteger('status')->default(0)->comment('状态(0-下线 1-上线)');
                $table->timestamps();
                $table->softDeletes();
            });
        }
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
        Schema::dropIfExists('adverts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('labels');
    }
}
