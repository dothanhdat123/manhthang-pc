@extends('client.layouts.index')

@section('client_head')
    <meta name="description" content="{{ $result['seo_description'] ?? "" }}">
    <meta name="keywords" content="{{ $result['seo_keyword'] ?? "" }}">

    <meta property="og:image"
          content="{{ (isset($result['thumb']) && !empty($result['thumb'])) ? hwa_image_url("products/thumbs", ($result['thumb'] ?? "")) : "shopwise/assets/images/blog_img1.jpg" }}">
    <meta property="og:description" content="{{ $result['seo_description'] ?? "" }}">
    <meta property="og:url" content="{{ route('client.news.show', ['slug' => ($result['slug'] ?? "")]) }}">
    <meta property="og:title" content="{{ $result['seo_title'] ?? "" }}">
    <meta property="og:type" content="article">
    <meta name="twitter:title" content="{{ $result['seo_title'] ?? "" }}">
    <meta name="twitter:description" content="{{ $result['seo_description'] ?? "" }}">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title($result['name'] ?? "Chi tiết sản phẩm") }}</title>
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
                        <li class="breadcrumb-item"><a
                                href="{{ route('client.category.show', ['slug' => $result['category']['slug']]) }}">{{ $result['category']['name'] ?? "Danh mục sản phẩm" }}</a>
                        </li>
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
                    <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                        <div class="product-image">
                            <div class="product_img_box">
                                <img id="product_img"
                                     src="{{ $result['thumb'] ? hwa_image_url("products/thumbs", $result['thumb']) : "shopwise/assets/images/product_img1.jpg" }}"
                                     data-zoom-image="{{ $result['thumb'] ? hwa_image_url("products/thumbs", $result['thumb']) : "shopwise/assets/images/product_img1.jpg" }}"
                                     alt="product_img1"/>
                                <a href="javascript:void(0);" class="product_img_zoom" title="Zoom">
                                    <span class="linearicons-zoom-in"></span>
                                </a>
                            </div>
                            <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4"
                                 data-slides-to-scroll="1" data-infinite="false">
                                @foreach(explode(',', $result['image']) as $img)
                                    <div class="item">
                                        <a href="javascript:void(0);"
                                           class="product_gallery_item @if($loop->first) active @endif"
                                           data-image="{{ hwa_image_url("products", $img) }}"
                                           data-zoom-image="{{ hwa_image_url("products", $img) }}">
                                            <img src="{{ hwa_image_url("products", $img) }}"
                                                 alt="{{ $result['name'] }}"/>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="pr_detail">
                            <div class="product_description">
                                <h4 class="product_title">{{ $result['name'] ?? "Sản phẩm" }}</h4>
                                <div class="rating_wrap">
                                    <div class="rating">
                                        <div class="product_rate"
                                             style="width:{{ hwa_rating_percent($result['id']) }}%"></div>
                                    </div>
                                    <span class="rating_num">({{ count($result['reviews']) ?? 0 }})</span>
                                </div>
                                <div style="display: flex!important; flex-direction: column!important;">
                                    <div class="product_price">
                                        <span class="price">{{ number_format($result['price']) ?? 0 }} đ</span>
                                    </div>
                                    <div class="pr_desc">
                                        <p>{!! $result['description'] !!}</p>
                                    </div>
                                </div>
                                <div class="product_sort_info">
                                    <ul>
                                        <li><i class="linearicons-shield-check"></i> Sản phẩm đảm bảo chất lượng</li>
                                        <li><i class="linearicons-sync"></i> 30 ngày đổi trả miễn phí</li>
                                        <li><i class="linearicons-bag-dollar"></i> Thanh toán khi nhận hàng</li>
                                    </ul>
                                </div>
                            </div>
                            <hr/>
                            <div class="cart_extra">
                                <div class="cart-product-quantity">
                                    <div class="quantity">
                                        <input type="button" value="-" class="minus">
                                        <input type="text" name="quantity" value="1" title="Qty" class="qty" size="4">
                                        <input type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="cart_btn">
                                    <a class="btn btn-fill-out btn-addtocart text-white" href="{{ route('client.cart.create', ['id' => $result['id']]) }}"><i
                                            class="icon-basket-loaded"></i> Thêm vào giỏ hàng
                                    </a>
                                    <a class="add_wishlist" href="{{ route('client.wishlist.store', ['id' => $result['id']]) }}"><i class="icon-heart"></i></a>
                                </div>
                            </div>
                            <hr/>
                            <ul class="product-meta">
                                <li>SKU: {{ $result['sku'] ?? "" }}</li>
                                <li>Danh mục: <a
                                        href="{{ route('client.category.show', ['slug' => $result['category']['slug']]) }}">{{ $result['category']['name'] ?? "Danh mục sản phẩm" }}</a>
                                </li>
                            </ul>

                            <div class="product_share">
                                <span>Chia sẻ:</span>
                                <ul class="social_icons">
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('client.product.show', ['slug' => $result['slug']])) }}&title={{ rawurldecode($result['description']) }}"
                                           target="_blank" title="Chia sẻ lên Facebook"><i
                                                class="ion-social-facebook"></i></a></li>
                                    <li>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('client.product.show', ['slug' => $result['slug']])) }}&text={{ rawurldecode($result['description']) }}"
                                           target="_blank" title="Chia sẻ lên Twitter"><i
                                                class="ion-social-twitter"></i></a></li>
                                    <li>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('client.product.show', ['slug' => $result['slug']])) }}&summary={{ rawurldecode($result['description']) }}&source=Linkedin"
                                           title="Chia sẻ lên Linkedin" target="_blank"><i
                                                class="ion-social-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="large_divider clearfix"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="tab-style3">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="Description-tab" data-toggle="tab"
                                       href="#Description" role="tab" aria-controls="Description" aria-selected="true">Mô
                                        tả</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="Reviews-tab" data-toggle="tab" href="#Reviews" role="tab"
                                       aria-controls="Reviews" aria-selected="false">Đánh giá
                                        ({{ count($result['reviews']) ?? 0 }})</a>
                                </li>
                            </ul>
                            <div class="tab-content shop_info_tab">
                                <div class="tab-pane fade show active" id="Description" role="tabpanel"
                                     aria-labelledby="Description-tab">
                                    <p>{!! $result['content'] ?? "" !!}</p>
                                </div>
                                <div class="tab-pane fade" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                                    <div class="comments">
                                        <h5 class="product_tab_title">{{ count($result['reviews']) ?? 0 }} lượt đánh giá
                                            cho <span>{{ $result['name'] ?? "Sản phẩm" }}</span>
                                        </h5>
                                        <ul class="list_none comment_list mt-4">
                                            @if(count($result['reviews']) > 0)
                                                @foreach($result['reviews'] as $review)
                                                    <li>
                                                        <div class="comment_img">
                                                            <img src="{{ $review->customer->avatar_url }}" alt="user1"/>
                                                        </div>
                                                        <div class="comment_block">
                                                            <div class="rating_wrap">
                                                                <div class="rating">
                                                                    <div class="product_rate"
                                                                         style="width:{{ ($review['point'] / 5) * 100 }}%"></div>
                                                                </div>
                                                            </div>
                                                            <p class="customer_meta">
                                                                <span
                                                                    class="review_author">{{ $review['customer']['name'] ?? "Khách hàng" }}</span>
                                                                <span
                                                                    class="comment-date">{{ Carbon\Carbon::parse($review['created_at'])->locale('vi')->isoFormat('DD MMM, Y') }}</span>
                                                            </p>
                                                            <div class="description">
                                                                <p>{{ $review['comment'] }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>

                                    @if(auth()->check())
                                        @if(!$existsReview)
                                            <div class="review_form field_form">
                                                <h5>Thêm đánh giá</h5>
                                                <form action="{{ route('client.product.reviews.create') }}"
                                                      class="row mt-3 form-review-product">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $result['id'] }}">
                                                    <input type="hidden" name="star" value="1">
                                                    <div class="form-group col-12">
                                                        <div class="star_rating">
                                                            <span data-value="1"><i class="far fa-star"></i></span>
                                                            <span data-value="2"><i class="far fa-star"></i></span>
                                                            <span data-value="3"><i class="far fa-star"></i></span>
                                                            <span data-value="4"><i class="far fa-star"></i></span>
                                                            <span data-value="5"><i class="far fa-star"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12">
                                                <textarea placeholder="Đánh giá của bạn" class="form-control"
                                                          name="comment" rows="4"></textarea>
                                                    </div>

                                                    <div class="form-group col-12">
                                                        <button type="submit" class="btn btn-fill-out" name="submit"
                                                                value="Submit">Gửi đánh giá
                                                        </button>
                                                    </div>

                                                    <div class="form-group col-12">
                                                        <div class="success-message text-success"
                                                             style="display: none;">
                                                            <span></span>
                                                        </div>

                                                        <div class="error-message text-danger" style="display: none;">
                                                            <span></span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-center text-danger">Vui lòng <a
                                                href="{{ route('client.auth.login') }}">đăng nhập</a> để đánh giá sản
                                            phẩm</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($productRelates->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="small_divider"></div>
                            <div class="divider"></div>
                            <div class="medium_divider"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_s1">
                                <h3>Sản phẩm liên quan</h3>
                            </div>
                            <div class="releted_product_slider carousel_slider owl-carousel owl-theme" data-margin="20"
                                 data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "1199":{"items": "4"}}'>
                                @foreach($productRelates as $item)
                                    <div class="item">
                                        <div class="product">
                                            <div class="product_img">
                                                <a href="{{ route('client.product.show', ['slug' => $item['slug']]) }}">
                                                    <img src="{{ hwa_image_url("products/thumbs", $item['thumb']) }}"
                                                         alt="{{ $item['name'] }}">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i class="icon-basket-loaded"></i>Thêm vào giỏ hàng</a></li>
                                                        <li><a href="{{ route('client.wishlist.store', ['id' => $item['id']]) }}"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="{{ route('client.product.show', ['slug' => $item['slug']]) }}">{{ $item['name'] }}</a></h6>
                                                <div class="product_price">
                                                    <span class="price">{{ number_format($item['price']) ?? 0 }} đ</span>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:{{ hwa_rating_percent($item['id']) }}%"></div>
                                                    </div>
                                                    <span class="rating_num">({{ count($item['reviews']) ?? 0 }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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

@section('client_script')
    <script type="text/javascript">
        $(document).on('click', '.form-review-product button[type=submit]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).prop('disabled', true).addClass('btn-disabled').addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).closest('form').prop('action'),
                data: new FormData($(this).closest('form')[0]),
                contentType: false,
                processData: false,
                success: res => {
                    $(this).closest('form').find('.success-message').html('').hide();
                    $(this).closest('form').find('.error-message').html('').hide();

                    if (!res.error) {
                        $(this).closest('form').find('select').val(0);
                        $(this).closest('form').find('textarea').val('');

                        $(this).closest('form').find('.success-message').html(res.message).show();

                        setTimeout(function () {
                            $(this).closest('form').find('.success-message').html('').hide();
                        }, 3000);
                    } else {
                        $(this).closest('form').find('.error-message').html(res.message).show();

                        $(this).closest('form').find('select').val(0);
                        $(this).closest('form').find('textarea').val('');

                        setTimeout(function () {
                            $(this).closest('form').find('.error-message').html('').hide();
                        }, 3000);
                    }

                    $(this).prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                },
                error: res => {
                    $(this).prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                    handleError(res, $(this).closest('form'));
                }
            });
        });

        let handleError = function (data, form) {
            if (typeof (data.errors) !== 'undefined' && !_.isArray(data.errors)) {
                handleValidationError(data.errors, form);
            } else if (typeof (data.responseJSON) !== 'undefined') {
                if (typeof (data.responseJSON.errors) !== 'undefined' && data.status === 422) {
                    handleValidationError(data.responseJSON.errors, form);
                } else if (typeof (data.responseJSON.message) !== 'undefined') {
                    $(form).find('.error-message').html(data.responseJSON.message).show();
                } else {
                    let message = '';
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            message += item + '<br />';
                        });
                    });

                    $(form).find('.error-message').html(message).show();
                }
            } else {
                $(form).find('.error-message').html(data.statusText).show();
            }
        };

        let handleValidationError = function (errors, form) {
            let message = '';
            $.each(errors, (index, item) => {
                message += item + '<br />';
            });

            $(form).find('.success-message').html('').hide();
            $(form).find('.error-message').html('').hide();

            $(form).find('.error-message').html(message).show();
        };

    </script>
@endsection
