<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        if(! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50);
                $table->string('email', 50);
                $table->string('password', 100);
                $table->string('avatar', 100)->nullable();
                $table->string('remember_token')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamp('last_login')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->primary(['user_id', 'role_id']);
            });
        }

        if(! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->integer('parent_id');
                $table->integer('level');
                $table->string('icon', 50);
                $table->string('route', 200);
                $table->integer('sort');
                $table->tinyInteger('type')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if(! Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->integer('permission_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->primary(['permission_id', 'role_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');
    }
}
