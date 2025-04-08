<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_interests', function (Blueprint $table) {
            $table->increments('iUserInterestId');

            $table->unsignedInteger('iInterestId')->nullable();
            $table->foreign('iInterestId')->references('iInterestId')->on('fun_interests')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

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
        Schema::dropIfExists('user_interests');
    }
}
