@extends('client.layouts.index')

<style>
    /* Promotional Popup Styles */
.promo-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 9998;
    display: none;
}

.promo-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    width: 90%;
    max-width: 400px;
    padding: 20px;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    display: none;
    text-align: center;
}

.promo-popup h2 {
    margin-bottom: 15px;
    font-size: 24px;
    color: #333;
}

.promo-popup p {
    font-size: 16px;
    color: #555;
    margin-bottom: 20px;
}

.promo-popup .close-btn {
    background: #ff5a5f;
    color: #fff;
    border: none;
    padding: 10px 25px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.promo-popup .close-btn:hover {
    background: #e04848;
}

/* Chatbox Modal Styles */
  /* Chatbox Container Styles */
.chatbox-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 320px;
    max-height: 400px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: Arial, sans-serif;
    z-index: 1050;
}

.chatbox-header {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: default;
}

.chatbox-header button {
    background: transparent;
    border: none;
    color: white;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
}

.chatbox-messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background-color: #f9f9f9;
}

.chatbox-messages p {
    margin: 0 0 10px 0;
    padding: 8px 12px;
    background-color: #e9ecef;
    border-radius: 15px;
    max-width: 80%;
}

.chatbox-input {
    border: 1px solid #ccc;
    border-radius: 20px;
    padding: 8px 12px;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 10px;
}

.chatbox-send-btn {
    background-color: #007bff;
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    cursor: pointer;
    width: 100%;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.chatbox-send-btn:hover {
    background-color: #0056b3;
}


</style>
@section('client_head')
    <meta name="description" content="{{ hwa_page_title() }}">
    <meta name="keywords" content="{{ hwa_page_title() }}">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title() }}</title>

    <!-- Promo Popup CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/promo-popup.css') }}">
    <!-- Chatbox CSS -->
    <link rel="stylesheet" href="{{ asset('resources/css/chatbox.css') }}">
@endsection

@section('client_main')

    @if(isset($banners) && count($banners) > 0)
        <!-- START SECTION BANNER -->
        <div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
            <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($banners as $banner)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }} background_bg"
                             data-img-src="{{ hwa_image_url("banners", $banner['image']) }}">
                            <div class="banner_slide_content">
                                <div class="container">
                                    <!-- STRART CONTAINER -->
                                    <div class="row">
                                        <div class="col-lg-7 col-9">
                                            <div class="banner_content overflow-hidden">
                                                <h5 class="mb-3 staggered-animation font-weight-light"
                                                    data-animation="slideInLeft"
                                                    data-animation-delay="0.5s">{{ $banner['sub_title'] ?? "" }}</h5>
                                                <h2 class="staggered-animation" data-animation="slideInLeft"
                                                    data-animation-delay="1s">{{ $banner['title'] ?? "" }}</h2>
                                                <a class="btn btn-fill-out rounded-0 staggered-animation text-uppercase"
                                                   href="{{ $banner['url'] ?? "javascript:void(0);" }}"
                                                   data-animation="slideInLeft"
                                                   data-animation-delay="1.5s" target="{{ $banner['target'] }}">Mua ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END CONTAINER-->
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                   data-slide="prev"><i
                        class="ion-chevron-left"></i></a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                   data-slide="next"><i
                        class="ion-chevron-right"></i></a>
            </div>
        </div>
        <!-- END SECTION BANNER -->
    @endif

    <button id="scrollToTopBtn" title="Lên đầu trang" style="position: fixed; bottom: 20px; left: 20px; z-index: 1070; display: none; background-color: #007bff; color: white; border: none; border-radius: 50%; width: 40px; height: 40px; font-size: 24px; cursor: pointer;">&#8679;</button>

    <!-- Chatbox Container -->
