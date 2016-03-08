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
        $user1 = \App\User::create(array(
            'name' => 'Yohann',
            'email' => 'test@gmail.com',
            'password' => Hash::make('test1')
        ));

        $user1 = \App\User::create(array(
            'name' => 'Michel',
            'email' => 'truc@gmail.com',
            'password' => Hash::make('test1')
        ));

        $user1 = \App\User::create(array(
            'name' => 'Gerard',
            'email' => 'gege@gmail.com',
            'password' => Hash::make('test1')
        ));
    }
}
