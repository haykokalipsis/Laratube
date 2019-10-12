@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ $channel->name}}

                        <a href="{{ route('channel.upload', $channel->id) }}">Upload Videos</a>
                    </div>

                    <div class="card-body">
                        @if ($channel->belongsToAuthenticatedUser())
                            <form action="{{ route('channels.update', $channel->id) }}" method="Post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="form-group row justify-content-center">
                                    <div class="channel-avatar">

                                        <div class="channel-avatar-overlay" onclick="document.getElementById('image').click()" >
                                            <!-- Svg here -->
                                        </div>

                                        <img src="{{ $channel->getImage() }}" alt="Your channel image">
                                    </div>

                                    <input style="display: none;" type="file" name="image" id="image">
                                </div>

                                <div class="form-group">
                                    <h4 class="text-center">
                                        {{ $channel->name }}
                                    </h4>

                                    <p class="text-center">
                                        {{ $channel->description  }}
                                    </p>
                                </div>

                                <div class="text-center">
                                    <subscribe-button
                                            {{-- inline-template --}}
                                            :initial-subscriptions="{{ $channel->subscriptions }}"
                                            :channel="{{ $channel }}">
                                    </subscribe-button>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="form-control-label">name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $channel->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea name="description" id="description" rows="10" class="form-control">{{ $channel->description }}</textarea>
                                </div>

                                @if($errors->any())
                                    <ul class="list-group mb-5">
                                        @foreach($errors as $error)
                                            <li class="list-group-item text-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <button type="submit" class="btn btn-info">Update Channel</button>
                            </form>
                        @else
                            <div class="form-group row justify-content-center">
                                <div class="channel-avatar">

                                    <div class="channel-avatar-overlay">
                                        <!-- Svg here -->
                                    </div>

                                    <img src="{{ $channel->getImage() }}" alt="Your channel image">
                                </div>
                            </div>

                            <div class="form-group">
                                <h4 class="text-center">
                                    {{ $channel->name }}
                                </h4>

                                <p class="text-center">
                                    {{ $channel->description  }}
                                </p>
                            </div>

                            <div class="text-center">
                                {{-- <subscribe-button
                                        inline-template
                                        :initial-subscriptions="{{ $channel->subscriptions }}"
                                        :channel="{{ $channel }}">
                                    <button @click.prevent="onToggleSubscription" class="btn btn-danger" :disabled="loading">
                                        <template v-if="! loading">
                                            @{{ owner ? '' : subscribed ? 'Unsubscribe' : 'Subscribe' }}
                                            @{{ subscriptions.length }}
                                            @{{ owner ? 'Subscribers' : '' }}
                                        </template>

                                        <template v-else>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </template>
                                    </button>
                                </subscribe-button> --}}

                                <subscribe-button
                                        :initial-subscriptions="{{ $channel->subscriptions }}"
                                        :channel="{{ $channel }}">
                                </subscribe-button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
