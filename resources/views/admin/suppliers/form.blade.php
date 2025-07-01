@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật nhà cung cấp") : "Thêm nhà cung cấp" ) }}</title>
    <meta
        content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật nhà cung cấp") : "Thêm nhà cung cấp") }}"
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
                                <a href="{{ route("{$path}.index") }}">Nhà cung cấp</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? "Cập nhật" : "Thêm mới" }}
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="name">Tên nhà cung cấp: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               name="name" id="name"
                                               placeholder="Nhập tên nhà cung cấp"
                                               value="{{ old('name', ($result['name'] ?? '')) }}">
                                        @if($errors->has('name'))
                                            <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="phone">Số điện thoại: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               name="phone" id="phone"
                                               placeholder="Nhập số điện thoại"
                                               value="{{ old('phone', ($result['phone'] ?? '')) }}">
                                        @if($errors->has('phone'))
                                            <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="email">Email:</label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               name="email" id="email"
                                               placeholder="Nhập email"
                                               value="{{ old('email', ($result['email'] ?? '')) }}">
                                        @if($errors->has('email'))
                                            <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="website">Website: </label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}"
                                               name="website" id="website"
                                               placeholder="Nhập đường dẫn website"
                                               value="{{ old('website', ($result['website'] ?? '')) }}">
                                        @if($errors->has('website'))
                                            <p class="text-danger mt-2">{{ $errors->first('website') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="address">Địa chỉ: </label>
                                        <textarea name="address" id="address" cols="30" rows="5"
                                                  class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                                  placeholder="Nhập mô tả nhà cung cấp">{{ old('address', ($result['address'] ?? '')) }}</textarea>
                                        @if($errors->has('address'))
                                            <p class="text-danger mt-2">{{ $errors->first('address') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="description">Mô tả:</label>
                                        <textarea name="description" id="description" cols="30" rows="5"
                                                  class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                                  placeholder="Nhập mô tả nhà cung cấp">{{ old('description', ($result['description'] ?? '')) }}</textarea>
                                        @if($errors->has('description'))
                                            <p class="text-danger mt-2">{{ $errors->first('description') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="image">Hình ảnh:</label>
                                <input type="file" class="dropify"
                                       name="image" {{ (isset($result['logo']) && !empty($result['logo'])) ? 'data-default-file=' . hwa_image_url("suppliers", $result['logo']) : "" }}>
                                @if($errors->has('image'))
                                    <p class="text-danger mt-2">{{ $errors->first('image') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                <select name="active" id="active" class="form-control">
                                    <option value="1" {{ old('active', ($result['active'] ?? '')) == '1' ? 'selected' : '' }}>Bật</option>
                                    <option value="0" {{ old('active', ($result['active'] ?? '')) == '0' ? 'selected' : '' }}>Tắt</option>
                                </select>
                                @if($errors->has('active'))
                                    <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                @endif
                            </div>
                            <!-- End active -->
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <div class="row">
                @include('admin.includes.form_button')
            </div>
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')
@endsection
