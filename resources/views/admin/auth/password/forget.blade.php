@extends('admin.layouts.auth')
@section('admin_head')
    <title>{{ hwa_page_title('Đặt lại mật khẩu hệ thống') }}</title>
    <meta content="{{ hwa_page_title('Đặt lại mật khẩu hệ thống') }}" name="description"/>
@endsection

@section('admin_auth_content')
    <div class="my-auto">

        <div>
            <h5 class="text-primary">Đặt lại mật khẩu hệ thống</h5>
            <p class="text-muted">Khôi phục mật khẩu với {{ hwa_app_name() }}.</p>
        </div>

        <div class="mt-4">
            <div class="alert alert-success text-center mb-4" role="alert">
                Nhập Email và hướng dẫn sẽ được gửi cho bạn!
            </div>
            <form action="{{ route("{$path}.password.forget") }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}"
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Nhập email">
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mt-3 d-grid">
                    <button type="submit"
                            class="btn btn-primary waves-effect"><i
                            class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Đặt lại
                    </button>
                </div>
            </form>
            <div class="mt-5 text-center">
                <p>Nhớ tài khoản? <a href="{{ route("{$path}.login") }}" class="fw-medium text-primary">Đăng nhập</a></p>
            </div>
        </div>
    </div>
@endsection

