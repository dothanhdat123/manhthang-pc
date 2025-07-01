@extends('client.layouts.index')

@section('client_main')
    <!-- START SECTION BREADCRUMB -->
    @yield('breadcrumb')
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="dashboard_menu">
                            <ul class="nav nav-tabs flex-column" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ customer_active_menu('index') }}" id="dashboard-tab"
                                       href="{{ route('client.customers.index') }}" role="tab" aria-controls="dashboard"
                                       aria-selected="false"><i class="ti-layout-grid2"></i>Bảng điều khiển</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ customer_active_menu('orders.index') }}{{ customer_active_menu('orders.show') }}"
                                       href="{{ route('client.customers.orders.index') }}"><i
                                            class="ti-shopping-cart-full"></i>Đơn hàng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ customer_active_menu('address.index') }}{{ customer_active_menu('address.store') }}{{ customer_active_menu('address.update') }}"
                                       href="{{ route('client.customers.address.index') }}"><i
                                            class="ti-location-pin"></i>Địa chỉ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ customer_active_menu('profile') }}"
                                       href="{{ route('client.customers.profile') }}"><i class="ti-id-badge"></i>Chi
                                        tiết tài khoản</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ customer_active_menu('password') }}"
                                       href="{{ route('client.customers.password') }}"><i class="ti-key"></i>Đổi mật
                                        khẩu</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('client.auth.logout') }}"><i class="ti-lock"></i>Đăng
                                        xuất</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="tab-content dashboard_content">
                            @yield('content')
                            <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>Orders</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>#1234</td>
                                                    <td>March 15, 2020</td>
                                                    <td>Processing</td>
                                                    <td>$78.00 for 1 item</td>
                                                    <td><a href="#" class="btn btn-fill-out btn-sm">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>#2366</td>
                                                    <td>June 20, 2020</td>
                                                    <td>Completed</td>
                                                    <td>$81.00 for 1 item</td>
                                                    <td><a href="#" class="btn btn-fill-out btn-sm">View</a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

    </div>
    <!-- END MAIN CONTENT -->
@endsection
