<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langs = [
            [
                'iso_639_1' => 'en',
                'name' => 'English',
                'slug' => 'english'
            ],
            [
                'iso_639_1' => 'id',
                'name' => 'Bahasa Indonesia',
                'slug' => 'indonesian'
            ],
        ];

        array_map(fn ($lang) => Language::create($lang), $langs);
    }
}
