<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            mpmenu_seeder::class
                     //roletable_seeder::class,
                    // userstable_seeder::class
            //funcode_seeder::class
        ]);
    }
}
