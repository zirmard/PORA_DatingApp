<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoveLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_love_languages', function (Blueprint $table) {
            $table->increments('iUserLoveLanguageId');

            $table->unsignedInteger('iLoveLanguageId')->nullable();
            $table->foreign('iLoveLanguageId')->references('iLoveLanguageId')->on('love_languages')->onUpdate('SET NULL')->onDelete('cascade');

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
        Schema::dropIfExists('user_love_languages');
    }
}
