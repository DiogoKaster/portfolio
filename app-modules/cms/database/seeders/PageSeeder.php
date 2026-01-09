<?php

namespace Kaster\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Kaster\Cms\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'index',
                'is_landing' => true,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
            ],
            [
                'title' => 'Projects',
                'slug' => 'projects',
            ],
        ];

        foreach ($pages as $page) {
            Page::factory()->create($page);
        }
    }
}
