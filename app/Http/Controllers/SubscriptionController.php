<?php

namespace Laratube\Http\Controllers;

use Laratube\Channel;
use Laratube\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Channel $channel)
    {
        $newSubscription = $channel->subscriptions()->create([
            'user_id' => auth()->user()->id
        ]);

        return response()->json($newSubscription, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Laratube\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Subscription $subscription)
    {
        $subscription->delete();
        
        return response()->json([], 200);
    }
}
