<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('front.pages.welcome');
});

Route::get('test', function() {
//    Storage::disk('google')->makeDirectory('New Directory');
//    $directories = Storage::disk('google')->directories();
//    Storage::disk('google')->deleteDirectory($directories[0]);
//
//    $files = Storage::disk('google')->files();
//    $allFiles = Storage::disk('google')->allFiles(); // recursive
//    $details = Storage::disk('google')->getMetadata($files[0]);
//    $url = Storage::disk('google')->url($files[0]);
//    Storage::disk('google')->setVisibility($files[0], 'private');
//    $visibility = Storage::disk('google')->getVisibility($files[0]);
//    Storage::disk('google')->rename($files[0], 'New Name');
//    $response = Storage::disk('google')->download($files[0], 'file.jpg'); $response->send();
//    Storage::disk('dropbox')->makeDirectory('New Directory');
    dd(Storage::disk('dropbox')->allFiles());
//    dd(Storage::disk('dropbox')->put('LaraTube', 'Hello World'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('channels', 'ChannelController');
Route::get('videos/{video}/comments', 'CommentController@comments');
Route::get('comments/{comment}/replies', 'CommentController@replies');
Route::get('videos/{video}', 'UploadVideoController@show')->name('videos.show');
Route::put('videos/{video}', 'VideoController@updateViews');
Route::put('videos/{video}/update', 'VideoController@update')->middleware(['auth'])->name('videos.update');

Route::group([
    'middleware' => ['auth']
], function () {
    Route::post('comments/{video}', 'CommentController@store');
    Route::post('votes/{entity_type}/{entity_id}/{vote_type}', 'VoteController@vote');
    Route::resource('channels/{channel}/subscriptions', 'SubscriptionController')->only(['store', 'destroy']);
    Route::get('channels/{channel}/videos', 'UploadVideoController@index')->name('channel.upload');
    Route::post('channels/{channel}/videos', 'UploadVideoController@store');
});
