<?php

use Illuminate\Database\Seeder;

class ProjectMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CodeProject\Entities\ProjectMember::class, 20)->create();
    }
}
