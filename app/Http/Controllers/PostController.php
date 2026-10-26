<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Xem danh sách bài viết (kèm Tìm kiếm, Lọc chuyên mục, Phân trang & Sidebar)[cite: 7, 23]
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort' => ['nullable', 'in:latest,oldest,popular'],
        ]);

        $search = $filters['search'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $sort = $filters['sort'] ?? 'latest';

        // Lấy bài viết nhiều view nhất làm Featured Post (Hero)
        $featuredPost = Post::with(['category', 'user'])->orderByDesc('views')->first();

        $postsQuery = Post::with(['category', 'user'])
            ->where('status', 'published')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            });

        // Nếu không tìm kiếm/lọc, loại bài Hero ra khỏi danh sách bên dưới
        if (! $search && ! $categoryId && ! $dateFrom && ! $dateTo && $sort === 'latest' && $featuredPost) {
            $postsQuery->where('id', '!=', $featuredPost->id);
        }

        $postsQuery = match ($sort) {
            'oldest' => $postsQuery->oldest(),
            'popular' => $postsQuery->orderByDesc('views')->orderByDesc('created_at'),
            default => $postsQuery->latest(),
        };

        $posts = $postsQuery->paginate(6)->withQueryString();

        // Dữ liệu cho Sidebar
        $categories = Category::withCount('posts')->get();
        $popularPosts = Post::with('category')->orderByDesc('views')->take(5)->get();

        return view('posts.index', compact(
            'posts', 'search', 'categoryId', 'dateFrom', 'dateTo', 'sort',
            'categories', 'popularPosts', 'featuredPost'
        ));
    }

    // Chi tiết bài viết: Tăng lượt xem & nạp kèm comments
    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);
        $post->increment('views'); // Tự động tăng 1 view
        $post->load(['category', 'user', 'comments.user']);

        // Bài viết liên quan cùng chuyên mục[cite: 7]
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

        $categories = Category::withCount('posts')->get();
        $popularPosts = Post::orderByDesc('views')->take(5)->get();

        return view('posts.show', compact('post', 'relatedPosts', 'categories', 'popularPosts'));
    }

    // Quản trị bài viết (Admin)[cite: 6, 7]
    public function adminIndex(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $featuredPost = Post::with(['category', 'user'])->orderByDesc('views')->first();

        $postsQuery = Post::with(['category', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            });

        if (! $search && ! $categoryId && $featuredPost) {
            $postsQuery->where('id', '!=', $featuredPost->id);
        }

        $posts = $postsQuery->latest()->paginate(10)->withQueryString();
        $categories = Category::withCount('posts')->get();
        $popularPosts = Post::with('category')->orderByDesc('views')->take(5)->get();

        return view('admin.posts.index', compact(
            'posts',
            'search',
            'categoryId',
            'status',
            'featuredPost',
            'categories',
            'popularPosts',
        ));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'category_id.required' => 'Vui lòng chọn chuyên mục.',
            'body.min' => 'Nội dung tối thiểu 10 ký tự.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'body' => $request->body,
            'image' => $imagePath,
            'user_id' => $request->user()->id,
            'status' => 'published',
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết mới thành công!');
    }

    public function userCreate()
    {
        $categories = Category::all();

        return view('posts.create', compact('categories'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'body' => ['required', 'string', 'min:10'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return redirect()->route('my.posts')->with('success', 'Bài viết đã được gửi và đang chờ Admin duyệt.');
    }

    public function userPosts(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('posts.my-index', compact('posts'));
    }

    public function userEdit(Request $request, Post $post)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        abort_unless(in_array($post->status, ['draft', 'rejected'], true), 403);

        $categories = Category::all();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function userUpdate(Request $request, Post $post)
    {
        abort_unless($post->user_id === $request->user()->id, 403);
        abort_unless(in_array($post->status, ['draft', 'rejected'], true), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'body' => ['required', 'string', 'min:10'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $oldImage = $post->image;
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            ...$validated,
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('my.posts')->with('success', 'Đã cập nhật và gửi lại bài viết chờ Admin duyệt.');
    }

    public function updateStatus(Request $request, Post $post)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,pending,published,rejected,archived'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $post->update([
            'status' => $validated['status'],
            'approved_by' => $validated['status'] === 'published' ? $request->user()->id : null,
            'approved_at' => $validated['status'] === 'published' ? now() : null,
            'rejection_reason' => $validated['status'] === 'rejected' ? ($validated['rejection_reason'] ?? null) : null,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái bài viết.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $oldImage = $post->image;
        $newImage = $oldImage;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'body' => $request->body,
            'image' => $newImage,
        ]);

        // Chỉ dọn ảnh cũ khi đã lưu bản ghi thành công và có tải ảnh mới
        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết thành công!');
    }
}
