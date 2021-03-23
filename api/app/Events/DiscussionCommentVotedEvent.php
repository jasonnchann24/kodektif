<?php

namespace App\Events;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscussionCommentVotedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $upVote;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DiscussionComment $comment, bool $upVote)
    {
        $this->comment = $comment;
        $this->upVote = $upVote;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('discussion-comment-voted.' . $this->comment->id);
    }
}
