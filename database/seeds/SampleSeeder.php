<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Sample::class, 100)->create();
    }
}
