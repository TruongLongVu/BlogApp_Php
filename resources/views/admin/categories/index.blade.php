@extends('layouts.main')
@section('title', 'Quản lý Chuyên mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">Quản Trị Chuyên Mục</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ Thêm Chuyên Mục Mới</a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Tên chuyên mục</th>
                    <th>Mô tả</th>
                    <th>Số bài viết</th>
                    <th style="width: 160px;" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td class="fw-bold text-primary">{{ $cat->name }}</td>
                    <td>{{ $cat->description ?? 'Không có mô tả' }}</td>
                    <td><span class="badge bg-secondary">{{ $cat->posts_count }} bài</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning me-1">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" data-confirm="Xóa danh mục sẽ xóa toàn bộ bài viết con. Bạn có chắc không?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Chưa có chuyên mục nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">
    {{ $categories->links() }}
</div>
@endsection