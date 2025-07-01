@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Đổi mật khẩu") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Đổi mật khẩu</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Đổi mật khẩu</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END CONTAINER-->
    </div>
@endsection

@section('content')
    <div class="tab-pane fade active show">
        <div class="card">
            <div class="card-header">
                <h3>Đổi mật khẩu</h3>
            </div>
            <div class="card-body">
                <form action="{{ route("{$path}.password") }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="old_password">Mật khẩu hiện tại: <span class="required">*</span></label>
                            <input class="form-control" name="old_password" id="old_password" type="password"
                                   value="{{ old('old_password') }}" placeholder="Nhập mật khẩu hiện tại">
                            @error('old_password')
                            <p class="mt-2 text-danger">{{ $errors->first('old_password') }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password">Mật khẩu mới: <span class="required">*</span></label>
                            <input class="form-control" name="password" id="password" type="password"
                                   value="{{ old('password') }}" placeholder="Nhập mật khẩu mới">
                            @error('password')
                            <p class="mt-2 text-danger">{{ $errors->first('password') }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password_confirmation">Xác nhận mật khẩu mới: <span
                                    class="required">*</span></label>
                            <input class="form-control" name="password_confirmation" id="password_confirmation"
                                   type="password" value="{{ old('password_confirmation') }}"
                                   placeholder="Xác nhận mật khẩu mới">
                            @error('password_confirmation')
                            <p class="mt-2 text-danger">{{ $errors->first('password_confirmation') }}</p>
                            @enderror
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-fill-out">Đổi mật khẩu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
