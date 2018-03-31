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
            'name' => 'name',
            'title'=> 'Name',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'name' => 'email',
            'title'=> 'Email',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'name' => 'last_name',
            'title'=> 'Last Name',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'name' => 'company',
            'title'=> 'Company',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'name' => 'country',
            'title'=> 'Country',
            'type' => 'string'
        ]);

        DB::table('user_fields')->insert([
            'name' => 'phone',
            'title'=> 'Phone',
            'type' => 'string'
        ]);
    }
}
