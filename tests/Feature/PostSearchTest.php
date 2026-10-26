<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_post_body(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Technology']);

        Post::create([
            'title' => 'Matching article',
            'body' => 'This article contains a distinctive searchable phrase.',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        Post::create([
            'title' => 'Unrelated article',
            'body' => 'This article has different content.',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $this->get('/?search=distinctive%20searchable')
            ->assertOk()
            ->assertSee('Matching article')
            ->assertDontSee('Unrelated article');
    }
}
