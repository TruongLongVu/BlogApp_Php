<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== 'admin') {
            return back()->with('error', 'Không thể hạ quyền tài khoản Admin đang đăng nhập.');
        }

        $user->update($validated);

        return back()->with('success', 'Cập nhật vai trò người dùng thành công!');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Không thể xóa tài khoản Admin đang đăng nhập.');
        }

        $user->delete();

        return back()->with('success', 'Đã xóa mềm người dùng thành công!');
    }
}
