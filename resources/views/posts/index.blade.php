@extends('layouts.main')
@section('title', 'Trang chủ tin tức')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold mb-0">Tin Mới Cập Nhật</h2>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Xóa bộ lọc</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('home') }}" method="GET">
            <div class="row g-3">
                <div class="col-lg-4">
                    <label for="search" class="form-label">Từ khóa</label>
                    <input id="search" type="text" name="search" class="form-control" placeholder="Tiêu đề hoặc nội dung..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="category_id" class="form-label">Chuyên mục</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($categoryId ?? '') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="sort" class="form-label">Sắp xếp</label>
                    <select id="sort" name="sort" class="form-select">
                        <option value="latest" @selected(($sort ?? 'latest') === 'latest')>Mới nhất</option>
                        <option value="oldest" @selected(($sort ?? '') === 'oldest')>Cũ nhất</option>
                        <option value="popular" @selected(($sort ?? '') === 'popular')>Nhiều lượt xem</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i> Áp dụng bộ lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" 
                        alt="{{ $post->title }}" 
                        class="card-img-top" 
                        style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="bi bi-image fs-2"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge bg-primary mb-2">{{ $post->category->name }}</span>
                    <h5 class="card-title fw-bold text-truncate">{{ $post->title }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($post->body, 90) }}</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm w-100">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Không tìm thấy bài viết nào phù hợp.</p>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $posts->links() }}
</div>
@endsection
