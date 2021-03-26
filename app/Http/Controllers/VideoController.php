<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Requests\Videos\UpdateVideoRequest;
use Illuminate\Support\Facades\Storage;


class VideoController extends Controller
{
    public function updateViews(Video $video)
    {
        $video->increment('views');
        return response()->json();
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->only(['title', 'description']));
        return redirect()->back();
    }

    public function show(Video $video)
    {
        if (request()->wantsJson()) {
            return $video;
        }

        $this->get_public_urls($video->id);

        return view('front.pages.videos.index', compact('video'));
    }

    public function file_ext($filename)
    {
        return preg_match('~\.~', $filename) ? preg_replace('~^.*\.~', '', $filename) : '';
    }

    public function get_public_urls($video_id)
    {
        /*
         * When convertation of video is over, i store converted video materials to video_id/original/ directory, and dont edit them ever.
         * Whenever i enter this method, i take files from that original directory and change links to urls in their contents.
         * I change links to .ts files in original directory to their dropbox urls
         * After that i create/store corresponding playlist files (not .ts video files) in $video_id/ directory
         * Then i create main playlist file with replaced links to playlist files in $video_id/ directory
         * When i request a video main playlist, i take the created one with replaced urls from $video_id/ directory, not the original one
        */

        // Regular expressions.
        $sub_playlists_regexp = "~^{$video_id}_\d+_\d+\.m3u8~mU";
        $ts_regexp = "~^(?P<ts_links>{$video_id}_\d+_\d+\_[0-9]{5}\.ts)~mU";

        // Get all files with storage urls in videos original folder
        // For .m3u8 files (files with playlist info) get content
        $files =
            collect(Storage::files("FFMPEG/videos/{$video_id}/original"))
                ->mapWithKeys(function ($item) {
                    $data = [
                        'url' => Storage::url($item),
                        'path' => $item,
                        'name' => pathinfo($item, PATHINFO_BASENAME)
                    ];

                    if (($this->file_ext($item) == 'm3u8')) {
                        $data['content'] = Storage::get($item);
                    }
                    return [
                        $item => $data
                    ];
                });

        // File with main playlist info
        $main_playlist_file = $files["FFMPEG/videos/{$video_id}/original/{$video_id}.m3u8"];

        // Get links to sub-playlist files from main playlist file
        preg_match_all($sub_playlists_regexp, $main_playlist_file['content'], $sub_playlists_links);

        // Get sub-playlist files from files collection, and convert to array (for some reason)
        $sub_playlist_files = $files->whereIn('name', $sub_playlists_links[0])->toArray();


        // loop through sub-playlist files
        foreach ($sub_playlist_files as $key => $sub_playlist_file) {
            // Extract links to each sub-playlists .ts files, put those in its (current sub-playlist) ts_links array
            preg_match_all($ts_regexp, $sub_playlist_file['content'], $ts_links);
            $sub_playlist_files[$key]['ts_links'] =  $ts_links['ts_links'];

            // loop through current sub-playlists all ts_links,
            // string replace them with storage urls from corresponding items in files collection
            foreach ($sub_playlist_files[$key]['ts_links'] as $key_link => $ts_link) {
                $sub_playlist_files[$key]['content'] = str_replace(
                    $sub_playlist_files[$key]['ts_links'][$key_link],
                    $files["FFMPEG/videos/{$video_id}/original/{$ts_link}"]['url'],
                    $sub_playlist_files[$key]['content']
                );
            }

            // Create current sub-playlist file from its content value (already links replaced with dropbox urls) in storages (dropbox) corresponding videos path
            Storage::put(
                "FFMPEG/videos/{$video_id}/{$sub_playlist_file['name']}",
                $sub_playlist_files[$key]['content']
            );

            // Change current sub-playlist files url value to dropbox url, so we can link it in main playlist file
            $sub_playlist_files[$key]['url'] = Storage::url("FFMPEG/videos/{$video_id}/{$sub_playlist_file['name']}");
        }

        // Replace links to sub-playlist files to corresponding urls in main-playlist files content.
        foreach ($sub_playlist_files as $key => $sub_playlist_file) {
            $main_playlist_file['content'] = str_replace(
                $sub_playlist_file['name'],
                $sub_playlist_file['url'],
                $main_playlist_file['content']
            );
        }

        // Create main-playlist file from its content value (already links replaced with dropbox urls) in storages (dropbox) corresponding videos path
        Storage::put(
            "FFMPEG/videos/{$video_id}/{$video_id}.m3u8",
            $main_playlist_file['content']
        );

//        dd($main_playlist_file);


//        $cookie_file_path = $path . '/cookies/shipping-cookie' . $unique;
//        $content = file_get_contents($cookie_file_path);
//        $content = str_replace('12345', '77348', $content);
//        file_put_contents($cookie_file_path, $content);

//        dd($sub_playlist_files);

//        // Get main list file
//        $main_list_file = Storage::get("FFMPEG/videos/{$video_id}/{$video_id}.m3u8");

//        // Extract Sublists from main list file
//        $sublists_regexp = "~^(?P<sublists_links>{$video_id}_\d+_\d+\.m3u8)~mU";
//        preg_match_all($sublists_regexp, $main_list_file, $sublists_links);
//
//        // Get sublists files
//        $sub_playlist_files = [];
//        foreach ($sublists_links['sublists_links'] as $key => $list) {
//            $sublist_files[] = Storage::get("FFMPEG/videos/{$video_id}/{$list}");
//        }
//        $sublist_files[1];
//

        //
        /*
         * 1. Get Main List: Get main .m2u8 file (FFMPEG/videos/{$video_id}/{$video_id}.m3u8)
         * 2. Get Sublists: Read main list file contents, get all rows that preg match $video_id_{\d}+_{\d}+.m3u8
         * 3. Get Sublists ts files: Read each sublists file contents. get all rows that preg match $video_id_{\d}+_{\d}+_[0-9]{4}.ts
         * 4. Get .ts files urls:
         * 5.
         * */

        return view('front.pages.videos.get-public-urls', compact(['files']));
    }

    public function write($path)
    {
        $line=1; // номер строки, которую нужно изменить
        $replace="xxx1"; // на что нужно изменить

        $file = file($path);
        $file[$line-1] = $replace.PHP_EOL;
        file_put_contents($path, join('', $file));
    }

//    public function ()
//    {
//        $replace_data_in_file = preg_replace($pattern, $replacement, $getting_file_contents);
//        $writing_replaced_data = file_put_contents($file_name, $replace_data_in_file);
//    }

    public function write2()
    {
        $reading = fopen('myfile', 'r');
        $writing = fopen('myfile.tmp', 'w');
        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line,'string to replace')) {
                $line = "replacement line!\n";
                $replaced = true;
            }
            fputs($writing, $line);
        }

        fclose($reading); fclose($writing);
        // might as well not overwrite the file if we didn't replace anything

        if ($replaced)
        {
            rename('myfile.tmp', 'myfile');
        } else {
            unlink('myfile.tmp');
        }
    }
}
