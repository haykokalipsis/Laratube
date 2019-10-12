<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laratube\Channel;
use Laratube\Subscription;
use Laratube\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
        $user1 = factory(User::class)->create([
            'email' => 'haykokalipsis@gmail.com',
            'password' => '$2y$10$3vsblDfHwb15QOcvAgUw0uRQcpKTUE6R2mXE47WsvAUlQJnouQ3Qm'
        ]);

        $user2 = factory(User::class)->create([
            'email' => 'haykokalipsis@gnail.com',
            'password' => '$2y$10$3vsblDfHwb15QOcvAgUw0uRQcpKTUE6R2mXE47WsvAUlQJnouQ3Qm'
        ]);

        $channel1 = factory(Channel::class)->create([
            'user_id' => $user1->id
        ]);

        $channel2 = factory(Channel::class)->create([
            'user_id' => $user2->id
        ]);

        // Subscribe user1 and user2 to each other
        $channel1->subscriptions()->create([
            'user_id' => $user2->id
        ]);

        $channel2->subscriptions()->create([
            'user_id' => $user1->id
        ]);

        // create another 10000 subscriptions for each channel
        factory(Subscription::class, 100)->create([
            'channel_id' => $channel1->id
        ]);

        // create another 10000 subscriptions for each channel
        factory(Subscription::class, 100)->create([
            'channel_id' => $channel2->id
        ]);
    }
}
