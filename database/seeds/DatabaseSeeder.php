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
        \App\User::create(array(
            'name' => 'Yohann',
            'email' => 'test@gmail.com',
            'password' => Hash::make('test')
        ));
    }
}
