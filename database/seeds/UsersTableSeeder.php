<?php

use App\Eloquent\User;
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
        factory(User::class, 20)->create();

        User::findOrFail(1)->update([
            'name' => 'fbook',
            'email' => 'fbook@framgia.com',
        ]);
    }
}
