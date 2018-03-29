<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Eloquent\Book;
use Faker\Factory;
use App\Eloquent\Category;
use App\Eloquent\Office;
use Illuminate\Http\UploadedFile;

class BookTest extends TestCase
{
    use DatabaseTransactions;

    public function dataFilterBook()
    {
        return [
            'filters' => [
                ['category' => [2, 3]],
                ['office' => [4, 5]],
            ],
            'sort' => [
                'by' => 'title',
                'order_by' => 'desc'
            ],
        ];
    }

    /* TEST GET BOOKS */

//    public function testGetBooksByRatingSuccess()
//    {
//        $response = $this->call('GET', route('api.v0.books.index', ['field' => 'rating']), [], [], [], $this->getHeaders());
//
//        $response->assertJsonStructure([
//            'item' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }
//
//    public function testGetBooksByLatestSuccess()
//    {
//        $response = $this->call('GET', route('api.v0.books.index', ['field' => 'latest']), [], [], [], $this->getHeaders());
//
//        $response->assertJsonStructure([
//            'item' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }
//
//    public function testGetBooksByViewSuccess()
//    {
//        $response = $this->call('GET', route('api.v0.books.index', ['field' => 'view']), [], [], [], $this->getHeaders());
//
//        $response->assertJsonStructure([
//            'item' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }
//
//    public function testGetBooksByReadSuccess()
//    {
//        $response = $this->call('GET', route('api.v0.books.index', ['field' => 'read']), [], [], [], $this->getHeaders());
//
//        $response->assertJsonStructure([
//            'item' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }

