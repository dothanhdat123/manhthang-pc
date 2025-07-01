@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Thông tin cá nhân") }}</title>
    <meta content="{{ hwa_page_title("Thông tin cá nhân") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <img class="img-thumbnail rounded-circle avatar-xl" style="min-height: 200px; min-width: 200px;"
                                 src="{{ (isset($user['avatar']) && !empty($user['avatar'])) ? hwa_image_url("users", $user['avatar']) : "assets/images/users/user.png" }}"
                                 alt="{{ $user['full_name'] ?? "{$user['first_name']} {$user['last_name']}" }}">
                        </div>
                        <table class="table table-nowrap mb-0">
                            <tbody>
                            <tr>
                                <th scope="row">Họ tên:</th>
                                <td>{{ $user['full_name'] ?? "{$user['first_name']} {$user['last_name']}" }}</td>
                            </tr>
                            @if(isset($user['phone']) && !empty($user['phone']))
                                <tr>
                                    <th scope="row">Số điện thoại:</th>
                                    <td>{{ $user['phone'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">E-mail:</th>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Trạng thái:</th>
                                <td>
                                    @if($user['active'] == 1)
                                        <span class='badge badge-pill badge-soft-success font-size-11' style='line-height: unset!important;'>Hoạt động</span>
                                    @else
                                        <span class='badge badge-pill badge-soft-danger font-size-11' style='line-height: unset!important;'>Bị khóa</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End information -->

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center text-uppercase mb-3">Thông tin tài khoản</h4>
                        <form class="form-horizontal" action="{{ route("{$path}.index") }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="first_name">Tên: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                       name="first_name" id="first_name" placeholder="Nhập tên"
                                       value="{{ old('first_name') ?? (isset($user['first_name']) ? $user['first_name'] : '') }}">
                                @if($errors->has('first_name'))
                                    <p class="text-danger mt-2">{{ $errors->first('first_name') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="last_name">Họ, đệm: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                       name="last_name" id="last_name" placeholder="Nhập họ, đệm"
                                       value="{{ old('last_name') ?? (isset($user['last_name']) ? $user['last_name'] : '') }}">
                                @if($errors->has('last_name'))
                                    <p class="text-danger mt-2">{{ $errors->first('last_name') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="username">Tên người dùng: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                       name="username" id="username" placeholder="Nhập tên người dùng"
                                       value="{{ old('username') ?? (isset($user['username']) ? $user['username'] : '') }}">
                                @if($errors->has('username'))
                                    <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="email">Email: <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       name="email" id="email" placeholder="Nhập email"
                                       value="{{ old('email') ?? (isset($user['email']) ? $user['email'] : '') }}">
                                @if($errors->has('email'))
                                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="phone">Số điện thoại:</label>
                                <input type="number"
                                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       name="phone" id="phone" placeholder="Nhập số điện thoại"
                                       value="{{ old('phone') ?? (isset($user['phone']) ? $user['phone'] : '') }}">
                                @if($errors->has('phone'))
                                    <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="avatar">Ảnh đại diện:</label>
                                <input type="file" class="dropify"
                                       name="avatar" {{ (isset($user['avatar']) && !empty($user['avatar'])) ? 'data-default-file=' . hwa_image_url("users", $user['avatar']) : "" }}>
                                @if($errors->has('avatar'))
                                    <p class="text-danger mt-2">{{ $errors->first('avatar') }}</p>
                                @endif
                            </div>

                        @include('admin.includes.form_button')
                        <!-- End button -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- End update information -->

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center text-uppercase mb-3">Đổi mật khẩu</h4>
                        <form class="form-horizontal" action="{{ route("{$path}.password") }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password">Mật khẩu cũ: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('old_password') ? 'is-invalid' : '' }}"
                                           name="old_password"
                                           value="{{ old('old_password') }}"
                                           placeholder="Nhập mật khẩu cũ" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-old-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('old_password')
                                <p class="text-danger">{{ $errors->first('old_password') }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password">Mật khẩu mới: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                                           name="password"
                                           value="{{ old('password') }}"
                                           placeholder="Nhập mật khẩu mới" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('password')
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation">Xác nhận mật khẩu: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('password_confirmation') ? 'is-invalid' : '' }}"
                                           name="password_confirmation"
                                           value="{{ old('password_confirmation') }}"
                                           placeholder="Nhập lại mật khẩu mới" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-confirmation-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('password_confirmation')
                                <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                @enderror
                            </div>

                            @include('admin.includes.form_button')
                            <!-- End button -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- End update password -->
        </div>


    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')

    <script type="text/javascript">
        $("#password-confirmation-addon").on("click", function () {
            0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this).siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
        });

        $("#password-old-addon").on("click", function () {
            0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this).siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
        });
    </script>
@endsection
