<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'description' => 'Learn web development technologies'],
            ['name' => 'Mobile Development', 'description' => 'iOS and Android development courses'],
            ['name' => 'Data Science', 'description' => 'Data analysis and machine learning'],
            ['name' => 'Business', 'description' => 'Business and entrepreneurship courses'],
            ['name' => 'Design', 'description' => 'Graphic and UI/UX design'],
            ['name' => 'Marketing', 'description' => 'Digital marketing and SEO'],
            ['name' => 'IT & Software', 'description' => 'Information technology courses'],
            ['name' => 'Personal Development', 'description' => 'Self-improvement and productivity'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
