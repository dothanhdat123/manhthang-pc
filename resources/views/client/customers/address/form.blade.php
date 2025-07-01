@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title(isset($address) ? "Cập nhật địa chỉ #{$address['id']}" : "Thêm địa chỉ mới") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>{{ isset($address) ? "Cập nhật địa chỉ #{$address['id']}" : "Thêm địa chỉ mới" }}</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('client.customers.address.index') }}">Địa chỉ</a></li>
                        <li class="breadcrumb-item active">{{ isset($address) ? "Cập nhật địa chỉ #{$address['id']}" : "Thêm mới" }}</li>
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
                <h3>{{ isset($address) ? "Cập nhật địa chỉ #{$address['id']}" : "Thêm địa chỉ mới" }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($address) ? route("{$path}.update", ['id' => $address['id']]) : route("{$path}.store") }}" method="post">
                    @csrf
                    @if(isset($address))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name">Họ tên: <span class="required">*</span></label>
                            <input class="form-control" name="name" id="name" value="{{ old('name', $address['name'] ?? "") }}" type="text" placeholder="Nhập họ tên">
                            @error('name')
                            <p class="mt-2 text-danger">{{ $errors->first('name') }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label for="phone">Số điện thoại: <span class="required">*</span></label>
                            <input class="form-control" name="phone" id="phone" value="{{ old('phone', $address['phone'] ?? "") }}" type="number" placeholder="Nhập số điện thoại">
                            @error('phone')
                            <p class="mt-2 text-danger">{{ $errors->first('phone') }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label for="address">Địa chỉ: <span class="required">*</span></label>
                            <input class="form-control" name="address" id="address" value="{{ old('address', $address['address'] ?? "") }}" type="text" placeholder="Nhập địa chỉ">
                            @error('address')
                            <p class="mt-2 text-danger">{{ $errors->first('address') }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{ old('is_default', $address['is_default'] ?? 0) == '1' ? "checked" : "" }} name="is_default" value="1" id="is_default">
                                <label class="form-check-label" for="is_default">Sử dụng địa chỉ này làm mặc định.</label>
                            </div>
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