<div id="chatbox-container" style="position: fixed; bottom: 20px; right: 20px; width: 300px; background: white; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 10px; z-index: 1050; display: none;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="mb-0">💬 Hỗ trợ trực tuyến</h2>
            <button id="chatbox-close" class="btn btn-sm btn-outline-secondary">×</button>
        </div>
        <div id="chat-box" style="height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
            @foreach ($messages as $msg)
                <div class="message">
                    <strong>{{ $msg->name }}:</strong> {{ $msg->message }}
                </div>
            @endforeach
        </div>

        <form id="chat-form" class="mt-3">
            @csrf
            <input type="text" name="name" class="form-control mb-2" placeholder="Tên của bạn" required>
            <input type="text" name="message" class="form-control mb-2" placeholder="Nội dung..." required>
            <button type="submit" class="btn btn-primary w-100">Gửi</button>
        </form>
    </div>

    <button id="chatbox-toggle" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; z-index: 1060;">
        Chat với chúng tôi
    </button>


    <!-- END MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section pt-10 pb-20">
            <div class="custom-container">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Sản Phẩm Độc Quyền</h4>
                                    </div>
                                    <div class="tab-style2">
                                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                                data-target="#tabmenubar" aria-expanded="false">
                                            <span class="ion-android-menu"></span>
                                        </button>
                                        <ul class="nav nav-tabs justify-content-center justify-content-md-end"
                                            id="tabmenubar" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="arrival-tab" data-toggle="tab"
                                                   href="#arrival" role="tab" aria-controls="arrival"
                                                   aria-selected="true">Sản Phẩm Mới</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="sellers-tab" data-toggle="tab" href="#sellers"
                                                   role="tab" aria-controls="sellers" aria-selected="false">Bán Chạy
                                                    Nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="tab_slider">
                                    <div class="tab-pane fade show active" id="arrival" role="tabpanel"
                                         aria-labelledby="arrival-tab">
                                        @if(isset($product_new) && count($product_new) > 0)
                                            <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="false" data-margin="20"
                                             data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                            @foreach($product_new as $newProduct)
                                                <div class="item">
                                                    <div class="product_wrap">
                                                        <span class="pr_flash bg-warning">Mới</span>
                                                        <div class="product_img">
                                                            <a href="{{ route('client.product.show', ['slug' => $newProduct['slug']]) }}">
                                                                <img
                                                                    src="{{ hwa_image_url("products/thumbs", $newProduct['thumb']) }}"
                                                                    alt="{{ $newProduct['name'] ?? "" }}">
                                                                <img class="product_hover_img"
                                                                     src="{{ hwa_image_url("products/thumbs", $newProduct['thumb']) }}"
                                                                     alt="{{ $newProduct['name'] ?? "" }}">
                                                            </a>
                                                            <div class="product_action_box">
                                                                <ul class="list_none pr_action_btn">
                                                                    <li class="add-to-cart"><a
                                                                            href="{{ route('client.cart.create', $newProduct['id']) }}"><i
                                                                                class="icon-basket-loaded"></i> Thêm vào
                                                                            giỏ hàng</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('client.wishlist.store', ['id' => $newProduct['id']]) }}"><i
                                                                                class="icon-heart"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="product_info">
                                                            <h6 class="product_title"><a
                                                                    href="{{ route('client.product.show', ['slug' => $newProduct['slug']]) }}">{{ $newProduct['name'] ?? "" }}</a>
                                                            </h6>
                                                            <div class="product_price">
                                                                <span class="price">{{ number_format($newProduct['price']) ?? 0 }} đ</span>
                                                            </div>
                                                            <div class="rating_wrap">
                                                                <div class="rating">
                                                                    <div class="product_rate"
                                                                         style="width:{{ hwa_rating_percent($newProduct['id']) }}%"></div>
                                                                </div>
                                                                <span class="rating_num">({{ count($newProduct->reviews) ?? 0 }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @else
                                            <p class="text-center">Không có sản phẩm</p>
                                        @endif
                                    </div>
                                    <div class="tab-pane fade" id="sellers" role="tabpanel"
                                         aria-labelledby="sellers-tab">
                                        @if(isset($product_sale) && count($product_sale) > 0)
                                            <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="false" data-margin="20"
                                             data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                            @foreach($product_sale as $sale)
                                                <div class="item">
                                                    <div class="product_wrap">
                                                        <span class="pr_flash bg-danger">Hot</span>
                                                        <div class="product_img">
                                                            <a href="{{ route('client.product.show', ['slug' => $sale['slug']]) }}">
                                                                <img
                                                                    src="{{ hwa_image_url("products/thumbs", $sale['thumb']) }}"
                                                                    alt="{{ $sale['name'] ?? "" }}">
                                                                <img class="product_hover_img"
                                                                     src="{{ hwa_image_url("products/thumbs", $sale['thumb']) }}"
                                                                     alt="{{ $sale['name'] ?? "" }}">
                                                            </a>
                                                            <div class="product_action_box">
                                                                <ul class="list_none pr_action_btn">
                                                                    <li class="add-to-cart"><a
                                                                            href="{{ route('client.cart.create', $sale['id']) }}"><i
                                                                                class="icon-basket-loaded"></i> Thêm vào
                                                                            giỏ hàng</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('client.wishlist.store', ['id' => $sale['id']]) }}"><i
                                                                                class="icon-heart"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="product_info">
                                                            <h6 class="product_title"><a
                                                                    href="{{ route('client.product.show', ['slug' => $sale['slug']]) }}">{{ $sale['name'] ?? "" }}</a>
                                                            </h6>
                                                            <div class="product_price">
                                                                <span class="price">{{ $sale['price'] ?? 0 }} đ</span>
                                                            </div>
                                                            <div class="rating_wrap">
                                                                <div class="rating">
                                                                    <div class="product_rate"
                                                                         style="width:{{ hwa_rating_percent($sale['id']) }}%"></div>
                                                                </div>
                                                                <span class="rating_num">({{ count($sale->reviews) ?? 0 }})</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @else
                                            <p class="text-center">Không có sản phẩm</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION SHOP -->
        <div class="section pt-0">
            <div class="custom-container">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Thương hiệu</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row justify-content-center">
                                    @if(!empty($brands) && count($brands) > 0)
                                        <div class="client_logo carousel_slider owl-carousel owl-theme nav_style3" data-dots="false"
                                             data-nav="true" data-margin="30" data-loop="false" data-autoplay="true"
                                             data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}, "1199":{"items": "6"}}'>
                                            @foreach($brands as $brand)
                                                <div class="item">
                                                    <div class="cl_logo">
                                                        <img src="{{ hwa_image_url("brands", $brand['images']) }}" alt="cl_logo"/>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-center">Không có thương hiệu</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION SHOP -->
        <div class="section pt-0">
            <div class="custom-container">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Sản Phẩm Xu Hướng</h4>
                                    </div>
                                    <div class="view_all">
                                        <a href="{{ route('client.product.index') }}" class="text_default"><i class="linearicons-power"></i> <span>Xem thêm</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if(isset($product_trending) && count($product_trending) > 0)
                                    <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                     data-loop="false" data-margin="20"
                                     data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                    @foreach($product_trending as $trending)
                                        <div class="item">
                                            <div class="product_wrap">
                                                <span class="pr_flash bg-danger">Hot</span>
                                                <div class="product_img">
                                                    <a href="{{ route('client.product.show', ['slug' => $trending['slug']]) }}">
                                                        <img
                                                            src="{{ hwa_image_url("products/thumbs", $trending['thumb']) }}"
                                                            alt="el_img2">
                                                        <img class="product_hover_img"
                                                             src="{{ hwa_image_url("products/thumbs", $trending['thumb']) }}"
                                                             alt="el_hover_img2">
                                                    </a>
                                                    <div class="product_action_box">
                                                        <ul class="list_none pr_action_btn">
                                                            <li class="add-to-cart"><a
                                                                    href="{{ route('client.cart.create', $trending['id']) }}"><i
                                                                        class="icon-basket-loaded"></i> Thêm vào giỏ
                                                                    hàng</a></li>
                                                            <li>
                                                                <a href="{{ route('client.wishlist.store', ['id' => $trending['id']]) }}"><i
                                                                        class="icon-heart"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product_info">
                                                    <h6 class="product_title"><a
                                                            href="{{ route('client.product.show', ['slug' => $trending['slug']]) }}">{{ $trending['name'] ?? "" }}</a>
                                                    </h6>
                                                    <div class="product_price">
                                                        <span class="price">{{ number_format($trending['price']) ?? 0 }} đ</span>
                                                    </div>
                                                    <div class="rating_wrap">
                                                        <div class="rating">
                                                            <div class="product_rate"
                                                                 style="width:{{ hwa_rating_percent($trending['id']) }}%"></div>
                                                        </div>
                                                        <span
                                                            class="rating_num">({{ count($trending->reviews) ?? 0 }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @else
                                    <p class="text-center">Không có sản phẩm.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION SHOP INFO -->
        <div class="section pt-0">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-lg-4">
                        <div class="icon_box icon_box_style1">
                            <div class="icon">
                                <i class="flaticon-shipped"></i>
                            </div>
                            <div class="icon_box_content">
                                <h5>Giao hàng miễn phí</h5>
                                <p>Giao hàng miễn phí cho tất cả các đơn đặt hàng tại Việt Nam.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="icon_box icon_box_style1">
                            <div class="icon">
                                <i class="flaticon-money-back"></i>
                            </div>
                            <div class="icon_box_content">
                                <h5>30 ngày hoàn trả</h5>
                                <p>Chỉ cần trả lại nó trong vòng 30 ngày để đổi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="icon_box icon_box_style1">
                            <div class="icon">
                                <i class="flaticon-support"></i>
                            </div>
                            <div class="icon_box_content">
                                <h5>Hỗ Trợ Trực Tuyến 27/4</h5>
                                <p>Liên hệ với chúng tôi 24 giờ một ngày, 7 ngày một tuần.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP INFO -->

       
        

    </div>
    <!-- END MAIN CONTENT -->

    <!-- Promotional Popup HTML -->
    <div class="promo-popup-overlay"></div>
    <div class="promo-popup">
        <h2>Khuyến mãi đặc biệt!</h2>
        <p>Nhận ngay ưu đãi giảm giá 20% cho đơn hàng đầu tiên.</p>
        <button class="close-btn">Đóng</button>
    </div>

     <button id="scrollToTopBtn" title="Lên đầu trang"
    style="position: fixed; bottom: 80px; right: 30px; display: none; width: 45px; height: 45px; border-radius: 50%; background-color: #007bff; color: white; border: none; font-size: 20px; z-index: 999;">
    ↑
</button>



@endsection

 <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
 
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Popup xử lý ---
    var popupOverlay = document.querySelector('.promo-popup-overlay');
    var popup = document.querySelector('.promo-popup');
    var closeBtn = document.querySelector('.promo-popup .close-btn');

    if (popupOverlay && popup && closeBtn) {
        function showPopup() {
            popupOverlay.style.display = 'block';
            popup.style.display = 'block';
        }

        function closePopup() {
            popupOverlay.style.display = 'none';
            popup.style.display = 'none';
        }

        closeBtn.addEventListener('click', closePopup);
        popupOverlay.addEventListener('click', closePopup);
        setTimeout(showPopup, 1000); // hiện popup sau 1 giây
    }

    // --- Chatbox xử lý gửi tin ---
    var chatboxInput = document.querySelector('.chatbox-input');
    var chatboxMessages = document.querySelector('.chatbox-messages');
    var sendBtn = document.querySelector('.chatbox-send-btn');

    if (chatboxInput && chatboxMessages && sendBtn) {
        function appendMessage(message, isUser) {
            var p = document.createElement('p');
            p.textContent = message;
            p.style.backgroundColor = isUser ? '#007bff' : '#f1f1f1';
            p.style.color = isUser ? '#fff' : '#000';
            p.style.textAlign = isUser ? 'right' : 'left';
            p.style.margin = '0 0 10px 0';
            p.style.padding = '5px 10px';
            p.style.borderRadius = '15px';
            p.style.maxWidth = '80%';
            chatboxMessages.appendChild(p);
            chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
        }

        sendBtn.addEventListener('click', function () {
            var message = chatboxInput.value.trim();
            if (message === '') return;
            appendMessage(message, true);
            chatboxInput.value = '';
            setTimeout(function () {
                appendMessage('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.', false);
            }, 1000);
        });

        chatboxInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendBtn.click();
                e.preventDefault();
            }
        });
    }

    // --- Chatbox toggle mở/đóng ---
    var chatboxContainer = document.getElementById('chatbox-container');
    var chatboxToggle = document.getElementById('chatbox-toggle');
    var chatboxClose = document.getElementById('chatbox-close');

    if (chatboxContainer && chatboxToggle && chatboxClose) {
        chatboxToggle.addEventListener('click', function () {
            chatboxContainer.style.display = 'block';
            chatboxToggle.style.display = 'none';
        });

        chatboxClose.addEventListener('click', function () {
            chatboxContainer.style.display = 'none';
            chatboxToggle.style.display = 'inline-block';
        });
    }

    // --- Nút lên đầu trang ---
    var scrollBtn = document.getElementById('scrollToTopBtn');

    if (scrollBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });

        scrollBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // --- Load & gửi tin nhắn qua Ajax (jQuery) ---
    function loadMessages() {
        $.get('/chat/fetch', function (data) {
            let html = '';
            data.forEach(msg => {
                html += `<div class="message"><strong>${msg.name}:</strong> ${msg.message}</div>`;
            });
            $('#chat-box').html(html);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        });
    }

    $('#chat-form').submit(function (e) {
        e.preventDefault();
        $.post('/chat/send', $(this).serialize(), function () {
            loadMessages();
            $('#chat-form')[0].reset();
        });
    });

    setInterval(loadMessages, 3000); // reload mỗi 3 giây
});
</script>




