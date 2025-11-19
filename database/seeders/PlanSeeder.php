<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::updateOrCreate(
            ['name' => 'Basic Plan'],
            [
                'price_monthly' => 49.00,
                'course_limit' => 5,
                'content_upload_limit' => 50,
                'student_limit' => 100
            ]
        );

        Plan::updateOrCreate(
            ['name' => 'Pro Plan'],
            [
                'price_monthly' => 99.00,
                'course_limit' => 999999,
                'content_upload_limit' => 999999,
                'student_limit' => 999999
            ]
        );
    }
}
