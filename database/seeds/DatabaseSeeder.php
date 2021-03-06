<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('InteractionTypeSeeder');
        $this->call('InteractionSeeder');
        $this->call('SegmentSeeder');
        $this->call('TokenSeeder');
    }
}
