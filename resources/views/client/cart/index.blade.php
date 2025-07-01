@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Giỏ hàng") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Giỏ hàng</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Giỏ hàng</li>
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
                            <form action="{{ route('client.cart.update') }}" method="post">
                                @csrf
                                @method('PUT')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="product-thumbnail">Hình ảnh</th>
                                        <th class="product-name">Tên sản phẩm</th>
                                        <th class="product-price">Giá bán</th>
                                        <th class="product-stock-status">Số lượng</th>
                                        <th class="product-remove">Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($results) > 0)
                                        @foreach($results as $item)
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="{{ route('client.product.show', ['slug' => $item['product_slug']]) }}"><img
                                                            src="{{ $item['product_thumb'] }}"
                                                            alt="{{ $item['product_name'] }}"></a>
                                                </td>
                                                <td class="product-name" data-title="{{ $item['product_name'] }}"><a
                                                        href="{{ route('client.product.show', ['slug' => $item['product_slug']]) }}">{{ $item['product_name'] }}</a>
                                                </td>
                                                <td class="product-price"
                                                    data-title="Price">{{ number_format($item['product_price'] ?? 0) }} đ
                                                </td>
                                                <input type="hidden" name="products[{{ $item['id'] }}]" value="{{ $item['product_id'] }}">
                                                <td class="product-quantity text-center" data-title="Quantity">
                                                    <div class="d-flex justify-content-center quantity">
                                                        <input type="button" value="-" class="minus">
                                                        <input type="text" name="items[{{ $item['id'] }}]" value="{{ old('qty', $item['quantity'] ?? 1) }}" title="Qty" class="qty" size="4">
                                                        <input type="button" value="+" class="plus">
                                                    </div>
                                                </td>
                                                <td class="product-remove" data-title="Remove">
                                                    <a href="{{ route("{$path}.destroy", ['id' => $item['id']]) }}"><i
                                                            class="ti-close"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">Giỏ hàng trống</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    @if(count($results) > 0)
                                        <tfoot>
                                        <tr>
                                            <td colspan="6" class="px-0">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-lg-4 col-md-6 mb-3 mb-md-0">

                                                    </div>
                                                    <div class="col-lg-8 col-md-6 text-left text-md-right">
                                                        <button type="submit" class="btn btn-fill-out btn-sm">Cập nhật giỏ hàng</button>
                                                        <a class="btn btn-fill-out btn-sm" href="{{ route('client.cart.remove_all') }}">Xóa giỏ hàng</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                @if(count($results) > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="medium_divider"></div>
                            <div class="divider center_icon"><i class="ti-shopping-cart-full"></i></div>
                            <div class="medium_divider"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 p-md-4">
                                <div class="heading_s1 mb-3">
                                    <h6>Cart Totals</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="cart_total_label">Tạm tính</td>
                                            <td class="cart_total_amount">{{ number_format($subtotal ?? 0) }} đ</td>
                                        </tr>
                                        <tr>
                                            <td class="cart_total_label">Phí vận chuyển</td>
                                            <td class="cart_total_amount">{{ number_format(30000) }} đ</td>
                                        </tr>
                                        <tr>
                                            <td class="cart_total_label">Tổng tiền</td>
                                            <td class="cart_total_amount"><strong>{{ number_format((intval($subtotal) + 30000) ?? 0) }} đ</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('client.checkout') }}" class="btn btn-fill-out">Tiếp tục thanh toán</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- END SECTION SHOP -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
