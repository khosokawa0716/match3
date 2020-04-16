<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->unsignedBigInteger('user_id');
            // onDelete('cascade')を指定することで、usersのレコードが削除されたときに紐づいているレコードを削除する
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->integer('minimum_amount')->nullable();
            $table->integer('max_amount')->nullable();
            $table->text('detail')->nullable();
            $table->boolean('status')->default(true)->comment('募集しているかどうか');
            // 余裕があれば案件削除機能
            $table->boolean('delete_flg')->default(false);
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
        Schema::dropIfExists('projects');
    }
}
