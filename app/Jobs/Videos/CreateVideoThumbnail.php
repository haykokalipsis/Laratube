<?php

namespace App\Jobs\Videos;

use FFMpeg;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;

class CreateVideoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FFMpeg::fromDisk('public')
//            ->open('FFMPEG/channels/0ba21459-4e7f-4041-95bc-bc62187334e3/videos/KoesIx09gybZHtHwrelj3yvUZHTrlHE3HFtDCg8D.mkv')
            ->open($this->video->path)
//            ->open('http://laratube.test/FFMPEG/channels/0ba21459-4e7f-4041-95bc-bc62187334e3/videos/5KcGbYinlOkkVgRt0ylWr0otREHGQFqczCYsf6Qz.mkv')
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('public')
            ->save("/FFMPEG/thumbnails/{$this->video->id}.png");

        $this->video->update([
//            'thumbnail' => "/thumbnails/{$this->video->id}.png"
            'thumbnail' => "/FFMPEG/thumbnails/{$this->video->id}.png"
//            'thumbnail' => Storage::disk("public")->url("/FFMPEG/thumbnails/{$this->video->id}.png")
        ]);
    }
}
