<?php

use App\Eloquent\Book;
use App\Eloquent\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        factory(Book::class, 20)->create()->each(function ($book) use ($faker) {
            $userIds = app(User::class)->pluck('id')->random(5)->all();
            $ownerIds = app(User::class)->pluck('id')->random(3)->all();
            $stars = [];

            foreach ($ownerIds as $ownerId) {
                $book->owners()->attach($ownerId, [
                    'created_at' => Carbon::now(),
                    'status' => $faker->randomElement(array_flip(config('model.book.status'))),
                    'updated_at' => Carbon::now(),
                ]);
            }

            foreach ($userIds as $key => $userId) {
                $star = $faker->numberBetween(1, 5);

                $book->users()->attach($userId, [
                    'status' => $faker->randomElement(config('model.book_user.status')),
                    'owner_id' => $faker->randomElement($ownerIds),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $book->reviews()->attach($userId, [
                    'title' => $faker->text($maxNbChars = 100),
                    'content' => $faker->text(250),
                    'star' => $star,
                    'up_vote' => $faker->numberBetween($min = 0, $max = 20),
                    'down_vote' => $faker->numberBetween($min = 0, $max = 20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $stars[] = $star;
            }

            $book->update([
                'avg_star' => array_sum($stars) / count($stars),
            ]);

            $book->media()->create([
                'name' => $faker->sentence(5),
                'path' => 'images/picture.jpg',
                'size' => $faker->numberBetween(500, 1024),
                'type' => config('model.media.type.avatar_book'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
    }
}
