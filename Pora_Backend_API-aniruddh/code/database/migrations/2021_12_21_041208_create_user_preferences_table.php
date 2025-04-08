<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->increments('iPreferenceId');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->tinyInteger('tiSameEthnicity')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');
            $table->tinyInteger('tiSameNationality')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');

            $table->string('vPreferredEarnings', 255)->nullable();

            $table->tinyInteger('tiIsDrinkingPreferred')->nullable()->comment('1 - Never, 2 - Occasionally, 3 - Regular');
            $table->tinyInteger('tiIsDrugPreferred')->nullable()->comment('1 - Never, 2 - Occasionally, 3 - Regular');
            $table->tinyInteger('tiPreferredPreviouslyMarried')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');
            $table->tinyInteger('tiLikeToHaveKids')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');
            $table->tinyInteger('tiPreferredEducation')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');

            $table->string('vPreferredEducation', 255)->nullable();
            $table->tinyInteger('tiPreferredAge')->nullable()->comment('1 - Older than me, 2 - Younger than me, 3 - In different');

            $table->tinyInteger('tiPreferredReligiousBeliefs')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');

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
        Schema::dropIfExists('user_preferences');
    }
}
