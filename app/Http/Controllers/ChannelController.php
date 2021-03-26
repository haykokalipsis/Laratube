<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use App\Models\Channel;
use Illuminate\Support\Facades\Storage;

//use App\Http\Requests\Channel\UpdateChannelRequest;

class ChannelController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(Channel $channel)
    {
        $videos = $channel->videos()->paginate(2);
        return view('front.pages.channels.show-or-edit', compact(['channel', 'videos']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Channel $channel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        if ($request->hasFile('image')) {
            $channel->clearMediaCollection('images');
            $channel->addMediaFromRequest('image')
                ->toMediaCollection('images');
        }

        $channel->update($request->except(['image']));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
