<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFavouritesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_favourites_list', function (Blueprint $table) {
            $table->increments('iFavouriteId');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedBigInteger('iFavouriteProfileId')->nullable();
            $table->foreign('iFavouriteProfileId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

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
        Schema::dropIfExists('user_favourites_list');
    }
}
