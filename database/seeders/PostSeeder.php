<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::first();
        Post::create([
            'title' => 'First Post',
            'description' => 'This is the first post.',
            'contact_phone' => '0100000001',
            'admin_id' => $admin->id,
        ]);

        Post::create([
            'title' => 'Second Post',
            'description' => 'This is the second post.',
            'contact_phone' => '0100000002',
            'admin_id' => $admin->id,
        ]);

        Post::create([
            'title' => 'Third Post',
            'description' => 'This is the third post.',
            'contact_phone' => '0100000003',
            'admin_id' => $admin->id,
        ]);

        Post::create([
            'title' => 'Fourth Post',
            'description' => 'This is the fourth post.',
            'contact_phone' => '0100000004',
            'admin_id' => $admin->id,
        ]);
    }
}
