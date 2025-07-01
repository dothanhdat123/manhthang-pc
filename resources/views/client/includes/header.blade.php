<header class="header_wrap fixed-top header_with_topbar">
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <ul class="contact_detail text-center text-lg-left">
                            <li><i class="ti-mobile"></i><span>{{ hwa_setting('site_phone', '123-456-7890') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center text-md-right">
                        <ul class="header_list">
                            <li><a href="{{ route('client.cart.index') }}"><i
                                        class="ti-shopping-cart"></i><span>Giỏ hàng</span></a></li>
                            <li><a href="{{ route('client.wishlist.index') }}"><i
                                        class="ti-heart"></i><span>Yêu thích</span></a></li>
                            @if(auth()->check())
                                <li><a href="{{ route('client.customers.index') }}"><i
                                            class="ti-user"></i><span>{{ auth()->user()->name ?? "Khách hàng" }}</span></a></li>
                                <li><a href="{{ route('client.auth.logout') }}"><i
                                            class="ti-lock"></i><span>Đăng xuất</span></a></li>
                            @else
                                <li><a href="{{ route('client.auth.login') }}"><i
                                            class="ti-user"></i><span>Đăng nhập</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_header dark_skin main_menu_uppercase">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ route('client.home') }}">
                    <img class="logo_light"
                         src="shopwise/assets/images/image(1280).png"
                         alt="logo" height="40"/>
                    <img class="logo_dark"
                         src="shopwise/assets/images/image(1280).png"
                         alt="logo" height="40"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="ion-android-menu"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li><a class="nav-link nav_item" href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle nav-link" href="{{ route('client.product.index') }}">Sản
                                phẩm</a>
                            <div class="dropdown-menu dropdown-reverse">
                                <ul>
                                    @foreach($global_categories as $global_category)
                                        <li>
                                            <a class="dropdown-item menu-link {{ (isset($global_category->childCategories) && count($global_category->childCategories) > 0) ? "dropdown-toggler" : "" }}"
                                               href="{{ route('client.category.show', ['slug' => $global_category['slug']]) }}">{{ $global_category['name'] ?? "" }}</a>
                                            @if(isset($global_category->childCategories) && count($global_category->childCategories) > 0)
                                                <div class="dropdown-menu">
                                                    <ul>
                                                        @foreach($global_category->childCategories as $childCategory)
                                                            <li><a class="dropdown-item nav-link nav_item"
                                                                   href="{{ route('client.category.show', ['slug' => $childCategory['slug']]) }}">{{ $childCategory['name'] ?? "" }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>  
                        <li><a class="nav-link nav_item" href="{{ route('client.term') }}">Điều khoản</a></li>
                        <li><a class="nav-link nav_item" href="{{ route('client.delivery') }}">Giao hàng</a></li>
                        <li><a class="nav-link nav_item" href="{{ route('client.returns') }}">Đổi trả</a></li>
                        <li><a class="nav-link nav_item" href="{{ route('client.about') }}">Giới thiệu</a></li>
                        <li><a class="nav-link nav_item" href="{{ route('client.contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <ul class="navbar-nav attr-nav align-items-center">
                    <li><a href="javascript:void(0);" class="nav-link search_trigger"><i
                                class="linearicons-magnifier"></i></a>
                        <div class="search_wrap">
                            <span class="close-search"><i class="ion-ios-close-empty"></i></span>
                            <form action="{{ route('client.search') }}" method="get">
                                <input type="text" placeholder="Nhập để tìm kiếm" class="form-control"
                                       id="search_input" name="q">
                                <button type="submit" class="search_icon"><i class="ion-ios-search-strong"></i></button>
                            </form>
                        </div>
                        <div class="search_overlay"></div>
                    </li>
                    <li class="dropdown cart_dropdown"><a class="nav-link cart_trigger" href="javascript:void(0);"
                                                          data-toggle="dropdown"><i class="linearicons-cart"></i><span
                                class="cart_count">{{ $cart['count'] ?? 0 }}</span></a>
                        <div class="cart_box dropdown-menu dropdown-menu-right">
                            <ul class="cart_list">
                                @if(auth()->check() && $cart['count'] > 0)
                                    @foreach($cart['data'] as $cartProduct)
                                        <li>
                                            <a href="{{ route('client.product.show', ['slug' => $cartProduct['product_slug']]) }}"
                                               class="item_remove"><i class="ion-close"></i></a>
                                            <a href="{{ route('client.product.show', ['slug' => $cartProduct['product_slug']]) }}"><img
                                                    src="{{ $cartProduct['product_thumb'] }}"
                                                    alt="cart_thumb2">{{ $cartProduct['product_name'] }}</a>
                                            <span class="cart_quantity">
                                                {{ $cartProduct['quantity'] }} x <span class="cart_amount"> {{ number_format($cartProduct['product_price']) }} đ</span>
                                            </span>
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <span class="text-center">Giỏ hàng trống</span>
                                    </li>
                                @endif
                            </ul>
                            @if(auth()->check() && $cart['count'] > 0)
                                <div class="cart_footer">
                                    <p class="cart_total"><strong>Tạm tính:</strong> <span class="cart_price">{{ number_format($cart['subtotal'] ?? 0) }} đ</span>
                                    </p>
                                    <p class="cart_buttons">
                                        <a href="{{ route('client.cart.index') }}" class="btn btn-fill-line view-cart">Giỏ hàng</a>
                                        <a href="{{ route('client.checkout') }}" class="btn btn-fill-out checkout">Thanh toán</a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
</header>
