<?php

use App\Eloquent\Suggestion;
use Illuminate\Database\Seeder;

class TestSuggestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Suggestion::class, 5)->create();
    }
}
