@extends('layouts.main')
@section('title', 'Bài viết của tôi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><h2 class="fw-bold">Bài viết của tôi</h2><a href="{{ route('my.posts.create') }}" class="btn btn-primary">Gửi bài viết</a></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Tiêu đề</th><th>Chuyên mục</th><th>Trạng thái</th><th>Ngày gửi</th><th>Thao tác</th></tr></thead><tbody>
@php($statusLabels = ['published' => 'Đã xuất bản', 'pending' => 'Chờ duyệt', 'rejected' => 'Bị từ chối', 'draft' => 'Bản nháp', 'archived' => 'Đã lưu trữ'])
@forelse($posts as $post)<tr><td class="fw-semibold">{{ $post->title }}@if($post->status === 'rejected' && $post->rejection_reason)<div class="small text-danger">Lý do: {{ $post->rejection_reason }}</div>@endif</td><td>{{ $post->category->name }}</td><td><span class="badge bg-{{ ['published'=>'success','pending'=>'warning','rejected'=>'danger','draft'=>'secondary','archived'=>'dark'][$post->status] ?? 'secondary' }}">{{ $statusLabels[$post->status] ?? 'Không xác định' }}</span></td><td>{{ $post->created_at->format('d/m/Y') }}</td><td>@if(in_array($post->status, ['draft', 'rejected'], true))<a href="{{ route('my.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square me-1"></i> Chỉnh sửa</a>@else<span class="text-muted small">Không chỉnh sửa</span>@endif</td></tr>@empty<tr><td colspan="5" class="text-center py-4 text-muted">Bạn chưa gửi bài viết nào.</td></tr>@endforelse
</tbody></table></div><div class="p-3">{{ $posts->links() }}</div></div>
@endsection
