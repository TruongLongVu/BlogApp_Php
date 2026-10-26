<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Quản Trị Viên', 'password' => Hash::make('12345678'), 'role' => 'admin']
        );

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'Sinh Viên HUIT', 'password' => Hash::make('12345678'), 'role' => 'user']
        );

        $catTech = Category::firstOrCreate(['name' => 'Công Nghệ'], ['description' => 'Tin tức công nghệ mới']);
        $catLife = Category::firstOrCreate(['name' => 'Đời Sống'], ['description' => 'Góc nhìn và kỹ năng sống']);
        $catEdu  = Category::firstOrCreate(['name' => 'Học Tập'], ['description' => 'Kinh nghiệm học tập lập trình']);

        $categories = [$catTech->id, $catLife->id, $catEdu->id];

        // Tạo 15 bài viết mẫu đa dạng lượt xem
        for ($i = 1; $i <= 15; $i++) {
            Post::firstOrCreate(
                ['title' => "Bài viết chuyên đề số {$i}: Hướng dẫn và kinh nghiệm thực tiễn"],
                [
                    'body' => "Đây là nội dung chi tiết của bài viết mẫu số {$i}. Bài viết chia sẻ các kiến thức lập trình web hiện đại với Laravel, xây dựng giao diện với Bootstrap 5 và quản lý cơ sở dữ liệu MySQL một cách hiệu quả.",
                    'views' => rand(10, 500),
                    'category_id' => $categories[array_rand($categories)],
                    'user_id' => $admin->id
                ]
            );
        }
    }
}