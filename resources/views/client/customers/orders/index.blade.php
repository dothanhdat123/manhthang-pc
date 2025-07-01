@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Đơn hàng") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Đơn hàng</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Đơn hàng</li>
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
                <h3>Đơn hàng</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">Mã đơn hàng</th>
                            <th class="text-center">Ngày đặt</th>
                            <th class="text-center">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($results) && count($results) > 0)
                            @foreach($results as $item)
                                <tr>
                                    <td class="text-center">#{{ $item['id'] }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y') }}</td>
                                    <td class="text-center">{{ number_format($item['total'] ?? 0) }} đ</td>
                                    <td class="text-center">{!! hwa_order_active($item['active']) !!}</td>
                                    <td class="text-center">
                                        <a href="{{ route("{$path}.show", ['id' => $item['id']]) }}" class="btn btn-fill-out btn-sm">Chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Không có đơn hàng</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
