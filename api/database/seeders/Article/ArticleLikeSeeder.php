<?php

namespace Database\Seeders\Article;

use App\Models\ArticleLike;
use Illuminate\Database\Seeder;

class ArticleLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleLike::factory()->count(100)->create();
    }
}
