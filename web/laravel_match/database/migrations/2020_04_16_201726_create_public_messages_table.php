<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            // onDelete('cascade')を指定することで、usersのレコードが削除されたときに紐づいているレコードを削除する
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('project_id');
            // onDelete('cascade')を指定することで、projectsのレコードが削除されたときに紐づいているレコードを削除する
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->text('content');
            // 余裕があればメッセージ削除機能
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
        Schema::dropIfExists('public_messages');
    }
}
