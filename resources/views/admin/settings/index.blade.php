@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Thiết lập cơ bản") }}</title>
    <meta content="{{ hwa_page_title("Thiết lập cơ bản") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.select2.style')
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
                            <li class="breadcrumb-item active" aria-current="page">Thiết lập</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row d-flex justify-content-sm-center">
            <div class="col-sm-9">
                <form action="{{ route("{$path}.index") }}" class="form-horizontal" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Thông tin chung</h4>
                            <label class="mt-3 text-muted">Cài đặt thông tin trang web.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_name">Tên website:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}"
                                               name="site_name" id="site_name"
                                               placeholder="Nhập tên website"
                                               value="{{ old('site_name') ?? hwa_setting('site_name', hwa_app_name()) }}">
                                        @error('site_name')
                                        <p class="text-danger mt-2">{{ $errors->first('site_name') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="admin_title">Tiêu đề admin:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('admin_title') ? 'is-invalid' : '' }}"
                                               name="admin_title" id="admin_title"
                                               placeholder="Nhập tiêu đề admin"
                                               value="{{ old('admin_title') ?? hwa_setting('admin_title', hwa_app_name()) }}">
                                        @error('admin_title')
                                        <p class="text-danger mt-2">{{ $errors->first('admin_title') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="admin_email">Admin email:</label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('admin_email') ? 'is-invalid' : '' }}"
                                               name="admin_email" id="admin_email"
                                               placeholder="{{ "email@" . hwa_app_domain() }}"
                                               value="{{ old('admin_email') ?? hwa_setting('admin_email') }}">
                                        @error('admin_email')
                                        <p class="text-danger mt-2">{{ $errors->first('admin_email') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="time_zone">Múi giờ:</label>
                                        <select name="time_zone" id="time_zone"
                                                class="form-control select2">
                                            @foreach(hwa_timezone_list() as $timezoneKey => $timezoneValue)
                                                <option value="{{ $timezoneKey }}"
                                                        @if(hwa_setting('time_zone', config('app.time_zone')) == $timezoneKey) selected @endif>{{ $timezoneValue }}</option>
                                            @endforeach
                                        </select>
                                        @error('time_zone')
                                        <p class="text-danger mt-2">{{ $errors->first('time_zone') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Giao diện quản trị</h4>
                            <label class="mt-3 text-muted">Thiết lập giao diện quản trị như favicon, logo, ...</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="favicon">Favicon:</label>
                                                <input type="file" class="dropify"
                                                       name="favicon" {{ hwa_setting('favicon') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('favicon')) : "" }}>
                                                @error('favicon')
                                                <p class="text-danger mt-2">{{ $errors->first('favicon') }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="admin_logo_small">Logo nhỏ:</label>
                                                <input type="file" class="dropify"
                                                       name="admin_logo_small" {{ hwa_setting('admin_logo_small') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('admin_logo_small')) : "" }}>
                                                @error('admin_logo_small')
                                                <p class="text-danger mt-2">{{ $errors->first('admin_logo_small') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="admin_logo">Logo:</label>
                                                <input type="file" class="dropify"
                                                       name="admin_logo" {{ hwa_setting('admin_logo') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('admin_logo')) : "" }}>
                                                @error('admin_logo')
                                                <p class="text-danger mt-2">{{ $errors->first('admin_logo') }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="auth_bg">Nền đăng nhập admin:</label>
                                                <input type="file" class="dropify"
                                                       name="auth_bg" {{ hwa_setting('auth_bg') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('auth_bg')) : "" }}>
                                                @error('auth_bg')
                                                <p class="text-danger mt-2">{{ $errors->first('auth_bg') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Thanh toán VN Pay</h4>
                            <label class="mt-3 text-muted">Cài đặt thông tin cấu hình thanh toán VN Pay.</label>
                            <p class="mt-3 text-muted">Tài liệu tích hợp tại <a href="https://sandbox.vnpayment.vn/apis" target="_blank">https://sandbox.vnpayment.vn/apis</a></p>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="vnp_sandbox">Môi trường:</label>
                                        <select name="vnp_sandbox" id="vnp_sandbox" class="form-control">
                                            <option value="sandbox" {{ old('vnp_sandbox', hwa_setting('vnp_sandbox', '')) == 'sandbox' ? "selected" : "" }}>Sandbox - Thử nghiệm</option>
                                            <option value="product" {{ old('vnp_sandbox', hwa_setting('vnp_sandbox', '')) == 'product' ? "selected" : "" }}>Product</option>
                                        </select>
                                        @error('vnp_sandbox')
                                        <p class="text-danger mt-2">{{ $errors->first('vnp_sandbox') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="vnp_key">Mã Website(vnp_TmnCode):</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('vnp_key') ? 'is-invalid' : '' }}"
                                               name="vnp_key" id="vnp_key"
                                               placeholder="Nhập tên website"
                                               value="{{ old('vnp_key') ?? hwa_setting('vnp_key', '') }}">
                                        @error('vnp_key')
                                        <p class="text-danger mt-2">{{ $errors->first('vnp_key') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="vnp_secret">Secret Key(vnp_HashSecret):</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('vnp_secret') ? 'is-invalid' : '' }}"
                                               name="vnp_secret" id="vnp_secret"
                                               placeholder="Nhập tiêu đề admin"
                                               value="{{ old('vnp_secret') ?? hwa_setting('vnp_secret', '') }}">
                                        @error('vnp_secret')
                                        <p class="text-danger mt-2">{{ $errors->first('vnp_secret') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="mb-3 mt-3 text-center justify-content-center">
                                <button type="submit"
                                        class="btn btn-success waves-effect waves-light"><i
                                        class="bx bx-check-double font-size-16 align-middle me-2"></i> Lưu thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                </form>
            </div>
        </div>
        <!-- End row -->

    </div>
@endsection

@section('admin_script')
    @include('admin.includes.select2.script')
    @include('admin.includes.dropify.script')
@endsection
