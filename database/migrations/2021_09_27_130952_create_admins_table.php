<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('iAdminId');
            $table->char('vAdminUuid', 36)->nullable()->unique('vAdminUuid');
            $table->string('vName',50);
            $table->string('vEmail',100)->unique();
            $table->string('vPassword',100);
            $table->text('txDescription')->nullable();
            $table->integer('iRoleId')->unsigned()->index('iRoleId');
            $table->tinyInteger('tiIsActive')->default(1)->comment('0 = No, 1 = Yes');
            $table->tinyInteger('tiIsDeleted')->default(0)->comment('0 = No, 1 = Yes');
            $table->integer('iLastLoginAt')->nullable();
            $table->integer('iCreatedAt')->nullable();
            $table->integer('iUpdatedAt')->nullable();
            $table->rememberToken();
            $table->foreign('iRoleId')->references('iRoleId')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
