<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('iUserId');
            $table->char('vUserUuid', 36)->nullable()->unique('vUserUuid');

            $table->string('vFirstName', 32)->nullable();
            $table->string('vLastName', 32)->nullable();
            $table->string('vISDCode', 6)->nullable();
            $table->string('vMobileNumber', 20)->nullable();
            $table->integer('iOTP')->nullable();
            $table->integer('iOTPExpireAt')->nullable();
            $table->string('vEmailId', 255)->nullable();
            $table->string('vPassword', 255)->nullable();
            $table->tinyInteger('tiGender')->nullable()->comment('1 - Man, 2 - Woman');
            $table->tinyInteger('tiLookingFor')->nullable()->comment('1 - Love, 2 - Friendship, 3 - Both');
            $table->integer('iDob')->nullable();

            $table->string('vZodiacSignName', 32)->nullable();

            $table->string('vOriginCountry', 64)->nullable();
            $table->string('vEthnicGroup', 64)->nullable();
            $table->string('vLivingCountry', 64)->nullable();
            $table->string('vCity', 64)->nullable();
            $table->string('vFaith', 64)->nullable();

            $table->tinyInteger('tiRelationshipStatus')->nullable()->comment('1 - Single, 2 - commited ...');

            $table->string('vProfileImage', 255)->nullable();
            $table->string('vVideo', 255)->nullable();
            $table->string('vTimeZone', 255)->nullable();

            $table->double('dbLatitude', 10,8)->nullable();
            $table->double('dbLongitude', 10,8)->nullable();

            $table->string('vOccupation', 255)->nullable();
            $table->string('vEarnings', 255)->nullable();

            $table->tinyInteger('tiIsDrink')->nullable()->comment('1 - Never, 2 - Occasionally, 3 - Regular');
            $table->tinyInteger('tiUseDrugs')->nullable()->comment('1 - Never, 2 - Occasionally, 3 - Regular');
            $table->tinyInteger('tiBelieveInMarriage')->nullable()->comment('1 - Yes, 2 - No, 3 - In different');
            $table->tinyInteger('tiHaveKids')->nullable()->comment('1 - Yes, 2 - No');

            $table->string('vEducationQualification', 255)->nullable();
            $table->text('txAboutYourSelf')->nullable();
            $table->text('txDealBreaker')->nullable();

            $table->tinyInteger('tiIsSocialLogin')->default(0)->comment('1 - Yes, 0 - No');
            $table->tinyInteger('tiIsPremiumUser')->default(0)->comment('1 - Yes, 0 - No');

            $table->tinyInteger('tiIsMobileVerified')->default(0)->comment('1 - Verified, 0 - Not Verified');
            $table->tinyInteger('tiIsProfileImageVerified')->nullable()->comment('1 - Verified, 2 - Rejected, 3 - Uploaded (Face-x API)');

            $table->tinyInteger('tiIsProfileCompleted')->default(0)->comment('1 - Completed till phase 1, 2 - Completed till phase 2, 3 - Completed till phase 3, 4 - All Steps Completed , 0 - Not Completed');

            $table->string('vQuickBloxUserId', 32)->nullable();
            $table->string('vQbLogin', 64)->nullable();

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
        Schema::dropIfExists('users');
    }
}
