@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['name'] ?? "Cập nhật khách hàng") }}</title>
    <meta content="{{ hwa_page_title($result['name'] ?? "Cập nhật khách hàng") }}" name="description"/>
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
                                <a href="{{ route("{$path}.index") }}">Khách hàng</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Cập nhật</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal" action="{{ route("{$path}.update", $result['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="name">Họ tên: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               name="name" id="name"
                                               placeholder="Nhập họ tên"
                                               value="{{ old('name') ?? (isset($result['name']) ? $result['name'] : '') }}">
                                        @if($errors->has('name'))
                                            <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                    <!-- End name -->

                                    <div class="mb-3">
                                        <label for="gender">Giới tính:</label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="">--- Chọn giới tính ---</option>
                                            <option value="male" {{ old('gender', $result['gender'] ?? '') == 'male' ? "selected" : "" }}>Nam</option>
                                            <option value="female" {{ old('gender', $result['gender'] ?? '') == 'female' ? "selected" : "" }}>Nữ</option>
                                        </select>
                                        @if($errors->has('gender'))
                                            <p class="text-danger mt-2">{{ $errors->first('gender') }}</p>
                                        @endif
                                    </div>
                                    <!-- End gender -->

                                    <div class="mb-3">
                                        <label for="phone">Số điện thoại:</label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                               name="phone" id="phone"
                                               placeholder="Nhập số điện thoại"
                                               value="{{ old('phone') ?? (isset($result['phone']) ? $result['phone'] : '') }}">
                                        @if($errors->has('phone'))
                                            <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                        @endif
                                    </div>
                                    <!-- End phone -->

                                    <div class="mb-3">
                                        <label for="email">Email: <span class="text-danger">*</span></label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               name="email" id="email"
                                               placeholder="Nhập email"
                                               value="{{ old('email', $result['email']) }}">
                                        @if($errors->has('email'))
                                            <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>
                                    <!-- End email -->

                                    <div class="mb-3">
                                        <label for="password">Mật khẩu: </label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password"
                                                   class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                                                   name="password"
                                                   value="{{ old('password') }}"
                                                   placeholder="Nhập mật khẩu"
                                                   aria-label="Password" aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                        @if($errors->has('password'))
                                            <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                                        @endif
                                    </div>
                                    <!-- End password -->
                                </div>
                                <!-- End col -->

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="username">Tên người dùng: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                               name="username" id="username"
                                               placeholder="Nhập tên người dùng"
                                               value="{{ old('username', $result['username']) }}">
                                        @if($errors->has('username'))
                                            <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                                        @endif
                                    </div>
                                    <!-- End username -->

                                    <div class="mb-3">
                                        <label for="avatar">Ảnh đại diện:</label>
                                        <input type="file" class="dropify"
                                               name="avatar" {{ (isset($result['avatar']) && !empty($result['avatar'])) ? 'data-default-file=' . hwa_image_url("customers", $result['avatar']) : "" }}>
                                        @if($errors->has('avatar'))
                                            <p class="text-danger mt-2">{{ $errors->first('avatar') }}</p>
                                        @endif
                                    </div>
                                    <!-- End avatar -->

                                    <div class="mb-3">
                                        <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                        <select name="active" id="active" class="form-control">
                                            <option
                                                value="1" {{ old('active') == '1' ? 'selected' : ((isset($result['active']) && $result['active'] == '1') ? 'selected' : '') }}>
                                                Hoạt động
                                            </option>
                                            <option
                                                value="0" {{ old('active') == '0' ? 'selected' : ((isset($result['active']) && $result['active'] == '0') ? 'selected' : '') }}>
                                                Bị khóa
                                            </option>
                                        </select>
                                        @if($errors->has('active'))
                                            <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                        @endif
                                    </div>
                                    <!-- End status -->
                                </div>
                                <!-- End col -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>

            <div class="row">
            @include('admin.includes.form_button')
            <!-- End button -->
            </div>
            <!-- End row -->
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')
@endsection
