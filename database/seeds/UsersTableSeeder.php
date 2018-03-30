<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'password' => bcrypt('secret'),
            'api_token' => str_random(100)
        ]);

        DB::table('users')->insert([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@gmail.com',
            'password' => bcrypt('secret'),
            'api_token' => str_random(100)
        ]);
    }
}
