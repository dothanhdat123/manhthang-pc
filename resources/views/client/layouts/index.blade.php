<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="{{ hwa_app_author() }}" name="author">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:site_name" content="{{ hwa_app_name() }}">

    @yield('client_head')

    <base href="{{ asset("") }}">

    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty(hwa_setting('site_favicon')) ? hwa_image_url('system', hwa_setting('site_favicon')) : 'shopwise/assets/images/favicon.png'}}">
    <!-- Animation CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/animate.css">
    <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="shopwise/assets/bootstrap/css/bootstrap.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:100,300,400,500,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    @toastr_css

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/all.min.css">
    <link rel="stylesheet" href="shopwise/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="shopwise/assets/css/themify-icons.css">
    <link rel="stylesheet" href="shopwise/assets/css/linearicons.css">
    <link rel="stylesheet" href="shopwise/assets/css/flaticon.css">
    <link rel="stylesheet" href="shopwise/assets/css/simple-line-icons.css">
    <!--- owl carousel CSS-->
    <link rel="stylesheet" href="shopwise/assets/owlcarousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="shopwise/assets/owlcarousel/css/owl.theme.css">
    <link rel="stylesheet" href="shopwise/assets/owlcarousel/css/owl.theme.default.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/magnific-popup.css">

    @yield('client_style')

    <!-- Slick CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/slick.css">
    <link rel="stylesheet" href="shopwise/assets/css/slick-theme.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="shopwise/assets/css/style.css">
    <link rel="stylesheet" href="shopwise/assets/css/responsive.css">

    <style type="text/css">
        html, body, h1, h2, h3, h4, h5, h6 {
            font-family: "Roboto Condensed"!important;
        }
    </style>

    <!-- Hotjar Tracking Code for bestwebcreator.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2073024,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

</head>

<body>

<!-- Home Popup Section -->
{{--@include('client.includes.popup')--}}
<!-- End Screen Load Popup Section -->

<!-- START HEADER -->
@include('client.includes.header')
<!-- END HEADER -->

@yield('client_main')

<!-- START FOOTER -->
@include('client.includes.footer')
<!-- END FOOTER -->

<a href="javascript:void(0);" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>

<!-- Latest jQuery -->
<script src="shopwise/assets/js/jquery-1.12.4.min.js"></script>
<!-- jquery-ui -->
<script src="shopwise/assets/js/jquery-ui.js"></script>
<!-- popper min js -->
<script src="shopwise/assets/js/popper.min.js"></script>
<!-- Latest compiled and minified Bootstrap -->
<script src="shopwise/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- owl-carousel min js  -->
<script src="shopwise/assets/owlcarousel/js/owl.carousel.min.js"></script>
<!-- magnific-popup min js  -->
<script src="shopwise/assets/js/magnific-popup.min.js"></script>
<!-- waypoints min js  -->
<script src="shopwise/assets/js/waypoints.min.js"></script>
<!-- parallax js  -->
<script src="shopwise/assets/js/parallax.js"></script>
<!-- countdown js  -->
<script src="shopwise/assets/js/jquery.countdown.min.js"></script>
<!-- imagesloaded js -->
<script src="shopwise/assets/js/imagesloaded.pkgd.min.js"></script>
<!-- isotope min js -->
<script src="shopwise/assets/js/isotope.min.js"></script>
<!-- jquery.dd.min js -->
<script src="shopwise/assets/js/jquery.dd.min.js"></script>
<!-- slick js -->
<script src="shopwise/assets/js/slick.min.js"></script>
<!-- elevatezoom js -->
<script src="shopwise/assets/js/jquery.elevatezoom.js"></script>

@toastr_js
@toastr_render

@yield('client_script')

<!-- scripts js -->
<script src="shopwise/assets/js/scripts.js"></script>

</body>
</html>
