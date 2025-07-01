@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Thiết lập cửa hàng") }}</title>
    <meta content="{{ hwa_page_title("Thiết lập cửa hàng") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.select2.style')
    @include('admin.includes.dropify.style')
@endsection

@section('admin_content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><span><i
                                            class="bx bxs-home-circle"></i> Bảng điều khiển</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thiết lập cửa hàng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row d-flex justify-content-sm-center">
            <div class="col-sm-9">
                <form action="{{ route("{$path}.shop") }}" class="form-horizontal" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Thông tin chung</h4>
                            <label class="mt-3 text-muted">Cài đặt thông tin cửa hàng.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_title">Tiêu đề cửa hàng:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_title') ? 'is-invalid' : '' }}"
                                               name="site_title" id="site_title"
                                               placeholder="Nhập tiêu đề cửa hàng"
                                               value="{{ old('site_title') ?? hwa_setting('site_title', hwa_app_name()) }}">
                                        @error('site_title')
                                        <p class="text-danger mt-2">{{ $errors->first('site_title') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_phone">Số điện thoại:</label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('site_phone') ? 'is-invalid' : '' }}"
                                               name="site_phone" id="site_phone"
                                               placeholder="Nhập số điện thoại"
                                               value="{{ old('site_phone') ?? hwa_setting('site_phone') }}">
                                        @error('site_phone')
                                        <p class="text-danger mt-2">{{ $errors->first('site_phone') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_email">Email:</label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('site_email') ? 'is-invalid' : '' }}"
                                               name="site_email" id="site_email"
                                               placeholder="{{ "contact@" . hwa_app_domain() }}"
                                               value="{{ old('site_email') ?? hwa_setting('site_email') }}">
                                        @error('site_email')
                                        <p class="text-danger mt-2">{{ $errors->first('site_email') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_address">Địa chỉ:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_address') ? 'is-invalid' : '' }}"
                                               name="site_address" id="site_address"
                                               placeholder="Nhập địa chỉ"
                                               value="{{ old('site_address') ?? hwa_setting('site_address', '') }}">
                                        @error('site_address')
                                        <p class="text-danger mt-2">{{ $errors->first('site_address') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Giao diện cửa hàng</h4>
                            <label class="mt-3 text-muted">Thiết lập giao diện cửa hàng như favicon, logo, ...</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_description">Mô tả:</label>
                                        <textarea name="site_description" id="site_description"
                                                  class="form-control {{ $errors->has('site_title') ? 'is-invalid' : '' }}"
                                                  cols="30" rows="4"
                                                  placeholder="Nhập mô tả">{{ old('site_description') ?? hwa_setting('site_description', '') }}</textarea>
                                        @error('site_description')
                                        <p class="text-danger mt-2">{{ $errors->first('site_description') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_keyword">Từ khóa:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_keyword') ? 'is-invalid' : '' }}"
                                               name="site_keyword" id="site_keyword"
                                               placeholder="Nhập từ khóa (mỗi từ khóa cách nhau dấu phẩy)"
                                               value="{{ old('site_keyword') ?? hwa_setting('site_keyword', '') }}">
                                        @error('site_keyword')
                                        <p class="text-danger mt-2">{{ $errors->first('site_keyword') }}</p>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="site_favicon">Favicon:</label>
                                                <input type="file" class="dropify"
                                                       name="site_favicon" {{ hwa_setting('site_favicon') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('site_favicon')) : "" }}>
                                                @error('site_favicon')
                                                <p class="text-danger mt-2">{{ $errors->first('site_favicon') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="site_logo_dark">Logo tối:</label>
                                                <input type="file" class="dropify"
                                                       name="site_logo_dark" {{ hwa_setting('site_logo_dark') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('site_logo_dark')) : "" }}>
                                                @error('site_logo_dark')
                                                <p class="text-danger mt-2">{{ $errors->first('site_logo_dark') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="site_logo_light">Logo sáng:</label>
                                                <input type="file" class="dropify"
                                                       name="site_logo_light" {{ hwa_setting('site_logo_light') ? 'data-default-file=' . hwa_image_url("system", hwa_setting('site_logo_light')) : "" }}>
                                                @error('site_logo_light')
                                                <p class="text-danger mt-2">{{ $errors->first('site_logo_light') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Bản đồ:</h4>
                            <label class="mt-3 text-muted">Bản đồ trang liên hệ.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_google_map">Đường dẫn:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_social_facebook') ? 'is-invalid' : '' }}"
                                               name="site_google_map" id="site_google_map"
                                               placeholder="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3710.032678754447!2d105.8045425154288!3d21.584647473943186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313527c1c758f793%3A0xeb1d640638f04b62!2sAnam%20Software!5e0!3m2!1svi!2s!4v1640800724276!5m2!1svi!2s"
                                               value="{{ old('site_google_map') ?? hwa_setting('site_google_map', '') }}">
                                        @error('site_google_map')
                                        <p class="text-danger mt-2">{{ $errors->first('site_google_map') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <iframe
                                            src="{{ hwa_setting('site_google_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3710.032678754447!2d105.8045425154288!3d21.584647473943186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313527c1c758f793%3A0xeb1d640638f04b62!2sAnam%20Software!5e0!3m2!1svi!2s!4v1640800724276!5m2!1svi!2s') }}"
                                            height="250" style="border:0; width: 100%;" allowfullscreen="" loading="lazy"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Liên kết mạng xã hội</h4>
                            <label class="mt-3 text-muted">Thông tin các liên kết mạng xã hội.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="site_social_facebook">Facebook:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_social_facebook') ? 'is-invalid' : '' }}"
                                               name="site_social_facebook" id="site_social_facebook"
                                               placeholder="https://www.facebook.com/username"
                                               value="{{ old('site_social_facebook') ?? hwa_setting('site_social_facebook', '') }}">
                                        @error('site_social_facebook')
                                        <p class="text-danger mt-2">{{ $errors->first('site_social_facebook') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_social_twitter">Twitter:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_social_twitter') ? 'is-invalid' : '' }}"
                                               name="site_social_twitter" id="site_social_twitter"
                                               placeholder="https://twitter.com/minecraft"
                                               value="{{ old('site_social_twitter') ?? hwa_setting('site_social_twitter') }}">
                                        @error('site_social_twitter')
                                        <p class="text-danger mt-2">{{ $errors->first('site_social_twitter') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_social_youtube">Youtube:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_social_youtube') ? 'is-invalid' : '' }}"
                                               name="site_social_youtube" id="site_social_youtube"
                                               placeholder="https://www.youtube.com/username"
                                               value="{{ old('site_social_youtube') ?? hwa_setting('site_social_youtube') }}">
                                        @error('site_social_youtube')
                                        <p class="text-danger mt-2">{{ $errors->first('site_social_youtube') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="site_social_instagram">Instagram:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('site_social_instagram') ? 'is-invalid' : '' }}"
                                               name="site_social_instagram" id="site_social_instagram"
                                               placeholder="https://www.instagram.com/username"
                                               value="{{ old('site_social_instagram') ?? hwa_setting('site_social_instagram', '') }}">
                                        @error('site_social_instagram')
                                        <p class="text-danger mt-2">{{ $errors->first('site_social_instagram') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="mb-3 mt-3 text-center justify-content-center">
                                <button type="submit"
                                        class="btn btn-success waves-effect waves-light"><i
                                        class="bx bx-check-double font-size-16 align-middle me-2"></i> Lưu thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                </form>
            </div>
        </div>
        <!-- End row -->

    </div>
@endsection

@section('admin_script')
    @include('admin.includes.select2.script')
    @include('admin.includes.dropify.script')
@endsection
