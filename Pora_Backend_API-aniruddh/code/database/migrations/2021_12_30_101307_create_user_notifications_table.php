<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->increments('iUserNotificationId');

            $table->unsignedBigInteger('iRecievedUserId')->nullable();
            $table->foreign('iRecievedUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedBigInteger('iSendUserId')->nullable();
            $table->foreign('iSendUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->string('vNotificationTitle', 100);

            $table->string('vNotificationDesc', 255)->nullable();

            $table->string('tiNotificationType', 30)->nullable();

            $table->tinyInteger('tiIsRead')->default(0)->comment('1 - Yes, 0 - No');

            $table->tinyInteger('tiIsActive')->default(1)->comment('1 - Active, 0 - InActive');
            $table->timestamp('tsCreatedAt')->useCurrent();
            $table->timestamp('tsUpdatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('tsDeletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
}
