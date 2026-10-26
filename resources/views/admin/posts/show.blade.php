@extends('layouts.main')
@section('title', $post->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active">{{ $post->category->name }}</li>
            </ol>
        </nav>

        <article class="bg-white p-4 p-md-5 rounded shadow-sm mb-4">
            <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
            
            <div class="text-muted d-flex gap-3 mb-4 border-bottom pb-3 small flex-wrap">
                <span>Tác giả: <strong>{{ $post->user->name }}</strong></span>
                <span>Ngày đăng: {{ $post->created_at->format('d/m/Y H:i') }}</span>
                <span>Lượt xem: <strong>{{ $post->views }}</strong></span>
                <span class="badge bg-primary align-self-center">{{ $post->category->name }}</span>
            </div>

            @if($post->image)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 450px; object-fit: cover;">
                </div>
            @endif

            <div class="article-body fs-5 lh-lg text-dark mb-4" style="white-space: pre-line;">
                {{ $post->body }}
            </div>
        </article>

        <!-- KHU VỰC BÌNH LUẬN -->
        <div class="card border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-4">Bình Luận ({{ $post->comments->count() }})</h4>

            <!-- Form gửi bình luận -->
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Viết bình luận của bạn:</label>
                        <textarea name="content" rows="3" class="form-control @error('content') is-invalid @enderror" placeholder="Chia sẻ suy nghĩ của bạn về bài viết này...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Gửi Bình Luận</button>
                </form>
            @else
                <div class="alert alert-light border mb-4">
                    Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để tham gia bình luận bài viết.
                </div>
            @endauth

            <hr>

            <!-- Danh sách các bình luận -->
            <div class="comments-list">
                @forelse($post->comments as $comment)
                    <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong class="text-dark">{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-secondary">{{ $comment->content }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" data-confirm="Bạn có chắc muốn xóa bình luận này?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <p class="text-muted my-3 text-center">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection