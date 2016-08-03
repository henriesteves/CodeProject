<?php

use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CodeProject\Entities\ProjectTask::class, 30)->create();
    }
}
