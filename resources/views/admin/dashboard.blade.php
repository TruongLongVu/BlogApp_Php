@extends('layouts.main')
@section('title', 'Admin Dashboard - Tổng quan hệ thống')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Bảng Điều Khiển Tổng Quan</h3>
    <span class="text-muted small">Cập nhật lúc: {{ now()->format('d/m/Y H:i') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold">Tổng Bài Viết</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalPosts }}</h2>
                </div>
                <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-dark shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><h6 class="text-uppercase mb-1 small fw-bold">Chờ duyệt</h6><h2 class="mb-0 fw-bold">{{ $pendingPosts }}</h2></div>
                <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold">Chuyên Mục</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalCategories }}</h2>
                </div>
                <i class="bi bi-folder fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold">Người Dùng</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalUsers }}</h2>
                </div>
                <i class="bi bi-people fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small fw-bold">Bình Luận</h6>
                    <h2 class="mb-0 fw-bold">{{ $totalComments }}</h2>
                </div>
                <i class="bi bi-chat-dots fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Bài viết mới cập nhật</h5>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả bài viết</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th>Lượt xem</th>
                    <th>Ngày đăng</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPosts as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td class="fw-semibold">{{ $p->title }}</td>
                    <td><span class="badge bg-secondary">{{ $p->category->name }}</span></td>
                    <td><span class="badge bg-info text-dark">{{ $p->views }} lượt</span></td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-3 text-muted">Chưa có bài viết nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
