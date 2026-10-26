<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_posts_page_renders_with_and_without_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Technology']);
        $otherCategory = Category::create(['name' => 'Lifestyle']);

        Post::create([
            'title' => 'Matching article',
            'body' => 'This article contains the searchable keyword.',
            'category_id' => $category->id,
            'user_id' => $admin->id,
            'views' => 10,
        ]);

        Post::create([
            'title' => 'Unrelated article',
            'body' => 'This article has different content.',
            'category_id' => $otherCategory->id,
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->withSession(['success' => 'Thay đổi đã được lưu.'])
            ->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSee('Unrelated article')
            ->assertSee('id="successToast"', false)
            ->assertSee('Thay đổi đã được lưu.')
            ->assertSee('id="confirmationModal"', false)
            ->assertSee('data-confirm="Bạn có chắc chắn muốn xóa bài viết này không?"', false)
            ->assertDontSee('onsubmit="return confirm(', false);

        $response = $this->get(route('admin.posts.index', [
            'search' => 'searchable keyword',
            'category_id' => $category->id,
        ]));

        $response
            ->assertOk()
            ->assertViewHas('posts', function ($posts) {
                return $posts->total() === 1
                    && $posts->first()->title === 'Matching article';
            });
    }
}
