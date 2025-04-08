<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('iTransactionId');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->integer('iSubscriptionPlanId');
            $table->integer('iStartAt');
            $table->integer('iExpiredAt')->nullable();

            $table->text('txReceiptData')->nullable();

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
        Schema::dropIfExists('transaction');
    }
}
