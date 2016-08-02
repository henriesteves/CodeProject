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

        //CodeProject\Entities\Client::truncate();
        //factory(CodeProject\Client::class, 10)->create();
        //CodeProject\Entities\Project::truncate();

        $this->call(UserTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(ProjectNoteSeeder::class);

        Model::reguard();
    }
}
