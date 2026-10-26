@extends('layouts.main')
@section('title', 'Chỉnh sửa bài viết')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card border-0 shadow-sm"><div class="card-header bg-warning fw-bold">Chỉnh sửa và gửi lại bài viết</div>
        <div class="card-body">
            <p class="text-muted">Sau khi cập nhật, bài viết sẽ chuyển về trạng thái “Chờ duyệt”.</p>
            <form action="{{ route('my.posts.update', $post) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                <div class="mb-3"><label class="form-label">Tiêu đề</label><input name="title" value="{{ old('title', $post->title) }}" class="form-control @error('title') is-invalid @enderror">@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Chuyên mục</label><select name="category_id" class="form-select @error('category_id') is-invalid @enderror">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Ảnh đại diện</label>@if($post->image)<img src="{{ asset('storage/' . $post->image) }}" class="d-block rounded mb-2" style="height:100px">@endif<input type="file" name="image" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Nội dung</label><textarea name="body" rows="8" class="form-control @error('body') is-invalid @enderror">{{ old('body', $post->body) }}</textarea>@error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="d-flex justify-content-end gap-2"><a href="{{ route('my.posts') }}" class="btn btn-secondary">Hủy</a><button class="btn btn-warning">Gửi lại chờ duyệt</button></div>
            </form>
        </div>
    </div>
</div></div>
@endsection
