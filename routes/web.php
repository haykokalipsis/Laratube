<?php

//use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('front.pages.welcome');
});

//Route::get('test', function() {
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
//    dd(Storage::disk('dropbox')->allFiles());
//    dd(Storage::disk('dropbox')->put('LaraTube', 'Hello World'));
//});

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');;
Route::resource('channels', \App\Http\Controllers\ChannelController::class)->only(['show', 'update', 'destroy']);
Route::get('videos/{video}/comments', [\App\Http\Controllers\CommentController::class, 'comments']);
Route::get('comments/{comment}/replies', [\App\Http\Controllers\CommentController::class, 'replies']);
Route::get('videos/{video}', [\App\Http\Controllers\VideoController::class, 'show'])->name('videos.show');
Route::put('videos/{video}', [\App\Http\Controllers\VideoController::class, 'updateViews']);
Route::put('videos/{video}/update', [\App\Http\Controllers\VideoController::class, 'update'])->middleware(['auth'])->name('videos.update');

Route::get('video-links/{video_id}', [\App\Http\Controllers\VideoController::class, 'get_public_urls'])->name('video-links.show');

Route::group([
    'middleware' => ['auth']
], function () {
    Route::post('comments/{video}', [\App\Http\Controllers\CommentController::class, 'store']);
    Route::post('votes/{entity_type}/{entity_id}/{vote_type}', [\App\Http\Controllers\VoteController::class, '@vote']);
    Route::resource('channels/{channel}/subscriptions', \App\Http\Controllers\SubscriptionController::class)->only(['store', 'destroy']);
    Route::get('channels/{channel}/videos', [\App\Http\Controllers\UploadVideoController::class, 'index'])->name('channel.upload');
    Route::post('channels/{channel}/videos', [\App\Http\Controllers\UploadVideoController::class, 'store']);
});

Auth::routes();
