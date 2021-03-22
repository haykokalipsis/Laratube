@extends('front.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <channel-uploads inline-template :channel="{{ $channel }}">
                    <template>

                        <div v-if=" ! selected" class="card p-3 d-flex justify-content-center align-items-center">
                            <img style="cursor: pointer" onclick="document.getElementById('video-files').click()" src="{{ asset('images/youtube-icon.svg') }}" alt="">
                            {{--                    <object type="image/svg+xml" data="{{ asset('icons/youtube-icimagesg') }}">Your browser does not support SVG</object>--}}
                            <input multiple style="display: none;" type="file" name="" id="video-files" ref="videos" @change="onUpload">
                            <p class="text-center">Upload Videos</p>
                        </div>

                        <div v-else class="card p-3">
                            <div v-for="video in videos" class="my-4">
                                <div class="progress mb-3">
                                    <!-- If the video has percentage property, it means that it is being processed (converted) on server -->
                                    <!-- If it does'nt, it means the video is being uploaded to server, and progress array has this videos progress -->
                                    <!-- If percentage property exists on the video show it (the video is being processed on server), else show the progress (the video is being uploaded to server)-->
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="{width: `${video.percentage || progress[video.name]}%`}" aria-valuemin="0" aria-valuemax="100">
                                        <!-- 1) If percentage doesent exist, write 'Uploading' 2)if percentage exists check its amount. 3) If its 100 write 'processing completed', else write 'processing'.-->
                                        @{{ video.percentage ? video.percentage === 100 ? 'Video Processing Completed!' : 'Processing' : 'Uploading' }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div v-if=" ! video.thumbnail" class="d-flex justify-content-center align-items-center" style="height: 180px; color: white; font-size: 18px; background: #808080;">
                                            Loading thumbnail ...
                                        </div>

{{--                                        <img v-else :src="video.thumbnail" style="width: 100%;" alt="">--}}
                                        <img v-else :src="video.thumbnail" style="width: 100%;" alt="">
{{--                                        <img v-else :src="{{Storage()->url('video.thumbnail')}}" style="width: 100%;" alt="">--}}
                                    </div>

                                    <div class="col-md-4">
                                        <!-- If video has percentage property, thus is not uploading but is processed on the server -->
                                        <a v-if="video.percentage && video.percentage === 100" target="_blank" :href="`/videos/${video.id}`">
                                            @{{ video.title }}
                                        </a>

                                        <h4 v-else class="text-center">
                                            <!-- The uploading file/video has name property. The processed and returned video has title field/property -->
                                            @{{ video.title || video.name }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </channel-uploads>

            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
