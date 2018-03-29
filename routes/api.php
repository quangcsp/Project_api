<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v0', 'as' => 'api.v0.', 'namespace' => 'Api'], function () {
    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::post('home/filters', ['as' => 'homeFilters', 'uses' => 'HomeController@filter']);

    Route::group(['namespace' => 'Auth'], function () {
        Route::post('register', ['as' => 'register', 'uses' => 'RegisterController@register']);
        Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);
        Route::post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);
        Route::post('refresh-token', ['as' => 'refresh.token', 'uses' => 'LoginController@refreshToken']);
    });

    Route::get('books/sort-by', 'BookController@sortBy');
    Route::resource('books', 'BookController', [
        'except' => ['store', 'update', 'destroy']
    ]);
    Route::get('books/{id}/increase-view', ['as' => 'books.increaseView', 'uses' => 'BookController@increaseView']);
    Route::post('books/filters', ['as' => 'books.filters', 'uses' => 'BookController@filter']);
    Route::get('books/category/{category_id}', ['as' => 'books.category', 'uses' => 'BookController@category']);
    Route::get('books/office/{office_id}', ['as' => 'books.office', 'uses' => 'BookController@office']);
    Route::post('books/category/{category_id}/filter', ['as' => 'books.category.filter', 'uses' => 'BookController@filterCategory']);
    Route::post('search', ['as' => 'search', 'uses' => 'BookController@search']);
    Route::resource('categories', 'CategoryController', [
        'only' => ['index']
    ]);
    Route::resource('offices', 'OfficeController', [
        'only' => ['index']
    ]);

    Route::get('search-books', 'SearchController@search');

    Route::get('search-books-detail/{book_id}', 'SearchController@detail');

    Route::group(['middleware' => 'fapi'], function () {
        Route::get('user-profile', ['as' => 'user.profile', 'uses' => 'UserController@getUserFromToken']);
        Route::get('user/books/waiting_approve', ['as' => 'user.books.waiting-approve', 'uses' => 'UserController@getListWaitingApprove']);
        Route::get('user/{book_id}/approve/detail', ['as' => 'user.books.approve.detail', 'uses' => 'UserController@getBookApproveDetail']);
        Route::post('users/add-tags', ['as' => 'user.add.tags', 'uses' => 'UserController@addTags']);
        Route::get('users/interested-books', ['as' => 'user.interested.books', 'uses' => 'UserController@getInterestedBooks']);
        Route::resource('users', 'UserController');
        Route::get('users/book/{id}/{action}', ['as' => 'users.book', 'uses' => 'UserController@getBook']);
        Route::post('books/review/{book_id}', ['as' => 'books.review', 'uses' => 'BookController@review']);
        Route::post('books/new-review/{book_id}', ['as' => 'books.review.new', 'uses' => 'BookController@reviewNew']);
        Route::post('books/booking', ['as' => 'books.booking', 'uses' => 'BookController@booking']);
        Route::post('books/approve/{book_id}', ['as' => 'books.approve', 'uses' => 'BookController@approve']);
        Route::get('users/books/owned', ['as' => 'users.books.owned', 'uses' => 'UserController@ownedBooks']);
        Route::delete('reviews/delete/{id}', ['as' => 'users.review.delete',
            'uses' => 'ReviewController@delete']);
        Route::get('books/review-details/{revieId}/{userId}', ['as' => 'books.review.detail',
            'uses' => 'ReviewController@reviewDetails']);
        Route::post('books/review-details/comment', ['as' => 'books.review.comment',
             'uses' => 'ReviewController@commentReview']);
        Route::post('books/vote', ['as' => 'books.review.vote', 'uses' => 'ReviewController@vote']);
        Route::resource('books', 'BookController', [
            'only' => ['store']
        ]);
        Route::get('books/add-owner/{book_id}', ['as' => 'books.add-owner', 'uses' => 'BookController@addOwner']);
        Route::get('books/remove-owner/{book_id}', ['as' => 'books.remove-owner', 'uses' => 'BookController@removeOwner']);
        Route::post('books/upload-media', ['as' => 'books.uploadMedia', 'uses' => 'BookController@uploadMedia']);
        Route::get('notifications', ['as' => 'users.notifications', 'uses' => 'UserController@getNotifications']);
        Route::post('users/follow', ['as' => 'users.follow', 'uses' => 'UserController@followOrUnfollow']);
        Route::get('users/follow/info/{user_id}', ['as' => 'users.follow.info', 'uses' => 'UserController@getFollowInfo']);
        Route::get('notification/update/{notification_id}', ['as' => 'notification.update', 'uses' => 'UserController@updateViewNotifications']);
        Route::get('notifications/count/user', ['as' => 'notifications.count', 'uses' => 'UserController@getCountNotifications']);
        Route::post('notifications/update/all', ['as' => 'notifications.update.all', 'uses' => 'UserController@updateViewNotificationsAll']);
        Route::get('notifications/dropdown', ['as' => 'users.notifications.dropdown', 'uses' => 'UserController@getNotificationsDropdown']);
        Route::put('books/{book_id}/request_update', ['as' => 'books.request.update', 'uses' => 'BookController@requestUpdate']);
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('waiting-update-book', ['as' => 'users.waiting-update-book', 'uses' => 'UserController@getWaitingApproveEditBook']);
        Route::post('books/approve-request-edit/{update_book_id}', ['as' => 'books.approve.request.edit', 'uses' => 'BookController@approveRequestUpdate']);
        Route::delete('books/delete-request-edit/{update_book_id}', ['as' => 'books.delete.request.edit', 'uses' => 'BookController@deleteRequestUpdate']);
        Route::resource('books', 'BookController', [
            'only' => ['update', 'destroy']
        ]);
        Route::resource('categories', 'CategoryController', [
            'only' => ['store', 'update']
        ]);
        Route::get('categories/all', 'CategoryController@getCategoryByPage');
        Route::get('categories/detail/{id}', 'CategoryController@show');
        Route::group(['prefix' => 'count'], function () {
            Route::get('users', ['as' => 'users.count', 'uses' => 'UserController@getTotalUser']);
            Route::get('books', ['as' => 'books.count', 'uses' => 'BookController@getTotalBook']);
            Route::get('categories', ['as' => 'categories.count', 'uses' => 'CategoryController@getTotalCategory']);
        });
        Route::post('categories/search', ['as' => 'categories.search', 'uses' => 'CategoryController@searchCategoryByName']);
        Route::get('users', 'UserController@getUserList');
        Route::post('users/search', 'UserController@searchUser');
    });
});
