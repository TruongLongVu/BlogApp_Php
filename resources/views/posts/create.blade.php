@extends('layouts.main')
@section('title', 'Gửi bài viết')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card border-0 shadow-sm"><div class="card-header bg-primary text-white fw-bold">Gửi bài viết chờ duyệt</div>
        <div class="card-body">
            <p class="text-muted">Bài viết sẽ được Admin kiểm duyệt trước khi hiển thị công khai.</p>
            <form action="{{ route('my.posts.store') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="mb-3"><label class="form-label">Tiêu đề</label><input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Chuyên mục</label><select name="category_id" class="form-select @error('category_id') is-invalid @enderror"><option value="">-- Chọn chuyên mục --</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="mb-3"><label class="form-label">Ảnh đại diện</label><input type="file" name="image" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Nội dung</label><textarea name="body" rows="8" class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>@error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <button class="btn btn-primary">Gửi chờ duyệt</button>
            </form>
        </div>
    </div>
</div></div>
@endsection
