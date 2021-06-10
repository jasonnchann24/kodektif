<?php

namespace Database\Seeders;

use Database\Seeders\Article\ArticleLikeSeeder;
use Database\Seeders\Article\ArticleSeeder;
use Database\Seeders\Course\ChapterSeeder;
use Database\Seeders\Course\CourseSeeder;
use Database\Seeders\Discussion\DiscussionComment\DiscussionCommentReplySeeder;
use Database\Seeders\Discussion\DiscussionComment\DiscussionCommentSeeder;
use Database\Seeders\Discussion\DiscussionComment\DiscussionCommentVoteSeeder;
use Database\Seeders\Discussion\DiscussionSeeder;
use Database\Seeders\Discussion\DiscussionVoteSeeder;
use Database\Seeders\Discussion\FollowDiscussionSeeder;
use Database\Seeders\Post\PostComment\PostCommentReplySeeder;
use Database\Seeders\Post\PostComment\PostCommentSeeder;
use Database\Seeders\Post\PostComment\PostCommentVoteSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\Post\PostVoteSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $start = microtime(true);
        if (config('app.env') != 'production') {

            $this->call([
                UserProfileSeeder::class,
                RoleSeeder::class,
                CategorySeeder::class,
                LanguageSeeder::class,

                ArticleSeeder::class,
                ArticleLikeSeeder::class,

                PostSeeder::class,
                PostVoteSeeder::class,
                PostCommentSeeder::class,
                PostCommentVoteSeeder::class,
                PostCommentReplySeeder::class,

                DiscussionSeeder::class,
                DiscussionVoteSeeder::class,
                DiscussionCommentSeeder::class,
                DiscussionCommentVoteSeeder::class,
                DiscussionCommentReplySeeder::class,
                FollowDiscussionSeeder::class,

                CourseSeeder::class,
                ChapterSeeder::class
            ]);
        } else {
            $this->call([
                RoleSeeder::class,
                CategorySeeder::class,
                LanguageSeeder::class,
            ]);
        }

        $end = microtime(true);
        $time = number_format(($end - $start) * 1000, 2);
        $info = 'Seeded database in ' . $time . ' ms';
        $this->command->info($info);
    }
}
