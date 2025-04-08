<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->integerIncrements('iRoleId', true);
            $table->string('vRoleName', 100);
            $table->boolean('tiIsActive')->default(1)->comment('0 = No, 1 = Yes');
            $table->boolean('tiIsDeleted')->default(0)->comment('0 = No, 1 = Yes');
            $table->integer('iCreatedAt')->nullable();;
            $table->integer('iUpdatedAt')->nullable();;
        });

        DB::table('roles')->insert([
            'iRoleId' => 1,
            'vRoleName' => 'Super Admin',
            'iCreatedAt' => time()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
