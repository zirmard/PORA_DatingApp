<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ContentPage::class);
        DB::table('admins')->insert([
            'vAdminUuid' => Uuid::uuid6(),
            'vName' => 'Super Admin',
            'vEmail' => 'admin@basecode.com',
            'vPassword' => '$2y$10$Vz7TTJ913ihGErJMDj42Aeh7EuU3ZodJJ89hfvZF/CLiHXWRJ4WU2', //12345678
            'iRoleId' => 1,
            'iCreatedAt' => time()
        ]);
    }
}
