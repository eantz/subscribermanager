<?php

use Illuminate\Database\Seeder;

class UserFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_fields')->insert([
            'user_id' => 1,
            'name' => 'last_name',
            'title'=> 'Last Name',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 1,
            'name' => 'company',
            'title'=> 'Company',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 1,
            'name' => 'country',
            'title'=> 'Country',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 1,
            'name' => 'phone',
            'title'=> 'Phone',
            'type' => 'string'
        ]);


        DB::table('user_fields')->insert([
            'user_id' => 2,
            'name' => 'last_name',
            'title'=> 'Last Name',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 2,
            'name' => 'company',
            'title'=> 'Company',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 2,
            'name' => 'country',
            'title'=> 'Country',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'user_id' => 2,
            'name' => 'phone',
            'title'=> 'Phone',
            'type' => 'string'
        ]);
    }
}
