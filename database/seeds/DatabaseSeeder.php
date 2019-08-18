<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        User::create([
            'name' => 'Fatih Sevban Uyanik',
            'email' => "fatihsevban15@gmail.com",
            'password' => bcrypt('11111')
        ]);


    }
}
