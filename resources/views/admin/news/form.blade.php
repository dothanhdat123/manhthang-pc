@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật bài viết") : "Thêm bài viết" ) }}</title>
    <meta
        content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật bài viết") : "Thêm bài viết") }}"
        name="description"/>
@endsection
@section('admin_style')
    @include('admin.includes.dropify.style')
@endsection

@section('admin_content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><span><i
                                            class="bx bxs-home-circle"></i> Bảng điều khiển</span></a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route("{$path}.index") }}">Tin tức</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? ($result['full_name'] ?? "Cập nhật") : "Thêm mới" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal"
              action="{{ isset($result) ? route("{$path}.update", $result['id']) : route("{$path}.store") }}"
              method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($result))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title">Tiêu đề: <span class="text-danger">*</span></label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 191 ký tự</span>
                                </div>
                                <input type="text"
                                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                       name="title" id="title"
                                       placeholder="Nhập tiêu đề bài viết"
                                       value="{{ old('title') ?? (isset($result['title']) ? $result['title'] : '') }}">
                                @if($errors->has('title'))
                                    <p class="text-danger mt-2">{{ $errors->first('title') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="description">Mô tả: <span class="text-danger">*</span></label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 255 ký tự</span>
                                </div>
                                <textarea name="description" id="description" cols="30" rows="4"
                                          class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                          placeholder="Nhập mô tả ngắn bài viết">{{ old('description') ?? (isset($result['description']) ? $result['description'] : '') }}</textarea>
                                @if($errors->has('description'))
                                    <p class="text-danger mt-2">{{ $errors->first('description') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="description">Nội dung: <span
                                        class="text-danger">*</span></label>
                                <textarea name="content" id="content" cols="30"
                                          rows="10">{!! old('content', (isset($result['content']) ? $result['content'] : '')) !!}</textarea>
                                @if($errors->has('content'))
                                    <p class="text-danger mt-2">{{ $errors->first('content') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id">Chuyên mục: <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">--- Chọn chuyên mục ---</option>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category['id'] }}" {{ old('category_id', (isset($result['category_id']) ? $result['category_id'] : '')) ? 'selected' : '' }}>{{ $category['name'] }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('category_id'))
                                    <p class="text-danger mt-2">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                <select name="active" id="active" class="form-control">
                                    <option value="draft" {{ old('active', (isset($result['active']) ? $result['active'] : '')) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                    <option value="published" {{ old('active', (isset($result['active']) ? $result['active'] : '')) == 'published' ? 'selected' : '' }}>Xuất bản</option>
                                    <option value="unpublished" {{ old('active', (isset($result['active']) ? $result['active'] : '')) == 'unpublished' ? 'selected' : '' }}>Hủy đăng</option>
                                </select>
                                @if($errors->has('active'))
                                    <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="image">Hình ảnh: <span class="text-danger">*</span></label>
                                <input type="file" class="dropify"
                                       name="image" {{ (isset($result['image']) && !empty($result['image'])) ? 'data-default-file=' . hwa_image_url("news/thumb", $result['image']) : "" }}>
                                @if($errors->has('image'))
                                    <p class="text-danger mt-2">{{ $errors->first('image') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="seo_title">Tiêu đề SEO: </label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 191 ký tự</span>
                                </div>
                                <input type="text"
                                       class="form-control {{ $errors->has('seo_title') ? 'is-invalid' : '' }}"
                                       name="seo_title" id="seo_title"
                                       placeholder="Nhập tiêu đề SEO"
                                       value="{{ old('seo_title') ?? (isset($result['seo_title']) ? $result['seo_title'] : '') }}">
                                @if($errors->has('seo_title'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_title') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="seo_description">Mô tả SEO:</label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 255 ký tự</span>
                                </div>
                                <textarea name="seo_description" id="seo_description" cols="30" rows="6"
                                          class="form-control {{ $errors->has('seo_description') ? 'is-invalid' : '' }}"
                                          placeholder="Nhập mô tả SEO">{{ old('seo_description') ?? (isset($result['seo_description']) ? $result['seo_description'] : '') }}</textarea>
                                @if($errors->has('seo_description'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_description') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="seo_keyword">Từ khóa SEO:</label>
                                <textarea name="seo_keyword" id="seo_keyword" cols="30" rows="4"
                                          class="form-control {{ $errors->has('seo_keyword') ? 'is-invalid' : '' }}"
                                          placeholder="Nhập từ khóa SEO (cách nhau bởi dấu phẩy)">{{ old('seo_keyword') ?? (isset($result['seo_keyword']) ? $result['seo_keyword'] : '') }}</textarea>
                                @if($errors->has('seo_keyword'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_keyword') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                @include('admin.includes.form_button')
            </div>
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')

    <script src={{ url('ckeditor/ckeditor.js') }}></script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser', ['type' => 'News']) }}',
        });
    </script>
    @include('ckfinder::setup')
@endsection
