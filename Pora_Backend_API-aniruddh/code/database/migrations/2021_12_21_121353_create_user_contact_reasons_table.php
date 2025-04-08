<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContactReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contact_reasons', function (Blueprint $table) {
            $table->increments('iUserContactReasonId');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedInteger('iContactReasonId')->nullable();
            $table->foreign('iContactReasonId')->references('iContactReasonId')->on('contact_reasons')->onUpdate('SET NULL')->onDelete('cascade');

            $table->string('txDetails');
            $table->timestamp('tsCreatedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_contact_reasons');
    }
}
