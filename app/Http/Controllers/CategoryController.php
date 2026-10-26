<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Vui lòng nhập tên chuyên mục.',
            'name.unique' => 'Tên chuyên mục này đã tồn tại.'
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')->with('success', 'Thêm chuyên mục thành công!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật chuyên mục thành công!');
    }

    public function destroy(Category $category)
    {
        // Xóa mềm các bài viết và bình luận liên quan để không còn xuất hiện công khai.
        $category->posts()->withTrashed()->get()->each(function ($post) {
            $post->comments()->withTrashed()->delete();
            $post->delete();
        });
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa mềm chuyên mục và các bài viết liên quan!');
    }
}
