<?php
use Illuminate\Http\Request;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('signup', 'API\AuthController@signup');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'API\AuthController@logout');
        Route::get('user', 'API\AuthController@user');
    });
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'API\PasswordResetController@create');
    Route::get('find/{token}', 'API\PasswordResetController@find');
    Route::post('reset', 'API\PasswordResetController@reset');
});


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('signup', 'API\AuthController@signup');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'API\AuthController@logout');
        Route::get('user', 'API\AuthController@retrieveCurrentUser');
    });
});





Route::group(['middleware' => 'auth:api'], function() {
    Route::post('users/{user}/unfollow', 'API\UserController@unFollowUser');
    Route::post('users/{user}/follow', 'API\UserController@followUser');

    Route::get('authtags', 'API\TagController@user_tags');
    Route::get( 'tags', 'API\TagController@index_tag');
    Route::post('tags', 'API\TagController@create_tag');
    Route::delete('tags/{tag}/delete', 'API\TagController@delete_tag');
    Route::put('tags/{tag}', 'API\TagController@update_tag');
    Route::post('posts', 'API\PostController@create_post');
    Route::get('posts', 'API\PostController@index_post');
    Route::get('authposts', 'API\PostController@auth_posts');
    Route::delete('posts/{post}/delete', 'API\PostController@delete_post');
    Route::get('posts/{post}', 'API\PostController@retieve_post');
    Route::post('posts/{post}', 'API\PostController@update_post');
    Route::post('auth/profile', 'API\AuthController@update_profile');
    Route::delete('auth/profileDelete', 'API\AuthController@delete_profile_image');
    Route::delete('postImages/{postImage}', 'API\PostController@delete_post_image');
    Route::get('users', 'API\UserController@index_users');
    Route::get('users/{user}', 'API\UserController@retrieve_user');
    Route::get('users/{user}/posts', 'API\PostController@retrieve_user_posts');
    Route::get('users/{user}/followers', 'API\UserController@retrieve_followers');
    Route::get('users/{user}/followings', 'API\UserController@retrieve_followings');


});

