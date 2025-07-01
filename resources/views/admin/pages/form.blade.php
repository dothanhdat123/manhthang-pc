@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['name'] ?? "Cập nhật trang tĩnh") }}</title>
    <meta content="{{ hwa_page_title($result['name'] ?? "Cập nhật trang tĩnh") }}" name="description"/>
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
                                <a href="{{ route("{$path}.index") }}">Trang tĩnh</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $result['full_name'] ?? "Cập nhật" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal"
              action="{{ route("{$path}.update", $result['id']) }}"
              method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name">Tên: <span class="text-danger">*</span></label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 191 ký tự</span>
                                </div>
                                <input type="text"
                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       name="name" id="name"
                                       placeholder="Nhập tên trang"
                                       value="{{ old('name') ?? (isset($result['name']) ? $result['name'] : '') }}">
                                @if($errors->has('name'))
                                    <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
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
                                <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                <select name="active" id="active" class="form-control">
                                    <option value="1" {{ old('active', (isset($result['active']) ? $result['active'] : '')) == '1' ? 'selected' : '' }}>Bật</option>
                                    <option value="0" {{ old('active', (isset($result['active']) ? $result['active'] : '')) == '0' ? 'selected' : '' }}>Tắt</option>
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
            filebrowserBrowseUrl: '{{ route('ckfinder_browser', ['type' => 'Pages']) }}',
        });
    </script>
    @include('ckfinder::setup')
@endsection
