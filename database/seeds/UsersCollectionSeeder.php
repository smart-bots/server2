<?php

use Illuminate\Database\Seeder;
use SmartBots\User;

class UsersCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i=0;$i<50;$i++) {
            $password = $faker->password;

            User::create([
                'username' => $faker->username,
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->e164phoneNumber,
                'password' => bcrypt($password),
                'avatar' => 'http://loremflickr.com/200/200?'.$password,
            ]);
        }
    }
}
