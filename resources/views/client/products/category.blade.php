@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title($result['name'] ?? "Danh mục sản phẩm") }}</title>
@endsection

@section('client_style')
    <!-- jquery-ui CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/jquery-ui.css">
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>{{ $result['name'] ?? "Danh mục sản phẩm" }}</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">{{ $result['name'] ?? "Danh mục sản phẩm" }}</li>
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
                    <div class="col-lg-9">
                        <div class="row align-items-center mb-4 pb-1">
                            <div class="col-12">
                                <div class="product_header">
                                    <div class="product_header_left">

                                    </div>
                                    <div class="product_header_right">
                                        <div class="products_view">
                                            <a href="javascript:void(0);" class="shorting_icon grid active"><i
                                                    class="ti-view-grid"></i></a>
                                            <a href="javascript:void(0);" class="shorting_icon list"><i
                                                    class="ti-layout-list-thumb"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row shop_container grid">
                            @if ($products->total() > 0)
                                @foreach($products->items() as $product)
                                    <div class="col-md-4 col-6">
                                    <div class="product">
                                        <div class="product_img">
                                            <a href="{{ route('client.product.show', ['slug' => $product['slug']]) }}">
                                                <img src="{{ hwa_image_url("products/thumbs", $product['thumb']) }}" alt="{{ $product['name'] ?? "" }}">
                                            </a>
                                            <div class="product_action_box">
                                                <ul class="list_none pr_action_btn">
                                                    <li class="add-to-cart"><a href="{{ route('client.cart.create', $product['id']) }}"><i
                                                                class="icon-basket-loaded"></i>
                                                            Thêm vào giỏ hàng</a></li>
                                                    <li><a href="{{ route('client.wishlist.store', ['id' => $product['id']]) }}"><i class="icon-heart"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product_info">
                                            <h6 class="product_title"><a href="{{ route('client.product.show', ['slug' => $product['slug']]) }}">{{ $product['name'] ?? "" }}</a></h6>
                                            <div class="product_price">
                                                <span class="price">{{ number_format($product['price']) ?? 0 }} đ</span>
                                            </div>
                                            <div class="rating_wrap">
                                                <div class="rating">
                                                    <div class="product_rate" style="width:{{ hwa_rating_percent($product['id']) }}%"></div>
                                                </div>
                                                <span class="rating_num">({{ count($product->reviews) ?? 0 }})</span>
                                            </div>
                                            <div class="pr_desc">
                                                <p>{{ $product['description'] ?? "" }}</p>
                                            </div>
                                            <div class="list_product_action_box">
                                                <ul class="list_none pr_action_btn">
                                                    <li class="add-to-cart"><a href="{{ route('client.cart.create', $product['id']) }}"><i
                                                                class="icon-basket-loaded"></i>
                                                            Thêm vào giỏ hàng</a></li>
                                                    <li><a href="{{ route('client.wishlist.store', ['id' => $product['id']]) }}"><i class="icon-heart"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <br>
                                <div class="col-12 text-center">Không có sản phẩm</div>
                            @endif
                        </div>
                        @if ($products->lastPage() > 1)
                            <div class="row">
                                <div class="col-12">
                                    <ul class="pagination mt-3 justify-content-center pagination_style1">
                                        @for($i=1; $i <= $products->lastPage(); $i++)
                                            <li class="page-item {{ ($products->currentPage() == $i) ? 'active' : '' }}">
                                                <a
                                                    class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a></li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                        <div class="sidebar">
                            <div class="widget">
                                <h5 class="widget_title">Danh mục sản phẩm</h5>
                                <ul class="widget_categories">
                                    @foreach($categories as $global_category)
                                        <li>
                                            <a href="{{ route('client.category.show', ['slug' => $global_category['slug']]) }}"><span
                                                    class="categories_name">{{ $global_category['name'] }}</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
