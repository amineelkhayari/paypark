<?php

use Illuminate\Database\Seeder;
use Database\Seeders\VehicleTypeSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UsersTableSeeder::class]);
        $this->call([VehicleTypeSeeder::class]);
    }
}
