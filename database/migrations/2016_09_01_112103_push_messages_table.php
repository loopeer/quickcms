<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PushMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('push_messages');
    }
}
