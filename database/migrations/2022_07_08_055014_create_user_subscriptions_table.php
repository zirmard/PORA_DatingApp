<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->integerIncrements('iUserSubscriptionId');
            $table->unsignedBigInteger('iUserId');
            $table->unsignedInteger('iSubscriptionPlanId');
            $table->string('vProductId',150)->nullable();
            $table->tinyInteger('tiIsAutoRenewing')->default(0);
            $table->text('txProductMetaData')->nullable();
            $table->longText('ltxSubscriptionToken')->nullable();
            $table->dateTime('dtSubscriptionStartDate')->nullable();
            $table->dateTime('dtSubscriptionEndDate')->nullable();
            $table->text('txError')->nullable();
            $table->tinyInteger('tiIsSandbox')->default(0);
            $table->tinyInteger('tiIsActive')->default(1)->comment('1 - Active, 0 - InActive');
            $table->timestamp('tsDeletedAt')->nullable();
            $table->timestamp('tsCreatedAt')->useCurrent();
            $table->timestamp('tsUpdatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index('iUserId');
            $table->foreign('iUserId')->references('iUserId')->on('users')->onDelete('cascade');
            $table->index('iSubscriptionPlanId');
            $table->foreign('iSubscriptionPlanId')->references('iSubscriptionPlanId')->on('subscription_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
}
