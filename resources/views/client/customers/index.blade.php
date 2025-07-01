@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Bảng điều khiểu") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Bảng điều khiểu</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Bảng điều khiểu</li>
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
                <h3>Bảng điều khiển</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Đơn hàng đang chờ</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ $card['pending'] ?? 0 }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Đơn hàng đang xử lý</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ $card['processing'] ?? 0 }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Đơn hàng đã hủy</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ $card['cancel'] ?? 0 }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Đơn hàng thất bại</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ $card['fail'] ?? 0 }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Đơn hàng thành công</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ $card['done'] ?? 0 }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <h5 class="font-size-14 mb-0">Tổng thanh toán</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4 class="text-center"> {{ number_format($card['total']) ?? 0 }} đ</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
