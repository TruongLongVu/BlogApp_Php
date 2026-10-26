<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Lưu bình luận mới của User
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ], [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.min' => 'Bình luận phải có ít nhất 3 ký tự.',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Bình luận đã được gửi thành công!');
    }

    // Xóa bình luận (Dành cho Admin hoặc chính người đã đăng bình luận)
    public function destroy(Request $request, Comment $comment)
    {
        $currentUser = $request->user();

        if (!$currentUser || ($currentUser->role !== 'admin' && $currentUser->id !== $comment->user_id)) {
            abort(403, 'Bạn không có quyền xóa bình luận này!');
        }

        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận!');
    }
}