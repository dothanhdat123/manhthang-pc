@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Không tồn tại") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Không tồn tại</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Không tồn tại</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START 404 SECTION -->
        <div class="section">
            <div class="error_wrap">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6 col-md-10 order-lg-first">
                            <div class="text-center">
                                <div class="error_txt">404</div>
                                <h5 class="mb-2 mb-sm-3">Xin lỗi! Trang bạn yêu cầu không tìm thấy!</h5>
                                <p>Trang bạn đang tìm kiếm đã bị di chuyển, xóa, đổi tên hoặc có thể không bao giờ tồn tại.</p>
                                <a href="{{ route('client.home') }}" class="btn btn-fill-out">Quay lại trang chủ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END 404 SECTION -->
    </div>
    <!-- END MAIN CONTENT -->
@endsection
