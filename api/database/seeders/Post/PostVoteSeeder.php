<?php

namespace Database\Seeders\Post;

use App\Models\Post;
use App\Models\PostVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        foreach ($users as $user) {
            $randomPosts = $posts->random(random_int(1, 5));

            foreach ($randomPosts as $randomPost) {
                $voteCount = PostVote::where('user_id', $user->id)
                    ->where('post_id', $randomPost->id)
                    ->count();

                if ($voteCount < 1) {
                    $randomVote = (bool)random_int(0, 1);

                    PostVote::factory([
                        'user_id' => $user->id,
                        'post_id' => $randomPost->id,
                        'upvote' => $randomVote
                    ])->create();

                    $post = $posts->find($randomPost->id);

                    $randomVote ?
                        $post->upvote_count = $post->upvote_count + 1 :
                        $post->downvote_count = $post->downvote_count + 1;

                    $post->save();
                }
            }
        }
    }
}
