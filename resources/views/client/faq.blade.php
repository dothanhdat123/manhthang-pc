@extends('client.layouts.index')

@section('client_head')
    <meta name="description"
          content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
          content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <!-- SITE TITLE -->
    <title>{{ hwa_page_title("FAQs") }}</title>
@endsection

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>FAQs</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">FAQs</li>
                    </ol>
                </div>
            </div>
        </div><!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- STAT SECTION FAQ -->
        <div class="section">
            <div class="container">
                <div class="row justify-content-center">
                    @if(count($generals) > 0)
                        <div class="col-md-6">
                        <div class="heading_s1 mb-3 mb-md-5">
                            <h3>Câu hỏi chung</h3>
                        </div>
                        <div id="accordion" class="accordion accordion_style1">
                            @foreach($generals as $general)
                                <div class="card">
                                    <div class="card-header" id="heading{{ $general['id'] }}">
                                        <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapse{{ $general['id'] }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $general['id'] }}">{{ $general['questions'] }}</a> </h6>
                                    </div>
                                    <div id="collapse{{ $general['id'] }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $general['id'] }}" data-parent="#accordion">
                                        <div class="card-body">
                                            <p>{{ $general['answers'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(count($others) > 0)
                        <div class="col-md-6 mt-4 mt-md-0">
                    <div class="heading_s1 mb-3 mb-md-5">
                        <h3>Các câu hỏi khác</h3>
                    </div>
                    <div id="accordion2" class="accordion accordion_style1">
                        @foreach($others as $other)
                            <div class="card">
                                <div class="card-header" id="heading{{ $other['id'] }}">
                                    <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapse{{ $other['id'] }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $other['id'] }}">{{ $other['questions'] }}</a> </h6>
                                </div>
                                <div id="collapse{{ $other['id'] }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $other['id'] }}" data-parent="#accordion2">
                                    <div class="card-body">
                                        <p>{{ $other['answers'] }}</p>
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
        <!-- END SECTION FAQ -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
