<?php

/**
 * Post Concepts
 * 
 */
Route::group(['middleware' => 'auth'], function () {

    Route::group(['namespace' => 'Post', 'as' => 'post.'], function () {
        // Post
        Route::get('blog', 'PostController@index')->name('index');
        Route::get('blog/{slug}', 'PostController@show')->name('show');
        
        Route::post('post/store', 'PostController@store')->name('store');
        Route::delete('post/destroy/{post_id}', 'PostController@destroy')->name('destroy');

        // Viewed User list
        Route::get('post-viewed-users/{post_id}', 'PostController@viewedUsersList');
        // Single post popup
        Route::get('post-single-popup/{post_id}/{post_view?}', 'PostController@single');

    });
    // Category
    Route::get('/category/{name}', ['uses' => 'CategoryController@show', 'as' => 'category.show']);
    Route::post('/category', ['uses' => 'CategoryController@index', 'as' => 'category.index']);
    Route::post('/add-new-place', ['uses' => 'CategoryController@store', 'as' => 'category.store']);

    // Tag
    Route::get('/tag/{name}', ['uses' => 'TagController@show', 'as' => 'tag.show']);
    Route::get('/tag', ['uses' => 'TagController@index', 'as' => 'tag.index']);
    // Comment
    Route::get('/commentable/{commentable_id}/comments', ['uses' => 'CommentController@show', 'as' => 'comment.show']);
    Route::resource('comment', 'CommentController', ['only' => ['store', 'destroy', 'edit', 'update']]);

    // Like
    Route::get('post/like/{id}', ['as' => 'post.like', 'uses' => 'LikeController@likePost']);
    // Liked User list
    Route::get('post-liked-users/{post_id}', 'LikeController@likedUsersList');

    // Slap
    Route::get('post/slap/{id}', ['as' => 'post.slpa', 'uses' => 'SlapController@slapPost']);
    // Slapped User list
    Route::get('post-slapped-users/{post_id}', 'SlapController@slappedUsersList');  


    // Boost Post Route

    Route::get('/boost/create/{post_id}/{search_key?}', ['uses' => 'BoostController@create', 'as' => 'boost.create']);

    Route::post('/boost/store', ['uses' => 'BoostController@store', 'as' => 'boost.store']);
    Route::get('post-boosted-users/{post_id}', ['uses' => 'BoostController@boostedUsers']);

});
