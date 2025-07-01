@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Chi tiết tài khoản") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Chi tiết tài khoản</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Chi tiết tài khoản</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END CONTAINER-->
    </div>
@endsection

@section('content')
    <div class="tab-pane fade active show" id="account-detail" role="tabpanel" aria-labelledby="account-detail-tab">
        <div class="card">
            <div class="card-header">
                <h3>Chi tiết tài khoản</h3>
            </div>
            <div class="card-body">
                <form action="{{ route("{$path}.profile") }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name">Họ tên: <span class="required">*</span></label>
                            <input class="form-control" name="name" id="name" value="{{ old('name', $customer['name'] ?? "") }}" type="text" placeholder="Nhập họ tên">
                            @error('name')
                                <p class="mt-2 text-danger">{{ $errors->first('name') }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="username">Tên tài khoản: <span class="required">*</span></label>
                            <input class="form-control" name="username" id="username" value="{{ old('username', $customer['username'] ?? "") }}" type="text" placeholder="Nhập tên tài khoản">
                            @error('username')
                            <p class="mt-2 text-danger">{{ $errors->first('username') }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email">Email: <span class="required">*</span></label>
                            <input class="form-control" name="email" type="email" value="{{ old('email', $customer['email'] ?? "") }}" placeholder="Nhập email">
                            @error('email')
                            <p class="mt-2 text-danger">{{ $errors->first('email') }}</p>
                            @enderror
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-fill-out">Lưu thông tin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
