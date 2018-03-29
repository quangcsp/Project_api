<?php

use App\Eloquent\User;
use Illuminate\Database\Seeder;

class TestUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 5)->create();
    }
}
