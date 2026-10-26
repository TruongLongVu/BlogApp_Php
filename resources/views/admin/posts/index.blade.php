@extends('layouts.main')
@section('title', 'Quản lý bài viết - HUIT NEWS')

@section('content')
<style>
    .admin-posts-page .posts-heading-icon {
        width: 3rem;
        height: 3rem;
    }

    .admin-posts-page .post-thumbnail {
        width: 4.5rem;
        height: 3.25rem;
        object-fit: cover;
    }

    .admin-posts-page .table > :not(caption) > * > * {
        padding: 1rem 0.875rem;
    }

    .admin-posts-page .post-title-link {
        max-width: 28rem;
    }

    @media (max-width: 767.98px) {
        .admin-posts-page .table > :not(caption) > * > * {
            padding: 0.75rem;
        }
    }
</style>

<div class="admin-posts-page">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="posts-heading-icon d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-journal-richtext fs-4"></i>
            </div>
            <div>
                <div class="text-uppercase text-primary fw-semibold small mb-1">Bảng điều khiển</div>
                <h1 class="h3 fw-bold mb-1">Quản lý bài viết</h1>
                <p class="text-muted mb-0">Tìm kiếm, theo dõi và cập nhật nội dung của bạn.</p>
            </div>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary fw-semibold shadow-sm align-self-md-start">
            <i class="bi bi-plus-lg me-1"></i> Tạo bài viết
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary" style="width: 2.75rem; height: 2.75rem;">
                        <i class="bi bi-file-earmark-text fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Bài viết phù hợp</div>
                        <div class="fs-4 fw-bold lh-1 mt-1">{{ number_format($posts->total()) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success" style="width: 2.75rem; height: 2.75rem;">
                        <i class="bi bi-folder2-open fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Chuyên mục</div>
                        <div class="fs-4 fw-bold lh-1 mt-1">{{ number_format($categories->count()) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning" style="width: 2.75rem; height: 2.75rem;">
                        <i class="bi bi-eye fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Lượt xem trang này</div>
                        <div class="fs-4 fw-bold lh-1 mt-1">{{ number_format($posts->getCollection()->sum('views')) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-funnel text-primary"></i>
                <h2 class="h6 fw-bold mb-0">Tìm và lọc bài viết</h2>
            </div>
            <form action="{{ route('admin.posts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-lg-6">
                    <label for="post-search" class="form-label small fw-semibold">Từ khóa</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                        <input
                            type="search"
                            id="post-search"
                            name="search"
                            class="form-control"
                            placeholder="Tìm theo tiêu đề hoặc nội dung..."
                            value="{{ $search ?? '' }}"
                        >
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-4">
                    <label for="post-category" class="form-label small fw-semibold">Chuyên mục</label>
                    <select id="post-category" name="category_id" class="form-select">
                        <option value="">Tất cả chuyên mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->posts_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5 col-lg-2">
                    <label for="post-status" class="form-label small fw-semibold">Trạng thái</label>
                    <select id="post-status" name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        @foreach(['pending' => 'Chờ duyệt', 'published' => 'Đã xuất bản', 'rejected' => 'Từ chối', 'draft' => 'Bản nháp', 'archived' => 'Lưu trữ'] as $value => $label)
                            <option value="{{ $value }}" @selected(($status ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Lọc
                    </button>
                    @if(($search ?? '') || ($categoryId ?? ''))
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary" aria-label="Xóa bộ lọc" title="Xóa bộ lọc">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 px-3 px-lg-4 py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <div>
                    <h2 class="h5 fw-bold mb-1">Danh sách bài viết</h2>
                    <p class="text-muted small mb-0">
                        @if($posts->total() > 0)
                            Đang hiển thị {{ number_format($posts->firstItem()) }}–{{ number_format($posts->lastItem()) }}
                            trong {{ number_format($posts->total()) }} bài viết
                        @else
                            Không có bài viết nào để hiển thị
                        @endif
                    </p>
                </div>
                <span class="badge rounded-pill bg-light text-secondary border align-self-start align-self-sm-center">
                    <i class="bi bi-arrow-down-up me-1"></i> Mới nhất
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-uppercase text-muted">
                        <th scope="col" class="text-center" style="width: 64px;">ID</th>
                        <th scope="col" style="width: 96px;">Ảnh</th>
                        <th scope="col">Bài viết</th>
                        <th scope="col">Chuyên mục</th>
                        <th scope="col" class="text-center">Lượt xem</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" class="text-center" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center text-muted fw-semibold">#{{ $post->id }}</td>
                            <td>
                                @if($post->image)
                                    <img
                                        src="{{ asset('storage/' . $post->image) }}"
                                        alt="{{ $post->title }}"
                                        class="post-thumbnail rounded-3 shadow-sm"
                                    >
                                @else
                                    <div class="post-thumbnail rounded-3 bg-light text-secondary d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" target="_blank" rel="noopener noreferrer" class="post-title-link text-decoration-none text-dark fw-semibold d-block text-truncate" title="{{ $post->title }}">
                                    {{ $post->title }}
                                </a>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $post->user->name ?? 'Admin' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 fw-medium">
                                    {{ $post->category->name }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-nowrap text-muted">
                                    <i class="bi bi-eye me-1"></i>{{ number_format($post->views) }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <span class="small fw-medium">{{ $post->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.posts.status', $post) }}" class="d-flex gap-1">
                                    @csrf @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach(['pending' => 'Chờ duyệt', 'published' => 'Đã xuất bản', 'rejected' => 'Từ chối', 'draft' => 'Bản nháp', 'archived' => 'Lưu trữ'] as $value => $label)
                                            <option value="{{ $value }}" @selected($post->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa bài viết" aria-label="Chỉnh sửa {{ $post->title }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" data-confirm="Bạn có chắc chắn muốn xóa bài viết này không?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa bài viết" aria-label="Xóa {{ $post->title }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5">
                                <div class="d-flex flex-column align-items-center text-center px-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-light text-secondary mb-3" style="width: 4rem; height: 4rem;">
                                        <i class="bi bi-journal-x fs-3"></i>
                                    </div>
                                    <h3 class="h6 fw-bold mb-1">
                                        {{ ($search ?? '') || ($categoryId ?? '') ? 'Không tìm thấy bài viết phù hợp' : 'Chưa có bài viết nào' }}
                                    </h3>
                                    <p class="text-muted small mb-3">
                                        {{ ($search ?? '') || ($categoryId ?? '') ? 'Thử thay đổi từ khóa hoặc chuyên mục để tìm kết quả khác.' : 'Tạo bài viết đầu tiên để bắt đầu xây dựng nội dung.' }}
                                    </p>
                                    @if(($search ?? '') || ($categoryId ?? ''))
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary btn-sm">Xóa bộ lọc</a>
                                    @else
                                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Tạo bài viết
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($posts->hasPages())
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
            <small class="text-muted">
                Trang {{ $posts->currentPage() }} / {{ $posts->lastPage() }}
            </small>
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
