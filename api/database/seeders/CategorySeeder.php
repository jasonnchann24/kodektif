<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/seeders/data/category_seeder.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Category::create([
                'id' => $obj->id,
                'name' => $obj->name,
                'parent_id' => $obj->parent_id,
            ]);
        }
    }
}
