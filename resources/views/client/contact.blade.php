@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Liên hệ") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Liên hệ</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Liên hệ</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION CONTACT -->
        <div class="section pb_70">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="contact_wrap contact_style3">
                            <div class="contact_icon">
                                <i class="linearicons-map2"></i>
                            </div>
                            <div class="contact_text">
                                <span>Địa chỉ</span>
                                <p>{{ hwa_setting('site_address', '123 Street, Old Trafford, NewYork, USA') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="contact_wrap contact_style3">
                            <div class="contact_icon">
                                <i class="linearicons-envelope-open"></i>
                            </div>
                            <div class="contact_text">
                                <span>Địa chỉ email</span>
                                <a href="mailto:{{ hwa_setting('site_email', hwa_app_contact()) }}">{{ hwa_setting('site_email', hwa_app_contact()) }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="contact_wrap contact_style3">
                            <div class="contact_icon">
                                <i class="linearicons-tablet2"></i>
                            </div>
                            <div class="contact_text">
                                <span>Số điện thoại</span>
                                <p>{{ hwa_setting('site_phone', '123-456-7890') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CONTACT -->

        <!-- START SECTION CONTACT -->
        <div class="section pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="heading_s1">
                            <h2>Bản Đồ</h2>
                        </div>
                        <iframe src="{{ hwa_setting('site_google_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3710.032678754447!2d105.8045425154288!3d21.584647473943186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313527c1c758f793%3A0xeb1d640638f04b62!2sAnam%20Software!5e0!3m2!1svi!2s!4v1640008149046!5m2!1svi!2s') }}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CONTACT -->

        <!-- START SECTION CONTACT -->
        <div class="section pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="heading_s1">
                            <h2>Liên Lạc</h2>
                        </div>
                        <p class="leads"></p>
                        <div class="field_form">
                            <form action="{{ route('client.contact') }}" method="post" name="enq">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Tên: <span class="text-danger">*</span></label>
                                        <input placeholder="Nhập tên" id="name" class="form-control"
                                               name="name" type="text">
                                        @error('name')
                                        <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email: <span class="text-danger">*</span></label>
                                        <input placeholder="Nhập Email" id="email" class="form-control"
                                               name="email" type="email">
                                        @error('email')
                                        <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">SĐT:</label>
                                        <input placeholder="Nhập SĐT" id="phone" class="form-control"
                                               name="phone">
                                        @error('phone')
                                        <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="subject">Tiêu đề:</label>
                                        <input placeholder="Nhập tiêu đề" id="subject" class="form-control"
                                               name="subject">
                                        @error('subject')
                                        <p class="text-danger mt-2">{{ $errors->first('subject') }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="message">Nội dung:</label>
                                        <textarea placeholder="Nộ dung tin nhắn" id="message" class="form-control"
                                                  name="message" rows="4"></textarea>
                                        @error('message')
                                        <p class="text-danger mt-2">{{ $errors->first('message') }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" title="Submit Your Message!" class="btn btn-fill-out">Gửi lời nhắn</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CONTACT -->

        <!-- START SECTION SUBSCRIBE NEWSLETTER -->
        <div class="section bg_default small_pt small_pb">
            <div class="custom-container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="newsletter_text text_white">
                            <h3>Theo dõi bản tin ngay bây giờ</h3>
                            <p>Đăng ký ngay để cập nhật các chương trình khuyến mãi.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="newsletter_form2 rounded_input">
                            <form action="{{ route('client.newsletter') }}" method="post">
                                @csrf
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" placeholder="Nhập email của bạn" value="{{ old('email') }}">
                                <button type="submit" class="btn btn-dark btn-radius">Theo dõi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- START SECTION SUBSCRIBE NEWSLETTER -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
