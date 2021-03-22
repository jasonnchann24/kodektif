<?php

namespace App\Observers;

use App\Models\Discussion\Discussion;
use Illuminate\Support\Str;

class DiscussionObserver
{

    /**
     * Handle the Discussion "saving" event.
     *
     * @param  \App\Models\Discussion $discussion
     * @return void
     */
    public function saving(Discussion $discussion)
    {
        $discussion->slug = Str::slug($discussion->title, '-');
    }

    /**
     * Handle the Discussion "created" event.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return void
     */
    public function created(Discussion $discussion)
    {
        // $discussion->slug =  Str::slug($discussion->title, '-');
    }

    /**
     * Handle the Discussion "updated" event.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return void
     */
    public function updated(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "deleted" event.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return void
     */
    public function deleted(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "restored" event.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return void
     */
    public function restored(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "force deleted" event.
     *
     * @param  \App\Models\Discussion\Discussion  $discussion
     * @return void
     */
    public function forceDeleted(Discussion $discussion)
    {
        //
    }
}
