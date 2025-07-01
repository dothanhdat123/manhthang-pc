@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Đăng ký") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Đăng ký</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Đăng ký</li>
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
                                    <h3 class="text-center">Đăng ký</h3>
                                </div>
                                <form method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name"
                                               placeholder="Nhập họ tên"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                    <!-- End name -->

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username"
                                               placeholder="Nhập tên tài khoản"
                                               value="{{ old('username') }}">
                                        @error('username')
                                        <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                                        @enderror
                                    </div>
                                    <!-- End username -->

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email"
                                               placeholder="Nhập email"
                                               value="{{ old('email') }}">
                                        @error('email')
                                        <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @enderror
                                    </div>
                                    <!-- End email -->

                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password"
                                               placeholder="Nhập mật khẩu" value="{{ old('password') }}">
                                        @error('password')
                                        <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                                        @enderror
                                    </div>
                                    <!-- End password -->

                                    <div class="form-group">
    <label for="store_id">Chọn cửa hàng</label>
    <select name="store_id" class="form-control" required>
        <option value="">-- Chọn cửa hàng --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                {{ $store->name }}
            </option>
        @endforeach
    </select>
</div>


                                    <div class="login_footer form-group">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox2" {{ old('remember_me') == 'on' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="exampleCheckbox2"><span>Tôi đồng ý với các điều khoản &amp; Chính sách.</span></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-fill-out btn-block" name="login">Đăng ký</button>
                                    </div>
                                </form>

                                <div class="form-note text-center">Đã có tài khoản? <a href="{{ route("{$path}.register") }}">Đăng nhập ngay</a></div>
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
