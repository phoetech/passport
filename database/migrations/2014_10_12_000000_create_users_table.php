<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('display_id')->unsigned()->index()->comment('用户ID');
            $table->string('name', 100)->nullable()->comment('昵称');
            $table->string('email', 100)->nullable();
            $table->string('identifier', 32)->comment('登录ID, 国家电话码+电话号码');
            $table->string('ccc', 8)->comment('国家电话码');
            $table->string('phone', 32);

            $table->string('password', 64);
            $table->string('salt', 8);
            $table->rememberToken();

            $table->string('lang', 8)->nullable();
            $table->string('timezone', 64)->nullable();

            // 信息资料
            $table->string('avatar')->nullable()->comment('头像地址');
            $table->date('birthday')->nullable()->comment('用户生日');
            $table->tinyInteger('gender')->default(-2)->comment('性别：-2-未填写 -1-保密 0-女 1-男');
            $table->string('introduction', 500)->nullable()->comment('用户简介');

            $table->timestamp('login_time')->nullable()->comment('最后登录时间');
            $table->integer('login_times')->unsigned()->default(0)->comment('登录次数');
            $table->string('reg_client')->default('unknow')->comment('注册来源web ios android');
            $table->ipAddress('reg_ip')->nullable()->comment('注册时的IP');
            $table->ipAddress('last_ip')->nullable()->comment('最后访问时的IP');
            $table->timestamps();
            $table->index(['ccc', 'phone']);
            $table->index('created_at');
         });

        Schema::create('auth_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ccc', 8)->comment('国家码');
            $table->string('phone', 32)->comment('电话号码');
            $table->string('code', 6)->comment('验证码');
            $table->enum('status', ['unused', 'used'])->default('unused');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_code');
        Schema::dropIfExists('users');
    }
}
