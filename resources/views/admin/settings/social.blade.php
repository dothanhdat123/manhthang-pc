@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Đăng nhập mạng xã hội") }}</title>
    <meta content="{{ hwa_page_title("Đăng nhập mạng xã hội") }}" name="description"/>
@endsection

@section('admin_style')

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
                            <li class="breadcrumb-item"><a href="{{ route("{$path}.index") }}">Thiết lập</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Đăng nhập mạng xã hội</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row d-flex justify-content-sm-center">
            <div class="col-sm-9">
                <form action="{{ route("{$path}.social_login") }}" class="form-horizontal" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Đăng nhập mạng xã hội</h4>
                            <label class="mt-3 text-muted">Định cấu hình các tùy chọn đăng nhập xã hội</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check mb-3 mt-3">
                                        <input class="form-check-input" type="checkbox" name="social_login_enable"
                                               @if(hwa_setting('social_login_enable')) checked @endif
                                               id="social_login_enable" onchange="valueChanged()">
                                        <label class="form-check-label" for="social_login_enable">
                                            Bật?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="social-login">
                        <div class="row mb-3">
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="title mt-3">Cài đặt đăng nhập Google</h4>
                                <label class="mt-3 text-muted">Bật / tắt và định cấu hình thông tin đăng nhập ứng dụng để đăng nhập Google</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-3 mt-3">
                                            <input class="form-check-input" type="checkbox" name="social_login_google_enable"
                                                   @if(hwa_setting('social_login_google_enable')) checked @endif
                                                   onchange="socialValueChanged('#social_login_google_enable', '.google-login')"
                                                   id="social_login_google_enable">
                                            <label class="form-check-label" for="social_login_google_enable">
                                                Bật?
                                            </label>
                                        </div>

                                        <div class="google-login">
                                            <div class="mb-3">
                                                <label for="social_login_google_app_id">App ID: </label>
                                                <input type="text" class="form-control" name="social_login_google_app_id"
                                                       id="social_login_google_app_id"
                                                       value="{{ old('social_login_google_app_id') ?? hwa_setting('social_login_google_app_id') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="social_login_google_app_secret">App Secret: </label>
                                                <input type="text" class="form-control" name="social_login_google_app_secret"
                                                       id="social_login_google_app_secret"
                                                       value="{{ old('social_login_google_app_secret') ?? hwa_setting('social_login_google_app_secret') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="font-weight-normal text-muted">
                                                    Tạo dự án mới: <a
                                                        href="https://console.developers.google.com/projectcreate"
                                                        class="text-decoration-none" target="_blank">https://console.developers.google.com/projectcreate</a>
                                                </label>
                                                <label class="font-weight-normal text-muted">
                                                    Lấy thông tin xác thực: <a
                                                        href="https://console.developers.google.com/apis/credentials"
                                                        class="text-decoration-none" target="_blank">https://console.developers.google.com/apis/credentials</a>
                                                </label>
                                                <label class="font-weight-normal text-muted">
                                                    URI chuyển hướng OAuth hợp lệ: <a
                                                        href="{{ route('admin.auth.social.callback', 'google') }}"
                                                        class="text-decoration-none">{{ route('admin.auth.social.callback', 'google') }}</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Google social login settings -->

                        <div class="row mb-3">
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="title mt-3">Cài đặt đăng nhập Facebook</h4>
                                <label class="mt-3 text-muted">Bật / tắt và định cấu hình thông tin đăng nhập Facebook</label>
                            </div>

                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-3 mt-3">
                                            <input class="form-check-input" type="checkbox" name="social_login_facebook_enable"
                                                   @if(hwa_setting('social_login_facebook_enable')) checked @endif
                                                   onchange="socialValueChanged('#social_login_facebook_enable', '.facebook-login')"
                                                   id="social_login_facebook_enable">
                                            <label class="form-check-label" for="social_login_facebook_enable">
                                                Bật?
                                            </label>
                                        </div>

                                        <div class="facebook-login">
                                            <div class="mb-3">
                                                <label for="social_login_facebook_app_id">App ID: </label>
                                                <input type="text" class="form-control" name="social_login_facebook_app_id"
                                                       id="social_login_facebook_app_id"
                                                       value="{{ old('social_login_facebook_app_id') ?? hwa_setting('social_login_facebook_app_id') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="social_login_facebook_app_secret">App Secret: </label>
                                                <input type="text" class="form-control" name="social_login_facebook_app_secret"
                                                       id="social_login_facebook_app_secret"
                                                       value="{{ old('social_login_facebook_app_secret') ?? hwa_setting('social_login_facebook_app_secret') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="font-weight-normal text-muted">
                                                    Lấy App ID và Secret Key từ: <a
                                                        href="https://developers.facebook.com"
                                                        class="text-decoration-none" target="_blank">https://developers.facebook.com</a>
                                                </label>
                                                <label class="font-weight-normal text-muted">
                                                    URI chuyển hướng OAuth hợp lệ: <a
                                                        href="{{ route('admin.auth.social.callback', 'facebook') }}"
                                                        class="text-decoration-none">{{ route('admin.auth.social.callback', 'facebook') }}</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Facebook social login settings -->

                        <div class="row mb-3">
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="title mt-3">Cài đặt đăng nhập Twitter</h4>
                                <label class="mt-3 text-muted">Bật / tắt và định cấu hình thông tin đăng nhập Twitter</label>
                            </div>

                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-3 mt-3">
                                            <input class="form-check-input" type="checkbox" name="social_login_twitter_enable"
                                                   @if(hwa_setting('social_login_twitter_enable')) checked @endif
                                                   onchange="socialValueChanged('#social_login_twitter_enable', '.twitter-login')"
                                                   id="social_login_twitter_enable">
                                            <label class="form-check-label" for="social_login_twitter_enable">
                                                Bật?
                                            </label>
                                        </div>

                                        <div class="twitter-login">
                                            <div class="mb-3">
                                                <label for="social_login_twitter_app_id">App ID: </label>
                                                <input type="text" class="form-control" name="social_login_twitter_app_id"
                                                       id="social_login_twitter_app_id"
                                                       value="{{ old('social_login_twitter_app_id') ?? hwa_setting('social_login_twitter_app_id') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="social_login_twitter_app_secret">App Secret: </label>
                                                <input type="text" class="form-control" name="social_login_twitter_app_secret"
                                                       id="social_login_twitter_app_secret"
                                                       value="{{ old('social_login_twitter_app_secret') ?? hwa_setting('social_login_twitter_app_secret') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="font-weight-normal text-muted">
                                                    Lấy App ID và Secret Key từ: <a
                                                        href="https://developer.twitter.com/en/portal/dashboard"
                                                        class="text-decoration-none" target="_blank">https://developer.twitter.com/en/portal/dashboard</a>
                                                </label>
                                                <label class="font-weight-normal text-muted">
                                                    URI chuyển hướng OAuth hợp lệ: <a
                                                        href="{{ route('admin.auth.social.callback', 'twitter') }}"
                                                        class="text-decoration-none">{{ route('admin.auth.social.callback', 'twitter') }}</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Twitter social login settings -->

                        <div class="row mb-3">
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="title mt-3">Cài đặt đăng nhập Linkedin</h4>
                                <label class="mt-3 text-muted">Bật / tắt và định cấu hình thông tin đăng nhập để đăng nhập Linkedin</label>
                            </div>

                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-3 mt-3">
                                            <input class="form-check-input" type="checkbox" name="social_login_linkedin_enable"
                                                   @if(hwa_setting('social_login_linkedin_enable')) checked @endif
                                                   onchange="socialValueChanged('#social_login_linkedin_enable', '.linkedin-login')"
                                                   id="social_login_linkedin_enable">
                                            <label class="form-check-label" for="social_login_linkedin_enable">
                                                Bật?
                                            </label>
                                        </div>

                                        <div class="linkedin-login">
                                            <div class="mb-3">
                                                <label for="social_login_linkedin_app_id">App ID: </label>
                                                <input type="text" class="form-control" name="social_login_linkedin_app_id"
                                                       id="social_login_linkedin_app_id"
                                                       value="{{ old('social_login_linkedin_app_id') ?? hwa_setting('social_login_linkedin_app_id') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="social_login_linkedin_app_secret">App Secret: </label>
                                                <input type="text" class="form-control" name="social_login_linkedin_app_secret"
                                                       id="social_login_linkedin_app_secret"
                                                       value="{{ old('social_login_linkedin_app_secret') ?? hwa_setting('social_login_linkedin_app_secret') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="font-weight-normal text-muted">
                                                    Lấy App ID và Secret Key từ: <a
                                                        href="https://www.linkedin.com/developers/apps/new"
                                                        class="text-decoration-none" target="_blank">https://www.linkedin.com/developers/apps/new</a>
                                                </label>
                                                <label class="font-weight-normal text-muted">
                                                    URI chuyển hướng OAuth hợp lệ: <a
                                                        href="{{ route('admin.auth.social.callback', 'linkedin') }}"
                                                        class="text-decoration-none">{{ route('admin.auth.social.callback', 'linkedin') }}</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Linkedin social login settings -->

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
    <script type="text/javascript">
        function valueChanged() {
            if ($('#social_login_enable').is(":checked"))
                $(".social-login").show();
            else
                $(".social-login").hide();
        }

        function socialValueChanged(checkbox, div) {
            if ($(checkbox).is(":checked"))
                $(div).show();
            else
                $(div).hide();
        }

        function checkSocial(checkbox, div) {
            if (!$(checkbox).is(':checked')) {
                $(div).hide();
            }
        }

        checkSocial('#social_login_enable', '.social-login');
        checkSocial('#social_login_facebook_enable', '.facebook-login');
        checkSocial('#social_login_google_enable', '.google-login');
        checkSocial('#social_login_twitter_enable', '.twitter-login');
        checkSocial('#social_login_linkedin_enable', '.linkedin-login');
    </script>
@endsection
