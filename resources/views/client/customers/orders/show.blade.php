@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Chi tiết đơn hàng #{$result['id']}") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>{{ "Chi tiết đơn hàng #{$result['id']}" }}</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('client.customers.orders.index') }}">Đơn hàng</a></li>
                        <li class="breadcrumb-item active">{{ "Chi tiết đơn hàng #{$result['id']}" }}</li>
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
                <h3>{{ "Chi tiết đơn hàng #{$result['id']}" }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-sm-6">
                        <img src="{{ hwa_setting('site_logo') ? hwa_image_url("system", hwa_setting('site_logo_dark')) : "shopwise/assets/images/logo_dark.png" }}" alt="logo" height="40"/>
                    </div>
                    <div class="col-sm-6 text-right">
                        <p>Mã đơn hàng: #{{ $result['id'] }}</p>
                        <p>Ngày đặt: {{ \Carbon\Carbon::parse($result['created_at'])->format('H:i d/m/Y') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6 text-left">
                        <h6 class="card-title font-weight-bold">Thông tin đơn hàng</h6>
                        <p>Trạng thái: {!! hwa_order_active($result['active']) !!}</p>
                        <p>Phương thức thanh toán: {!! hwa_order_payment_method($result['payment_method']) !!}</p>
                        <p>Trạng thái thanh toán: {!! hwa_order_payment_status($result['payment_status']) !!}</p>
                        <p>Tổng tiền: {{ number_format($result['total'] ?? 0) }} đ</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h6 class="card-title font-weight-bold">Thông tin khách hàng</h6>
                        <p>Họ tên: {{ $result['customer_address']['name'] ?? "Khách hàng" }}</p>
                        <p>Số điện thoại: {{ $result['customer_address']['phone'] ?? "SĐT" }}</p>
                        <p>Địa chỉ: {{ $result['customer_address']['address'] ?? "Địa chỉ" }}</p>
                    </div>
                </div>

                <div class="table-responsive mt-5">
                    <h6 class="card-title font-weight-bold">Chi tiết</h6>
                    <table class="table mt-3">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Hình ảnh</th>
                            <th class="text-center">Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Tổng tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($result['order_details']) && count($result['order_details']) > 0)
                            @foreach($result['order_details'] as $item)
                                <tr>
                                    <td class="text-center">{{ $item['id'] }}</td>
                                    <td class="text-center">
                                        <img src="{{ hwa_image_url("products/thumbs", $item['product']['thumb']) }}" alt="" height="100">
                                    </td>
                                    <td class="text-center">{{ $item['name'] . "(". $item['sku'] .")" }}</td>
                                    <td class="text-center">{{ number_format($item['price'] ?? 0) }} đ</td>
                                    <td class="text-center">{{ $item['qty'] }}</td>
                                    <td class="text-center">{{ number_format($item['total'] ?? 0) }} đ</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">Không có sản phẩm</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="row d-flex justify-content-center mt-5">
                    <a href="{{ route("{$path}.export_detail", ['id' => $result['id']]) }}" class="btn btn-fill-out btn-sm">Xuất hóa đơn</a>
                    @if(($result['active'] == 'pending' || $result['active'] == 'processing') && $result['payment_method'] == 'cod')
                        <a href="{{ route("{$path}.cancel", ['id' => $result['id']]) }}" class="btn btn-dark btn-sm">Hủy đơn hàng</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
