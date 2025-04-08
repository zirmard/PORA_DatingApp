<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_users', function (Blueprint $table) {
            $table->increments('iUserReportId');

            $table->char('vUserReportUuid', 36)->nullable()->unique('vUserReportUuid');

            $table->unsignedInteger('iReportReasonId')->nullable();
            $table->foreign('iReportReasonId')->references('iReportReasonId')->on('report_reasons')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedBigInteger('iUserId')->nullable();
            $table->foreign('iUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->unsignedBigInteger('iReportedUserId')->nullable();
            $table->foreign('iReportedUserId')->references('iUserId')->on('users')->onUpdate('SET NULL')->onDelete('cascade');

            $table->string('txDetails');
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
        Schema::dropIfExists('reported_users');
    }
}
