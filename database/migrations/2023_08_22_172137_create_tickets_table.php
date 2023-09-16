<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->startingValue(1000);
            $table->string('subject');
            $table->tinyInteger('priority')->default(1)->comment('1:Normal 2:Low 3:High 4:Urgent');
            $table->tinyInteger('status')->default(1)->comment('1:Opened 2:Closed');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('tickets');
    }
};
