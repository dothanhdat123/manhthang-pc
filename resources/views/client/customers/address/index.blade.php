@extends('client.customers.layout')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Địa chỉ") }}</title>
@endsection

@section('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Địa chỉ</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Địa chỉ</li>
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
                <div class="float-left">
                    <h3>Địa chỉ</h3>
                </div>
                <div class="float-right">
                    <a href="{{ route("{$path}.store") }}" class="btn btn-fill-out btn-sm">Thêm địa chỉ mới</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">Địa chỉ</th>
                            <th class="text-center">Mặc định</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($address) && count($address) > 0)
                            @foreach($address as $item)
                                <tr>
                                    <td>
                                        <p>{{ $item['name'] }}</p>
                                        <p>{{ $item['phone'] }}</p>
                                        <p>{{ $item['address'] }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($item['is_default'] == 1)
                                            {{ 'Có' }}
                                        @else
                                            {{ 'Không' }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route("{$path}.update", ['id' => $item['id']]) }}" class="btn btn-fill-out btn-sm">Sửa</a>
                                        <a href="{{ route("{$path}.destroy", ['id' => $item['id']]) }}" class="btn btn-fill-out btn-sm">Xóa</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">Không có địa chỉ</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
