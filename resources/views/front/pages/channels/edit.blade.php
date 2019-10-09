@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $channel->name}}</div>

                    <div class="card-body">
                        <form action="{{ route('channels.update', $channel->id) }}" method="Post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <div onclick="document.getElementById('image').click()" class="channel-avatar">
                                    <div class="channel-avatar-overlay">
                                        <!-- Svg here -->
                                    </div>
                                </div>

                                <input style="display: none;" type="file" name="image" id="image">
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="form-control-label">name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $channel->name }}">
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-control-label">Description</label>
                                <textarea name="description" id="description" rows="10" class="form-control">{{ $channel->description }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-info">Update Channel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection