@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("Bài viết") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Bài viết</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Bài viết</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION BLOG -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row blog_thumbs">
                            @if ($results->total() > 0)
                                @foreach($results->items() as $item)
                                    <div class="col-12">
                                        <div class="blog_post blog_style2">
                                            <div class="blog_img">
                                                <a href="{{ route('client.news.show', ['slug' => $item['slug']]) }}">
                                                    <img
                                                        src="{{ $item['image'] ? hwa_image_url("news/thumb", $item['image']) : "assets/images/blog_small_img1.jpg" }}"
                                                        alt="{{ $item['title'] ?? "" }}">
                                                </a>
                                            </div>
                                            <div class="blog_content bg-white">
                                                <div class="blog_text">
                                                    <h6 class="blog_title"><a
                                                            href="{{ route('client.news.show', ['slug' => $item['slug']]) }}">{{ $item['title'] ?? "" }}</a>
                                                    </h6>
                                                    <ul class="list_none blog_meta">
                                                        <li>
                                                            <i class="ti-calendar"></i> {{ Carbon\Carbon::parse($item['created_at'])->locale('vi')->isoFormat('MMM DD, Y') }}
                                                        </li>
                                                        <li><i class="ti-eye"></i> {{ number_format($item['views']) ?? 0 }}
                                                            Lượt xem
                                                        </li>
                                                    </ul>
                                                    <p>{{ $item['description'] ?? "" }}</p>
                                                    <a href="{{ route('client.news.show', ['slug' => $item['slug']]) }}"
                                                       class="btn btn-fill-line border-2 btn-xs rounded-0">Xem thêm</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <br>
                                <div class="col-12 text-center">Không có bài viết</div>
                            @endif
                        </div>

                        @if($results->lastPage() > 1)
                            <div class="row">
                                <div class="col-12 mt-2 mt-md-4">
                                    <ul class="pagination pagination_style1 justify-content-center">
                                        @for($i=1; $i <= $results->lastPage(); $i++)
                                            <li class="page-item {{ ($results->currentPage() == $i) ? 'active' : '' }}">
                                                <a
                                                    class="page-link" href="{{ $results->url($i) }}">{{ $i }}</a></li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION BLOG -->
    </div>
    <!-- END MAIN CONTENT -->
@endsection
