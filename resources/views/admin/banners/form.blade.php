@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật banner") : "Thêm banner" ) }}</title>
    <meta
        content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật banner") : "Thêm banner") }}"
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
                                <a href="{{ route("{$path}.index") }}">Banner</a>
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
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="image">Hình ảnh: <span class="text-danger">*</span></label>
                                        <input type="file" class="dropify"
                                               name="image" {{ (isset($result['image']) && !empty($result['image'])) ? 'data-default-file=' . hwa_image_url("banners", $result['image']) : "" }}>
                                        @if($errors->has('image'))
                                            <p class="text-danger mt-2">{{ $errors->first('image') }}</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="title">Tiêu đề: </label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                               name="title" id="title"
                                               placeholder="Nhập tiêu đề"
                                               value="{{ old('title') ?? (isset($result['title']) ? $result['title'] : '') }}">
                                        @if($errors->has('title'))
                                            <p class="text-danger mt-2">{{ $errors->first('title') }}</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="sub_title">Tiêu đề phụ: </label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('sub_title') ? 'is-invalid' : '' }}"
                                               name="sub_title" id="sub_title"
                                               placeholder="Nhập tiêu đề phụ"
                                               value="{{ old('sub_title') ?? (isset($result['sub_title']) ? $result['sub_title'] : '') }}">
                                        @if($errors->has('sub_title'))
                                            <p class="text-danger mt-2">{{ $errors->first('sub_title') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="banner_type">Kiểu banner: <span class="text-danger">*</span></label>
                                        <select name="banner_type" id="banner_type" class="form-control">
                                            <option value="">--- Chọn kiểu banner ---</option>
                                            @foreach($types as $type)
                                                <option
                                                    value="{{ $type['code'] }}" {{ old('banner_type') == $type['code'] ? 'selected' : ((isset($result['banner_type']) && $result['banner_type'] === $type['code']) ? 'selected' : '') }}>{{ $type['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="url">Đường dẫn: </label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}"
                                               name="url" id="url"
                                               placeholder="Nhập đường dẫn"
                                               value="{{ old('url') ?? (isset($result['url']) ? $result['url'] : '') }}">
                                        @if($errors->has('url'))
                                            <p class="text-danger mt-2">{{ $errors->first('url') }}</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="target">Target:</label>
                                        <select name="target" id="target" class="form-control">
                                            <option value="">--- Chọn target ---</option>
                                            <option value="_self" {{ old('target', (isset($result) ? $result['target'] : '')) == '_self' ? 'selected' : '' }}>_self</option>
                                            <option value="_blank" {{ old('target', (isset($result) ? $result['target'] : '')) == '_blank' ? 'selected' : '' }}>_blank</option>
                                        </select>
                                        @if($errors->has('target'))
                                            <p class="text-danger mt-2">{{ $errors->first('target') }}</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="sort">Thứ tự: </label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('sort') ? 'is-invalid' : '' }}"
                                               name="sort" id="sort"
                                               placeholder="Nhập vị trí"
                                               value="{{ old('sort', (isset($result['sort']) ? $result['sort'] : 0)) }}">
                                        @if($errors->has('sort'))
                                            <p class="text-danger mt-2">{{ $errors->first('sort') }}</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                        <select name="active" id="active" class="form-control">
                                            <option
                                                value="1" {{ old('active') == '1' ? 'selected' : ((isset($result['active']) && $result['active'] == '1') ? 'selected' : '') }}>
                                                Bật
                                            </option>
                                            <option
                                                value="0" {{ old('active') == '0' ? 'selected' : ((isset($result['active']) && $result['active'] == '0') ? 'selected' : '') }}>
                                                Tắt
                                            </option>
                                        </select>
                                        @if($errors->has('active'))
                                            <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                        @endif
                                    </div>
                                    <!-- End name -->
                                </div>
                            </div>

                            <di class="row">
                                @include('admin.includes.form_button')
                            </di>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')
@endsection
