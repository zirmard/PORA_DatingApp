<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoveLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('love_languages', function (Blueprint $table) {
            $table->increments('iLoveLanguageId');
            $table->char('vLoveLanguageUuid', 36)->nullable()->unique('vLoveLanguageUuid');

            $table->string('vLoveLanguage', 255);
            $table->string('vLoveLanguageLogo', 255)->nullable();

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
        Schema::dropIfExists('love_languages');
    }
}
