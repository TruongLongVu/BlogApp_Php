<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();
        $totalCategories = Category::count();$totalUsers = User::count();
        $totalViews = Post::sum('views');$totalComments = Comment::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $pendingPosts = Post::where('status', 'pending')->count();

        // 5 bài viết mới nhất và 5 bình luận mới nhất
        $recentPosts = Post::with('category')->latest()->take(5)->get();$recentComments = Comment::with(['user', 'post'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPosts',
            'totalCategories',
            'totalUsers',
            'totalViews',
            'totalComments',
            'publishedPosts',
            'pendingPosts',
            'recentPosts',
            'recentComments'
        ));
    }
}
