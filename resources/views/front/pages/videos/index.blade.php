@extends('front.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($video->editable())
                    <form action="{{ route('videos.update', $video->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                @endif

                <div class="card-header">{{ $video->title }}</div>

                <div class="card-body">
                    <video-js id="video" class="vjs-default-skin" controls preload="auto" width="640" height="268">
                        <source src='{{ asset(Storage::url("videos/{$video->id}/{$video->id}.m3u8")) }}' type="application/x-mpegURL">
                    </video-js>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mt-3">
                                @if($video->editable())
                                    <input type="text" name="title" id="" value="{{ $video->title }}" class="form-control">
                                @else
                                    {{ $video->title }}
                                @endif
                            </h4>

                            {{ $video->views }} {{ str_plural('view', $video->views) }}
                        </div>

                        <votes
                            :default_votes="{{ $video->votes }}"
                            entity_owner="{{ $video->channel->user_id }}"
                            entity_id="{{ $video->id }}">
                        </votes>
                    </div>


                    <hr>

                    <div>
                        @if ($video->editable())
                            <textarea name="description" id="" cols="3" rows="3" class="form-control">{{ $video->description }}</textarea>

                            <div class="text-right mt-4">
                                <button class="btn btn-info btn-sm" type="submit">Update video details</button>
                            </div>
                        @else
                            {{ $video->description }}
                        @endif
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="media">
                        <img src="https://" width="50" height="50"  alt="" class="rounded-circle mr-3">
                        <div class="media-body ml-2">
                            <h5 class="mt-0 mb-0">{{ $video->channel->name }}</h5>
                            <span class="small">Published on {{$video->created_at->toFormattedDateString()  }}</span>
                        </div>
                    </div>

                    <subscribe-button
                            class="mr-3"
                            :initial-subscriptions="{{ $video->channel->subscriptions }}"
                            :channel="{{ $video->channel }}">
                    </subscribe-button>
                </div>

                @if($video->editable())
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://vjs.zencdn.net/7.6.5/video-js.css" rel="stylesheet">

    <style>
        .vjs-default-skin {
            width: 100%;
        }

        .thumbs-up,
        .thumbs-down {
            width: 20px;
            height: 20px;
            cursor: pointer;
            fill: currentColor;
        }

        .thumbs-up-active,
        .thumbs-down-active {
            color: #3EA6FF;
        }

        .thumbs-down {
            margin-left: 1rem;
        }

    </style>
@endpush

@push('scripts')
    <script src='https://vjs.zencdn.net/7.6.5/video.js'></script>

    <script>
        const CURRENT_VIDEO = '{{ $video->id }}'
        const PLAYER = videojs('video');
        let viewLogged = false;

        PLAYER.on('timeupdate', function() {
            let percentagePlayed = Math.ceil(PLAYER.currentTime() / PLAYER.duration() * 100)

            if (percentagePlayed > 10 &&  ! viewLogged) {
                // console.log(percentagePlayed);
                axios.put('/videos/' + CURRENT_VIDEO);
                viewLogged = true;
            }
        });
    </script>
@endpush
