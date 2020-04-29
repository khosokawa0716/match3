<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePrivateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('private_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('received_user_id')->after('user_id');
            $table->foreign('received_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('unread')->default(true)->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('private_messages', function (Blueprint $table) {
            $table->dropForeign(['received_user_id']);
            $table->dropColumn('received_user_id');
            $table->dropColumn('unread');
        });
    }
}
