@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Thông tin hệ thống") }}</title>
    <meta content="{{ hwa_page_title("Thông tin hệ thống") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.database.style')
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
                            <li class="breadcrumb-item active" aria-current="page">Quản trị hệ thống</li>
                            <li class="breadcrumb-item active" aria-current="page">Thông tin hệ thống</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Các gói đã cài đặt và phiên bản</h4>
                        <hr>
                        <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle text-uppercase">Tên gói: phiên bản</th>
                                <th class="text-center align-middle text-uppercase">Tên gói phụ thuộc: phiên bản</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($packages as $item)
                                <tr>
                                    <td class="align-middle">
                                        {{ $item['name'] }} : <span
                                            class='badge badge-pill badge-soft-success font-size-11'
                                            style='line-height: unset!important;'>{{ $item['version'] }}</span><br> ({{ is_array($item['dependencies']) ? count($item['dependencies']) : 0 }} gói phụ thuộc)
                                    </td>
                                    <td class="align-middle">
                                        @if(is_array($item['dependencies']))
                                            @foreach($item['dependencies'] as $dependencyName => $dependencyVersion)
                                                <li class="list-unstyled">{{ $dependencyName }} : <span
                                                        class='badge badge-pill badge-soft-success font-size-11'
                                                        style='line-height: unset!important;'>{{ $dependencyVersion }}</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <li><span class="label label-primary">{{ $item['dependencies'] }}</span>
                                            </li>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End information -->

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin hệ thống</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">Hwaravel Version: {{ hwa_app_version() }}</li>
                            <li class="list-group-item">Framework Version: {{ $systemEnv['version'] }}</li>
                            <li class="list-group-item">Múi giờ: {{ $systemEnv['timezone'] }}</li>
                            <li class="list-group-item">Chế độ debug: {!! $systemEnv['debug_mode'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Quyền ghi Storage: {!! $systemEnv['storage_dir_writable'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Quyền ghi Cache: {!! $systemEnv['cache_dir_writable'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Kích thước: {{ $systemEnv['app_size'] }}</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin máy chủ</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">PHP Version: {{ $serverEnv['version'] }} @if ($matchPHPRequirement) <span class="text-success fas fa-check"></span> @else <span class="text-danger fas fa-times"></span> <span class="text-danger">(PHP must be >= {{ $requiredPhpVersion }})</span> @endif</li>
                            <li class="list-group-item">Server Software: {{ $serverEnv['server_software'] }}</li>
                            <li class="list-group-item">Server OS: {{ $serverEnv['server_os'] }}</li>
                            <li class="list-group-item">Database: {{ $serverEnv['database_connection_name'] }}</li>
                            <li class="list-group-item">SSL Installed: {!! $serverEnv['ssl_installed'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Cache Driver: {{ $serverEnv['cache_driver'] }}</li>
                            <li class="list-group-item">Session Driver: {{ $serverEnv['session_driver'] }}</li>
                            <li class="list-group-item">Queue Driver: {{ $serverEnv['queue_connection'] }}</li>
                            <li class="list-group-item">OpenSSL Ext: {!! $serverEnv['openssl'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Mbstring Ext: {!! $serverEnv['mbstring'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">SSL Ext: {!! $serverEnv['pdo'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">CURL Ext: {!! $serverEnv['curl'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Exif Ext: {!! $serverEnv['exif'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">File info Ext: {!! $serverEnv['fileinfo'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Tokenizer Ext: {!! $serverEnv['tokenizer'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                            <li class="list-group-item">Imagick/GD Ext: {!! $serverEnv['imagick_or_gd'] ? '<span class="text-success fas fa-check"></span>' : '<span class="text-danger fas fa-times"></span>' !!}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End system info -->
        </div>


    </div>
@endsection

@section('admin_script')
    @include('admin.includes.database.script')
@endsection
