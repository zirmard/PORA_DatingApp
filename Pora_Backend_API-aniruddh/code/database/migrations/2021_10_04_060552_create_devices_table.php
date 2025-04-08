<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('iDeviceId');
            $table->unsignedBigInteger('iUserId')->index('iUserId');
            $table->string("vAccessToken",100);
            $table->boolean('tiDeviceType')->default(1)->comment('0 = Web, 1 = Android, 2 = IOS');
            $table->string('vDeviceToken',255)->nullable();
            $table->string('vDeviceUniqueId',255)->nullable();
            $table->string('vOSVersion',10)->nullable();
            $table->string('vDeviceName', 100)->nullable();
            $table->string('vIpAddress',50)->nullable();
            $table->double('dLatitude',10,8)->nullable();
            $table->double('dLongitude',10,8)->nullable();
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
        Schema::dropIfExists('devices');
    }
}
