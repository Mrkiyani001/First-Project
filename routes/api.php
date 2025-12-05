<?php

use App\Http\Controllers\AddViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\CommentsRepliesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/register',[AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::group(['middleware' => 'api',], function ($router) {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh_token']);
    Route::get('getallusers',[AuthController::class,'get_all_users']);
    Route::post('getUser',[AuthController::class,'getUser']);
    Route::put('updateUser',[AuthController::class,'update_user']);
    Route::patch('forget_password',[AuthController::class,'update_password']);
    Route::delete('delete_user',[AuthController::class,'delete_user']);

    // Post Routes
    Route::post('create_post',[PostController::class,'create']);
    Route::post('update_post',[PostController::class,'update']);
    Route::delete('delete_post',[PostController::class,'destroy']);
    Route::post('get_post',[PostController::class,'get_post']);
    Route::get('get_all_posts',[PostController::class,'get_all_posts']);
// Comment Routes
    Route::post('create_comment',[CommentsController::class,'create']);
    Route::post('update_comment',[CommentsController::class,'update']);
    Route::delete('delete_comment',[CommentsController::class,'destroy']);
    Route::post('get_comment',[CommentsController::class,'get_comments_by_post']);
// Comment Reply Routes
    Route::post('create_comment_reply',[CommentsRepliesController::class,'create']);
    Route::post('update_comment_reply',[CommentsRepliesController::class,'update']);
    Route::delete('delete_comment_reply',[CommentsRepliesController::class,'destroy']);
    Route::post('get_comment_replies',[CommentsRepliesController::class,'get_replies_by_comment']);

// Reaction Routes
Route::post('add_reaction_to_post',[ReactionController::class,'addReactiontoPost']);
Route::post('add_reaction_to_comment',[ReactionController::class,'addReactiontoComment']);
Route::post('add_reaction_to_comment_reply',[ReactionController::class,'addReactiontoCommentReply']);

// View Routes
    Route::post('add_view_to_post',[AddViewController::class,'addView']);

});
