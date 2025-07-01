@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật danh mục sản phẩm") : "Thêm danh mục sản phẩm" ) }}</title>
    <meta
        content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật danh mục sản phẩm") : "Thêm danh mục sản phẩm") }}"
        name="description"/>
@endsection
@section('admin_style')
    @include('admin.includes.dropify.style')
    @include('admin.includes.select2.style')
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
                                <a href="{{ route("{$path}.index") }}">Danh mục sản phẩm</a>
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
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Tên danh mục: <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           name="name" id="name"
                                           placeholder="Nhập tên danh mục"
                                           value="{{ old('name') ?? (isset($result['name']) ? $result['name'] : '') }}">
                                    @if($errors->has('name'))
                                        <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="description">Mô tả:</label>
                                    <textarea name="description" id="description" cols="30" rows="4"
                                              class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                              placeholder="Nhập mô tả danh mục">{{ old('description') ?? (isset($result['description']) ? $result['description'] : '') }}</textarea>
                                    @if($errors->has('description'))
                                        <p class="text-danger mt-2">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id">Danh mục cha</label>
                                    <select name="parent_id" id="parent_id" class="form-control select2">
                                        <option value="">=== ROOT ===</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{ $category['id'] }}" {{ old('parent_id') == $category['id'] ? 'selected' : ((isset($result['parent_id']) && $result['parent_id'] === $category['id']) ? 'selected' : '') }}>{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image">Hình ảnh:</label>
                                    <input type="file" class="dropify"
                                           name="image" {{ (isset($result['images']) && !empty($result['images'])) ? 'data-default-file=' . hwa_image_url("categories", $result['images']) : "" }}>
                                    @if($errors->has('image'))
                                        <p class="text-danger mt-2">{{ $errors->first('image') }}</p>
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

                                @include('admin.includes.form_button')
                            </div>
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
    @include('admin.includes.select2.script')
@endsection
