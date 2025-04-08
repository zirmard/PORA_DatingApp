<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_pages', function (Blueprint $table) {
            $table->integerIncrements("iPageId");
            $table->char('vPageUuid', 36)->nullable()->unique('vPageUuid');
            $table->string("vPageName",150);
            $table->string("vSlug",50);
            $table->longText("txContent")->nullable();
            $table->boolean('tiIsActive')->default(1)->comment('0 = No, 1 = Yes');
            $table->integer('iCreatedAt')->nullable(false);
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
        Schema::dropIfExists('content_pages');
    }
}
