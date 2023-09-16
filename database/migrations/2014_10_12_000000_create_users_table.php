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
            $table->bigIncrements('id');
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('status')->default(true)->comment('0:Banned, 1:Active');
            $table->boolean('google2fa_status')->default(false)->comment('0:Disabled, 1:Active');
            $table->text('google2fa_secret')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
