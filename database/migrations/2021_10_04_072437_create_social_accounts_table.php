<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->bigIncrements("iSocialAccountId");
            $table->unsignedBigInteger('iUserId')->index('iUserId');
            $table->string("vSocialId",250);
            $table->boolean("tiSocialType")->nullable()->comment("1 - Facebook, 2 - Google");
            $table->string("vImageUrl",250)->nullable();
            $table->integer('iCreatedAt')->nullable();
            $table->integer('iUpdatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_accounts');
    }
}
