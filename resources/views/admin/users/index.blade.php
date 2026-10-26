@extends('layouts.main')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Quản lý người dùng</h2>
        <p class="text-muted mb-0">Xem, tìm kiếm, phân quyền và xóa mềm tài khoản.</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4">
            <div class="col-md-9">
                <input type="search" name="search" class="form-control" value="{{ $search ?? '' }}" placeholder="Tìm theo tên hoặc email...">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit"><i class="bi bi-search me-1"></i> Tìm kiếm</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Xóa</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Ngày tạo</th><th class="text-end">Thao tác</th></tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="d-flex gap-2">
                                @csrf @method('PATCH')
                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" @disabled($user->is(auth()->user()))>
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                        <td class="text-end">
                            @if(!$user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" data-confirm="Bạn có chắc muốn xóa mềm tài khoản này?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @else
                                <span class="text-muted small">Tài khoản hiện tại</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Không tìm thấy người dùng.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</div>
@endsection
