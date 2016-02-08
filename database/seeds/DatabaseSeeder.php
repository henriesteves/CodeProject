<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);

        //CodeProject\Client::truncate();
        //factory(CodeProject\Client::class, 10)->create();

        $this->call(ClientTableSeeder::class);

        Model::reguard();
    }
}
