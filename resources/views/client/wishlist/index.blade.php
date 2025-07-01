@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Yêu thích") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Yêu thích</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Yêu thích</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive wishlist_table">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="product-thumbnail">Hình ảnh</th>
                                    <th class="product-name">Tên sản phẩm</th>
                                    <th class="product-price">Giá bán</th>
                                    <th class="product-stock-status">Trạng thái</th>
                                    <th class="product-add-to-cart"></th>
                                    <th class="product-remove">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($results->count() > 0)
                                    @foreach($results as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="{{ route('client.product.show', ['slug' => $item['product']['slug']]) }}"><img
                                                        src="{{ hwa_image_url("products/thumbs", $item['product']['thumb']) }}"
                                                        alt="{{ $item['product']['name'] }}"></a>
                                            </td>
                                            <td class="product-name" data-title="{{ $item['product']['name'] }}"><a
                                                    href="{{ route('client.product.show', ['slug' => $item['product']['slug']]) }}">{{ $item['product']['name'] }}</a></td>
                                            <td class="product-price"
                                                data-title="Price">{{ number_format($item['product']['price'] ?? 0) }} đ
                                            </td>
                                            <td class="product-stock-status" data-title="Stock Status">
                                                @if($item['product']['quantity'] > 0)
                                                    <span class="badge badge-pill badge-success">Còn hàng</span>
                                                @else
                                                    <span class="badge badge-pill badge-danger">Hết hàng</span>
                                                @endif
                                            </td>
                                            <td class="product-add-to-cart"><a href="{{ route('client.wishlist.wishlist_to_cart', ['id' => $item['id']]) }}" class="btn btn-fill-out"><i
                                                        class="icon-basket-loaded"></i></a></td>
                                            <td class="product-remove" data-title="Remove"><a href="{{ route("{$path}.destroy", ['id' => $item['id']]) }}"><i
                                                        class="ti-close"></i></a></td>
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
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
