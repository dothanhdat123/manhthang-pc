@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Đăng nhập") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Đăng nhập</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Đăng nhập</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START LOGIN SECTION -->
        <div class="login_register_wrap section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-md-10">
                        <div class="login_wrap">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <h3 class="text-center">Đăng nhập</h3>
                                </div>
                                <form action="{{ route("{$path}.login") }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email"
                                               placeholder="Nhập email/tên tài khoản"
                                               value="{{ old('email') ?? ((hwa_demo_env() || hwa_local_env()) ? 'customer' : '') }}">
                                        @error('email')
                                        <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password"
                                               placeholder="Nhập mật khẩu" value="{{ old('password') ?? ((hwa_demo_env() || hwa_local_env()) ? 'admin123' : '') }}">
                                        @error('password')
                                        <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                                        @enderror
                                    </div>
                                    <div class="login_footer form-group">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox" name="remember_me"
                                                       id="exampleCheckbox1" {{ old('remember_me') == 'on' ? 'checked' : ((hwa_demo_env() || hwa_local_env()) ? 'checked' : '') }}>
                                                <label class="form-check-label" for="exampleCheckbox1"><span>Nhớ mật khẩu</span></label>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);">Quên mật khẩu?</a>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-fill-out btn-block">Đăng nhập</button>
                                    </div>
                                </form>
                                <div class="form-note text-center">Chưa có tài khoản? <a
                                        href="{{ route("{$path}.register") }}">Đăng ký ngay</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END LOGIN SECTION -->
    </div>
    <!-- END MAIN CONTENT -->
@endsection
