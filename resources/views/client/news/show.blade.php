@extends('client.layouts.index')

@section('client_head')
    <meta name="description" content="{{ $result['seo_description'] }}">
    <meta name="keywords" content="{{ $result['seo_keyword'] }}">

    <meta property="og:image"
          content="{{ $result['image'] ? hwa_image_url("news/thumb", $result['image']) : "shopwise/assets/images/blog_img1.jpg" }}">
    <meta property="og:description" content="{{ $result['seo_description'] }}">
    <meta property="og:url" content="{{ route('client.news.show', ['slug' => $result['slug']]) }}">
    <meta property="og:title" content="{{ $result['seo_title'] }}">
    <meta property="og:type" content="article">
    <meta name="twitter:title" content="{{ $result['seo_title'] }}">
    <meta name="twitter:description" content="{{ $result['seo_description'] }}">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title($result['title'] ?? "Chi tiết bài viết") }}</title>
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
                        <li class="breadcrumb-item"><a href="{{ route('client.news.index') }}">Bài viết</a></li>
                        <li class="breadcrumb-item active">{{ $result['title'] }}</li>
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
                    <div class="col-xl-12">
                        <div class="single_post">
                            <h2 class="blog_title">{{ $result['title'] ?? "" }}</h2>
                            <ul class="list_none blog_meta">
                                <li>
                                    <i class="ti-calendar"></i> {{ Carbon\Carbon::parse($result['created_at'])->locale('vi')->isoFormat('MMM DD, Y') }}
                                </li>
                                <li><i class="ti-pencil-alt"></i> {{ $result['category']['name'] ?? "Chuyên mục" }}</li>
                                <li><i class="ti-eye"></i> {{ number_format($result['views']) ?? 0 }} Lượt xem</li>
                            </ul>
                            <div class="blog_img">
                                <img
                                    src="{{ $result['image'] ? hwa_image_url("news/thumb", $result['image']) : "shopwise/assets/images/blog_img1.jpg" }}"
                                    alt="{{ $result['title'] ?? "" }}">
                            </div>
                            <div class="blog_content">
                                <div class="blog_text">
                                    {!! $result['content'] !!}
                                    <div class="blog_post_footer">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="col-md-8 mb-3 mb-md-0">
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="social_icons text-md-right">
                                                    <ul class="social_icons text-md-right">
                                                        <li>
                                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('client.news.show', ['slug' => $result['slug']])) }}&title={{ rawurldecode($result['description']) }}"
                                                               target="_blank" title="Chia sẻ lên Facebook"><i
                                                                    class="ion-social-facebook"></i></a></li>
                                                        <li>
                                                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('client.news.show', ['slug' => $result['slug']])) }}&text={{ rawurldecode($result['description']) }}"
                                                               target="_blank" title="Chia sẻ lên Twitter"><i
                                                                    class="ion-social-twitter"></i></a></li>
                                                        <li>
                                                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('client.news.show', ['slug' => $result['slug']])) }}&summary={{ rawurldecode($result['description']) }}&source=Linkedin"
                                                               title="Chia sẻ lên Linkedin" target="_blank"><i
                                                                    class="ion-social-linkedin"></i></a></li>
                                                    </ul>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(count($relatePosts) > 0)
                            <div class="related_post">
                                <div class="content_title">
                                    <h5>Bài viết liên quan</h5>
                                </div>
                                <div class="row">
                                    @foreach($relatePosts as $post)
                                        <div class="col-md-6">
                                            <div class="blog_post blog_style2 box_shadow1">
                                                <div class="blog_img">
                                                    <a href="{{ route('client.news.show', ['slug' => $post['slug']]) }}">
                                                        <img
                                                            src="{{ $post['image'] ? hwa_image_url("news/thumb", $post['image']) : "shopwise/assets/images/blog_img1.jpg" }}"
                                                            alt="{{ $post['title'] ?? "" }}">
                                                    </a>
                                                </div>
                                                <div class="blog_content bg-white">
                                                    <div class="blog_text">
                                                        <h5 class="blog_title"><a
                                                                href="{{ route('client.news.show', ['slug' => $post['slug']]) }}">{{ $post['title'] ?? "" }}</a>
                                                        </h5>
                                                        <ul class="list_none blog_meta">
                                                            <li>
                                                                <i class="ti-calendar"></i> {{ Carbon\Carbon::parse($post['created_at'])->locale('vi')->isoFormat('MMM DD, Y') }}
                                                            </li>
                                                            <li>
                                                                <i class="ti-eye"></i> {{ number_format($post['views']) ?? 0 }}
                                                                Lượt xem
                                                            </li>
                                                        </ul>
                                                        <p>{{ $post['description'] ?? "" }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
