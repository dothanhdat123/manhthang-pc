@extends('admin.layouts.auth')

@section('admin_head')
    <title>{{ hwa_page_title('Đăng nhập quản trị') }}</title>
    <meta content="{{ hwa_page_title('Đăng nhập quản trị') }}" name="description"/>
@endsection

@section('admin_auth_content')
    <div class="my-auto">

        <div>
            <h5 class="text-primary">Welcome Back !</h5>
            <p class="text-muted">Đăng nhập hệ thống {{ hwa_app_name() }}.</p>
        </div>

        <div class="mt-4">
            <form action="{{ route("{$path}.login") }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Tài khoản: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}"
                           id="username" name="email"
                           value="{{ old('email') ?? ((hwa_demo_env() || hwa_local_env()) ? 'admin' : '') }}"
                           placeholder="Nhập email/tên tài khoản">
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="float-end">
                        <a href="{{ route("{$path}.password.forget") }}" class="text-muted">Quên mật khẩu?</a>
                    </div>
                    <label class="form-label">Mật khẩu: <span class="text-danger">*</span></label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                               name="password"
                               value="{{ old('password') ?? ((hwa_demo_env() || hwa_local_env()) ? 'admin123' : '') }}"
                               placeholder="Nhập mật khẩu" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light " type="button" id="password-addon"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                    <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-check"
                           name="remember_me" {{ old('remember_me') == 'on' ? 'checked' : ((hwa_demo_env() || hwa_local_env()) ? 'checked' : '') }}>
                    <label class="form-check-label" for="remember-check">
                        Nhớ mật khẩu
                    </label>
                </div>

                <div class="mt-3 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Đăng nhập</button>
                </div>

                @if (hwa_setting('social_login_facebook_enable', false) || hwa_setting('social_login_google_enable', false) || hwa_setting('social_login_twitter_enable', false) || hwa_setting('social_login_linkedin_enable', false))
                    <div class="mt-4 text-center">
                        <h5 class="font-size-14 mb-3">Đăng nhập với</h5>
                        <ul class="list-inline">
                            @if (hwa_setting('social_login_google_enable', false))
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.auth.social.redirect', 'google') }}"
                                       class="social-list-item bg-danger text-white border-danger">
                                        <i class="mdi mdi-google"></i>
                                    </a>
                                </li>
                            @endif
                            @if (hwa_setting('social_login_facebook_enable', false))
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.auth.social.redirect', 'facebook') }}"
                                       class="social-list-item bg-primary text-white border-primary">
                                        <i class="mdi mdi-facebook"></i>
                                    </a>
                                </li>
                            @endif
                            @if (hwa_setting('social_login_twitter_enable', false))
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.auth.social.redirect', 'twitter') }}"
                                       class="social-list-item bg-info text-white border-info">
                                        <i class="mdi mdi-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if (hwa_setting('social_login_linkedin_enable', false))
                                <li class="list-inline-item">
                                    <a href="{{ route('admin.auth.social.redirect', 'linkedin') }}"
                                       class="social-list-item bg-dark text-white border-dark">
                                        <i class="mdi mdi-linkedin"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </form>
            <div class="mt-5 text-center">
                <p>Chưa có tài khoản? <a href="{{ route("{$path}.register") }}" class="fw-medium text-primary">Đăng ký
                        ngay</a></p>
            </div>
        </div>
    </div>
@endsection
