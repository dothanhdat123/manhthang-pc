@extends('admin.layouts.index')
@section('admin_main')
    <body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

    @include('admin.includes.header')

    <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Side menu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        @foreach(hwaCore()->getAdminMenu() as $menu)
                            @if(isset($menu['permissions']))
                                @canany($menu['permissions'])
                                    <li>
                                        <a href="{{ (!isset($menu['items']) && !empty($menu['route'])) ? route("admin.{$menu['route']}") : 'javascript:void(0);' }}"
                                           class="{{ isset($menu['items']) ? 'has-arrow' : '' }} waves-effect">
                                            <i class="bx {{ $menu['icon'] }}"></i>
                                            <span>{{ $menu['label'] }}</span>
                                        </a>
                                        @if(isset($menu['items']))
                                            <ul class="sub-menu" aria-expanded="false">
                                                @foreach($menu['items'] as $subMenu)
                                                    @can($subMenu['permission'])
                                                        <li>
                                                            <a href="{{ !empty($subMenu['route']) ? route("admin.{$subMenu['route']}") : 'javascript:void(0);' }}"><i
                                                                    class="bx {{ $subMenu['icon'] ?? '' }}"></i> {{ $subMenu['label'] }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endcanany
                            @else
                                <li>
                                    <a href="{{ (!isset($menu['items']) && !empty($menu['route'])) ? route("admin.{$menu['route']}") : 'javascript:void(0);' }}"
                                       class="waves-effect">
                                        <i class="bx {{ $menu['icon'] }}"></i>
                                        <span>{{ $menu['label'] }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
            @yield('admin_content')
            <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- Transaction Modal -->
            <div class="modal fade deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-top" role="document">
                    <div class="modal-content">
                        <form id="deleteForm" action="" method="post">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header" style="border: none;">

                            </div>
                            <div class="modal-body">
                                <h5 class="text-center" id="deleteMessage"></h5>
                            </div>
                            <div class="modal-footer d-flex align-items-center justify-content-center"
                                 style="border: none">
                                <button type="submit" class="btn btn-danger me-2"><i class="bx bx-trash"></i> Xóa
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end modal -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            Copyright © {{ date('Y') }} to <a
                                href="{{ route('admin.home') }}"><b>{{ hwa_app_name() }}</b></a>.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Tải trong {{ round((microtime(true) - LARAVEL_START), 2) }} giây
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @include('admin.includes.script')
    </body>
@endsection