    public function testGetBooksInvalid()
    {
        $response = $this->call('GET', route('api.v0.books.index', ['field' => 'viewa']), [], [], [], $this->getHeaders());
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description',
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    /* TEST SHOW DETAIL BOOK */

    public function testShowBookWithBookInvalid()
    {
        $headers = $this->getHeaders();
        $response = $this->call('GET', route('api.v0.books.show', 'xxx'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testShowBookWithBookNotFound()
    {
        $headers = $this->getHeaders();
        $response = $this->call('GET', route('api.v0.books.show', 0), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testShowBooksSuccess()
    {
        $headers = $this->getHeaders();
        $book = factory(Book::class)->create();

        $response = $this->call('GET', route('api.v0.books.show', $book->id), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    /* TEST INCREASE VIEW OF BOOK */

    public function testIncreaseViewBookWithBookInvalid()
    {
        $headers = $this->getHeaders();
        $response = $this->call('GET', route('api.v0.books.increaseView', 'xxx'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testIncreaseViewBookWithBookNotFound()
    {
        $headers = $this->getHeaders();
        $response = $this->call('GET', route('api.v0.books.increaseView', 0), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testIncreaseViewBookSuccess()
    {
        $headers = $this->getHeaders();
        $book = factory(Book::class)->create();

        $response = $this->call('GET', route('api.v0.books.increaseView', $book->id), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'count_view' => $book->count_view + 1
        ]);
    }

    /* TEST LIST BOOKS */

//    public function testListBookSearchSuccess()
//    {
//        $headers = $this->getHeaders();
//        $data = [
//            'search' => [
//                'field' => 'title',
//                'keyword' => 'a',
//            ],
//            'conditions' => [
//                [
//                    'category' => [
//                        1, 2, 3
//                    ]
//                ],
//                [
//                    'office' => [
//                        1, 2, 3
//                    ]
//                ],
//            ],
//            'sort' => [
//                'field' => 'avg_star',
//                'order_by' => 'desc',
//            ],
//        ];
//
//        $response = $this->call('POST', 'api/v0/search', $data, [], [], $headers);
//        $response->assertJsonStructure([
//            'items' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }
//
//    public function testListBookSearchWithNotInput()
//    {
//        $headers = $this->getHeaders();
//
//        $response = $this->call('POST', 'api/v0/search', [], [], [], $headers);
//        $response->assertJsonStructure([
//            'items' => [
//                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
//            ],
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }

    public function testListBookSearchWithFieldInValid()
    {
        $headers = $this->getHeaders();
        $data = [
            'search' => [
                'field' => 'a',
                'keyword' => 'a',
            ],
            'conditions' => [
                [
                    'category' => [
                        1, 2, 3
                    ]
                ],
                [
                    'office' => [
                        1, 2, 3
                    ]
                ],
            ],
            'sort' => [
                'field' => 'a',
                'order_by' => 'a',
            ],
        ];

        $response = $this->call('POST', 'api/v0/search', $data, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    /* TEST BOOKING BOOK */
    public function testBookingSuccess()
    {
        $headers = $this->getFauthHeaders();
        $book = Book::first();
        $user = $this->createUser();
        $book->owners()->attach($user->id, ['status' => config('model.book.status.available')]);

        $data['book_id'] = $book->id;
        $data['status'] = config('model.book_user.status.waiting');
        $data['owner_id'] = $this->createUser()->id;
        $data['days_to_read'] = 7;

        $response = $this->call('POST', route('api.v0.books.booking'), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testBookingWithBookInValid()
    {
        $headers = $this->getFauthHeaders();

        $data['book_id'] = 0;
        $data['status'] = config('model.book_user.status.waiting');
        $data['owner_id'] = $this->createUser()->id;

        $response = $this->call('POST', route('api.v0.books.booking'), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testBookingStatusWaitingWithGuest()
    {
        $headers = $this->getHeaders();
        $book = Book::first();
        $user = $this->createUser();
        $book->owners()->attach($user->id, ['status' => config('model.book.status.available')]);

        $data['book_id'] = $book->id;
        $data['status'] = config('model.book_user.status.waiting');
        $data['owner_id'] = $this->createUser()->id;

        $response = $this->call('POST', route('api.v0.books.booking'), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    /* TEST REVIEW BOOK */

//    public function testReviewBookSuccess()
//    {
//        $faker = Factory::create();
//        $headers = $this->getFauthHeaders();
//        $book = factory(Book::class)->create();
//
//        $dataReview['content'] = $faker->sentence;
//        $dataReview['star'] = $faker->numberBetween(1, 5);
//
//        $response = $this->call('POST', route('api.v0.books.review', $book->id), ['item' => $dataReview], [], [], $headers);
//        $response->assertJsonStructure([
//            'message' => [
//                'status', 'code',
//            ],
//        ])->assertJson([
//            'message' => [
//                'status' => true,
//                'code' => 200,
//            ]
//        ])->assertStatus(200);
//    }

    public function testReviewBookWithFieldsNull()
    {
        $headers = $this->getFauthHeaders();
        $book = factory(Book::class)->create();

        $response = $this->call('POST', route('api.v0.books.review', $book->id), ['item' => []], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    public function testReviewBookWithBookIdInvalid()
    {
        $headers = $this->getFauthHeaders();

        $response = $this->call('POST', route('api.v0.books.review', 0), ['item' => []], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    public function testReviewBookWithGuest()
    {
        $faker = Factory::create();
        $headers = $this->getHeaders();
        $book = factory(Book::class)->create();

        $dataReview['content'] = $faker->sentence;
        $dataReview['star'] = $faker->numberBetween(1, 5);

        $response = $this->call('POST', route('api.v0.books.review', $book->id), ['item' => $dataReview], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    /* TEST GET BOOKS */

    public function testGetBooksFilterByRatingSuccess()
    {
        $response = $this->call(
            'POST', route('api.v0.books.filters', ['field' => 'rating']), $this->dataFilterBook(), [], [], $this->getHeaders()
        );

        $response->assertJsonStructure([
            'items' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksFilterByLatestSuccess()
    {
        $response = $this->call(
            'POST', route('api.v0.books.filters', ['field' => 'latest']), $this->dataFilterBook(), [], [], $this->getHeaders()
        );

        $response->assertJsonStructure([
            'items' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksFilterByViewSuccess()
    {
        $response = $this->call(
            'POST', route('api.v0.books.filters', ['field' => 'view']), $this->dataFilterBook(), [], [], $this->getHeaders()
        );

        $response->assertJsonStructure([
            'items' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'data'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksFilterInvalid()
    {
        $input = [
            'filters' => 'a',
            'sort' => [
                'key' => 'a',
                'order_by' => 'a',
            ],
        ];
        $response = $this->call('POST', route('api.v0.books.filters', ['field' => 'viewa']), $input, [], [], $this->getHeaders());
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description',
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    /* TEST STORE BOOKS */

    public function testStoreBookSuccess()
    {
        $headers = $this->getFauthHeaders();
        $dataBook = factory(Book::class)->make()->toArray();
        $dataBook['category_id'] = factory(Category::class)->create()->id;
        $dataBook['office_id'] = factory(Office::class)->create()->id;
        $dataBook['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $dataBook['medias'][0]['type'] = config('model.media.type.avatar_book');

        $response = $this->call('POST', route('api.v0.books.store'), $dataBook, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testStoreBookWithFieldsNull()
    {
        $headers = $this->getFauthHeaders();

        $response = $this->call('POST', route('api.v0.books.store'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    public function testStoreBookWithGuest()
    {
        $headers = $this->getHeaders();
        $dataBook = factory(Book::class)->make()->toArray();
        $dataBook['category_id'] = factory(Category::class)->create()->id;
        $dataBook['office_id'] = factory(Office::class)->create()->id;
        $dataBook['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $dataBook['medias'][0]['type'] = config('model.media.type.avatar_book');

        $response = $this->call('POST', route('api.v0.books.store'), $dataBook, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    /* TEST REQUEST UPDATE BOOKS */

    public function testUpdateBookNotOwner()
    {
        $headers = $this->getFauthHeaders();
        $bookId = factory(Book::class)->create()->id;
        $dataBook = factory(Book::class)->make()->toArray();
        $dataBook['category_id'] = factory(Category::class)->create()->id;
        $dataBook['office_id'] = factory(Office::class)->create()->id;
        $dataBook['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $dataBook['medias'][0]['type'] = config('model.media.type.avatar_book');

        $response = $this->call('PUT', route('api.v0.books.request.update', $bookId), $dataBook, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 400,
            ]
        ])->assertStatus(400);
    }

    public function testUpdateBookWithFieldsNull()
    {
        $headers = $this->getFauthHeaders();
        $bookId = factory(Book::class)->create()->id;

        $response = $this->call('PUT', route('api.v0.books.request.update', $bookId), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    public function testUpdateBookWithGuest()
    {
        $headers = $this->getHeaders();
        $bookId = factory(Book::class)->create()->id;
        $dataBook = factory(Book::class)->make()->toArray();
        $dataBook['category_id'] = factory(Category::class)->create()->id;
        $dataBook['office_id'] = factory(Office::class)->create()->id;
        $dataBook['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);

        $response = $this->call('PUT', route('api.v0.books.request.update', $bookId), $dataBook, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }


    /* TEST DELETE BOOKS */

    public function testDeleteBookWithInvalidBookId()
    {
        $headers = $this->getFauthHeaders();

        $response = $this->call('DELETE', route('api.v0.books.destroy', 'xxx'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testDeleteBookWithGuest()
    {
        $headers = $this->getHeaders();
        $bookId = factory(Book::class)->create()->id;

        $response = $this->call('DELETE', route('api.v0.books.destroy', $bookId), [], [], [], $headers);

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    /* TEST GET BOOK BY CATEGORY */

    public function testGetBooksByCategorySuccess()
    {
        $categoryId = factory(Category::class)->create()->id;
        $response = $this->call('GET', route('api.v0.books.category', $categoryId), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'items' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'category'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksByCategoryWithIdInvalid()
    {
        $response = $this->call('GET', route('api.v0.books.category', 0), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
                'description' => [translate('exception.not_found')]
            ]
        ])->assertStatus(404);
    }

    /* TEST ADD OWNER BOOK*/

    public function testAddOwnerBookSuccess()
    {
        $bookId = factory(Book::class)->create()->id;
        $response = $this->call('GET', route('api.v0.books.add-owner', $bookId), [], [], [], $this->getFauthHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testAddOwnerBookIdInvalid()
    {
        $response = $this->call('GET', route('api.v0.books.add-owner', 0), [], [], [], $this->getFauthHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
                'description' => [translate('exception.not_found')]
            ]
        ])->assertStatus(404);
    }

    /* TEST REMOVE OWNER BOOK*/

    public function testRemoveOwnerBookNotOwner()
    {
        $bookId = factory(Book::class)->create()->id;
        $response = $this->call('GET', route('api.v0.books.remove-owner', $bookId), [], [], [], $this->getFauthHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 400,
                'description' => [translate('exception.not_owner')]
            ]
        ])->assertStatus(400);
    }

    public function testRemoveOwnerBookIdInvalid()
    {
        $response = $this->call('GET', route('api.v0.books.remove-owner', 0), [], [], [], $this->getFauthHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
                'description' => [translate('exception.not_found')]
            ]
        ])->assertStatus(404);
    }

    public function testRemoveOwnerBookWithGuest()
    {
        $bookId = factory(Book::class)->create()->id;
        $response = $this->call('GET', route('api.v0.books.remove-owner', $bookId), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401
            ]
        ])->assertStatus(401);
    }

    /* TEST GET BOOK FILTERED BY CATEGORY */

    public function testGetBooksFilteredByCategorySuccess()
    {
        $categoryId = factory(Category::class)->create()->id;
        $data = [
            'filters' => [
                ['office' => [4, 5]],
            ],
            'sort' => [
                'by' => 'count_view',
                'order_by' => 'desc'
            ],
        ];
        $response = $this->call('POST', route('api.v0.books.category.filter', $categoryId), $data, [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'items' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'category'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksFilteredByCategoryWithIdInvalid()
    {
        $response = $this->call('POST', route('api.v0.books.category.filter', 0), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
                'description' => [translate('exception.not_found')]
            ]
        ])->assertStatus(404);
    }

    /* TEST UPLOAD MEDIA FOR BOOKS */

    public function testUploadMediaForBookNotOwner()
    {
        $headers = $this->getFauthHeaders();

        $data['book_id'] = factory(Book::class)->create()->id;
        $data['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $data['medias'][0]['type'] = config('model.media.type.avatar_book');
        $data['medias'][1]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $data['medias'][1]['type'] = config('model.media.type.not_avatar_book');

        $response = $this->call('POST', route('api.v0.books.uploadMedia'), $data, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 400,
            ]
        ])->assertStatus(400);
    }

    public function testUploadMediaForBookWithFieldsNull()
    {
        $headers = $this->getFauthHeaders();

        $response = $this->call('POST', route('api.v0.books.uploadMedia'), [], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 422,
            ]
        ])->assertStatus(422);
    }

    public function testUploadMediaForBookWithGuest()
    {
        $headers = $this->getHeaders();

        $data['book_id'] = factory(Book::class)->create()->id;
        $data['medias'][0]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $data['medias'][0]['type'] = config('model.media.type.avatar_book');
        $data['medias'][1]['file'] = UploadedFile::fake()->image(str_random(20) . '.jpg', 100, 100)->size(100);
        $data['medias'][1]['type'] = config('model.media.type.not_avatar_book');

        $response = $this->call('POST', route('api.v0.books.uploadMedia'), $data, [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    /* TEST APPROVE BOOK */

    public function testApproveBookWithBookInValid()
    {
        $headers = $this->getFauthHeaders();

        $data['user_id'] = $this->createUser()->id;
        $data['key'] = 'approve';

        $response = $this->call('POST', route('api.v0.books.approve', 0), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
            ]
        ])->assertStatus(404);
    }

    public function testApproveBookWithGuest()
    {
        $headers = $this->getHeaders();

        $bookId = factory(Book::class)->create()->id;
        $data['user_id'] = $this->createUser()->id;
        $data['key'] = 'approve';

        $response = $this->call('POST', route('api.v0.books.approve', $bookId), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 401,
            ]
        ])->assertStatus(401);
    }

    public function testApproveBookWithNotOwner()
    {
        $headers = $this->getFauthHeaders();
        $book = factory(Book::class)->create();
        $owner = $this->createUser();
        $book->owners()->attach($owner->id, ['status' => config('model.book.status.available')]);

        $data['user_id'] = $this->createUser()->id;
        $data['key'] = 'approve';

        $response = $this->call('POST', route('api.v0.books.approve', $book->id), ['item' => $data], [], [], $headers);
        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 400,
            ]
        ])->assertStatus(400);
    }

    /* TEST GET BOOK BY OFFICE */

    public function testGetBooksByOfficeSuccess()
    {
        $officeId = factory(Office::class)->create()->id;
        $response = $this->call('GET', route('api.v0.books.office', $officeId), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'item' => [
                'total', 'per_page', 'current_page', 'next_page', 'prev_page', 'office'
            ],
            'message' => [
                'status', 'code',
            ],
        ])->assertJson([
            'message' => [
                'status' => true,
                'code' => 200,
            ]
        ])->assertStatus(200);
    }

    public function testGetBooksByOfficeWithIdInvalid()
    {
        $response = $this->call('GET', route('api.v0.books.office', 0), [], [], [], $this->getHeaders());

        $response->assertJsonStructure([
            'message' => [
                'status', 'code', 'description'
            ],
        ])->assertJson([
            'message' => [
                'status' => false,
                'code' => 404,
                'description' => [translate('exception.not_found')]
            ]
        ])->assertStatus(404);
    }
}
