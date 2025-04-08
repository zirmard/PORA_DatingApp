<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFunInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fun_interests', function (Blueprint $table) {
            $table->increments('iInterestId');
            $table->char('vInterestUuId', 36)->nullable()->unique('vInterestUuId');

            $table->string('vInterestLogo', 255)->nullable();
            $table->string('vInterestName', 255);

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
        Schema::dropIfExists('fun_interests');
    }
}
