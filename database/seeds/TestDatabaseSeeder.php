<?php

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TestOfficesTableSeeder::class);
        $this->call(TestUsersTableSeeder::class);
        $this->call(TestCategoriesTableSeeder::class);
        $this->call(TestBooksTableSeeder::class);
        $this->call(TestSuggestionsTableSeeder::class);
    }
}
