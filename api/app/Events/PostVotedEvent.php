<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostVotedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $upVote;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post, $upVote)
    {
        $this->post = $post;
        $this->upVote = $upVote;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('post-voted.' . $this->post->id);
    }
}
