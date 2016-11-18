<?php

use Illuminate\Database\Seeder;
use SmartBots\Hub;

class HubsCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i=0;$i<100;$i++) {

            Hub::create([
                'token' => str_random(50),
                'name' => $faker->state,
                'description' => $faker->text,
                'timezone' => $faker->timezone,
                'image' => 'http://loremflickr.com/200/200?'.str_random(5),
                'active' => rand(0,1)
            ]);
        }
    }
}
