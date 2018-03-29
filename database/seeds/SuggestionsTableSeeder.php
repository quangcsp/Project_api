<?php

use App\Eloquent\Suggestion;
use Illuminate\Database\Seeder;

class SuggestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Suggestion::class, 20)->create();
    }
}
