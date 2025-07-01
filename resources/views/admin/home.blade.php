@extends('admin.layouts.master')
@section('admin_head')
    <title>{{ hwa_page_title('Bảng điều khiển') }}</title>
    <meta content="{{ hwa_page_title('Bảng điều khiển') }}" name="description"/>
@endsection

@section('admin_content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active" aria-current="page">
                                <span><i class="bx bxs-home-circle"></i> Bảng điều khiển</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium text-uppercase">Sản phẩm</p>
                                        <h4 class="mb-0">{{ $card['product'] ?? 0 }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                            <a href="{{ route('admin.products.index') }}">
                                                <span class="avatar-title">
                                                    <i class="bx bx-home-alt font-size-24"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium text-uppercase">Đơn hàng mới</p>
                                        <h4 class="mb-0">{{ $card['order'] ?? 0 }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center ">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <a href="{{ route('admin.orders.index') }}">
                                                <span class="avatar-title rounded-circle bg-success">
                                                    <i class="bx bx-group font-size-24"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium text-uppercase">Khách hàng</p>
                                        <h4 class="mb-0">{{ $card['customer'] ?? 0 }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <a href="{{ route('admin.customers.index') }}">
                                                <span class="avatar-title rounded-circle bg-dark">
                                                    <i class="mdi mdi-account-voice font-size-24"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium text-uppercase">Bài viết</p>
                                        <h4 class="mb-0">{{ $card['news'] ?? 0 }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <a href="{{ route('admin.news.index') }}">
                                                <span class="avatar-title rounded-circle bg-danger">
                                                    <i class="bx bx-detail font-size-24"></i>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title-right">
                                <h4 class="card-title">Thu nhập</h4>
                            </div>
                            <label class="text-dark">Năm {{ \Carbon\Carbon::now()->year }}</label>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="text-muted">
                                    <div class="mb-4">
                                        <p>Tháng này</p>
                                        <h4 class="text-danger">{{ number_format($orders['revenue'] ?? 0) }} đ</h4>
                                        <div>
                                            @if(isset($orders['revenueUpOrDown']))
                                                @if($orders['revenueUpOrDown'] >= 0)
                                                    <span class="badge badge-soft-success font-size-12 me-1"> + {{ number_format($orders['revenue'] - $orders['revenueLast']) }} đ </span>
                                                    so với tháng trước
                                                @else
                                                    <span class="badge badge-soft-danger font-size-12 me-1"> - {{ number_format(abs($orders['revenue'] - $orders['revenueLast'])) }} đ </span>
                                                    so với tháng trước
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <p class="mb-2">Tháng trước</p>
                                        <h4>{{ number_format($orders['revenueLast'] ?? 0) }} đ</h4>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div id="line-chart" class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title-right">
                                <h4 class="card-title">Đơn hàng</h4>
                            </div>
                            <label class="text-dark">Năm {{ \Carbon\Carbon::now()->format('m/Y') }}</label>
                        </div>

                        <div class="mb-4">
                            <div id="donut-chart" class="apex-charts"></div>
                        </div>

                        <div class="text-center text-muted">
                            <div class="row">
                                <div class="col-2">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-primary mr-1"></i> Mới
                                        </p>
                                        <h5>{{ $orders['pending'] ?? 0 }}</h5>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-secondary mr-1"></i> Xử lý
                                        </p>
                                        <h5>{{ $orders['processing'] ?? 0 }}</h5>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-warning mr-1"></i> Hủy
                                        </p>
                                        <h5>{{ $orders['cancel'] ?? 0 }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-success mr-1"></i> Thành công
                                        </p>
                                        <h5>{{ $orders['done'] ?? 0 }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i
                                                class="mdi mdi-circle text-danger mr-1"></i> Thất bại
                                        </p>
                                        <h5>{{ $orders['fail'] ?? 0 }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title-right">
                                <h4 class="card-title">Đơn hàng mới</h4>
                            </div>
                            <a href="{{ route('admin.orders.index') }}">Xem tất cả</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0">

                                <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    @can('view_order')
                                        <th>Hành động</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($newestOrders) && count($newestOrders) > 0)
                                    @foreach($newestOrders as $order)
                                        <tr class="text-center align-middle">
                                            <td><a href="{{ route('admin.orders.show', $order['id']) }}"
                                                   class="text-body font-weight-bold">#{{ $order['id'] }}</a>
                                            </td>
                                            <td>{{ $order['customer_address']['name'] ?? "" }}</td>
                                            <td>{{ $order['customer_address']['phone'] ?? "" }}</td>
                                            <td>{{ Carbon\Carbon::parse($order['created_at'])->format('H:i d/m/Y') }}</td>
                                            <td>{{ number_format($order['total'] ?? 0) }} đ</td>
                                            @can('view_order')
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order['id']) }}"
                                                       class="text-dark">
                                                        <i class="bx bx-info-circle text-info me-1"></i>
                                                        <span>Chi tiết</span>
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đơn hàng</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
@endsection

@section('admin_script')
    <!-- apexcharts -->
    <script src="assets\libs\apexcharts\apexcharts.min.js"></script>
    <!-- apexcharts init -->
    <script src="assets\libs\apexcharts\apexcharts.min.js"></script>

    <script>
        let options = {
                series: [{
                    name: "Mới",
                    data: [{{ $chart['pending'] ?? '9,2,3,4,4,6,7,0,4,0,1,2' }}]
                }, {
                    name: "Xử lý",
                    data: [{{ $chart['processing'] ?? '7,2,3,4,9,6,7,0,5,0,1,2' }}]
                },{
                    name: "Hủy",
                    data: [{{ $chart['cancel'] ?? '8,4,3,4,4,6,7,0,12,0,1,2' }}]
                }, {
                    name: "Thành công",
                    data: [{{ $chart['done'] ?? '3,2,3,4,4,6,7,0,20,0,1,2' }}]
                }, {
                    name: "Thất bại",
                    data: [{{ $chart['fail'] ?? '10,2,3,4,4,6,7,17,0,0,1,2' }}]
                }],
                chart: {
                    height: 320,
                    type: "line",
                    toolbar: "false",
                    dropShadow: {
                        enabled: !0,
                        color: "#000",
                        top: 18,
                        left: 7,
                        blur: 8,
                        opacity: .2
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                colors: ["#50a5f1", "#74788d", "#f1b44c", "#34c38f", "#f46a6a"],
                stroke: {
                    curve: "smooth",
                    width: 3
                }
            },
            chart = new ApexCharts(document.querySelector("#line-chart"), options);
        chart.render();

        options = {
            series: [{{ $orders['pending'] ?? 10 }}, {{ $orders['processing'] ?? 20 }}, {{ $orders['cancel'] ?? 30 }}, {{ $orders['done'] ?? 30 }}, {{ $orders['fail'] ?? 10 }}],
            chart: {
                type: "donut",
                height: 240
            },
            labels: [
                "Mới",
                "Xử lý",
                "Đã hủy",
                "Thành công",
                "Thất bại",
            ],
            colors: ["#50a5f1", "#74788d", "#f1b44c", "#34c38f", "#f46a6a"],
            legend: {
                show: !1
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "40%",
                    }
                }
            },
        };
        (chart = new ApexCharts(document.querySelector("#donut-chart"), options)).render();
    </script>

    <!-- Magnific Popup-->
    <script src="assets\libs\magnific-popup\jquery.magnific-popup.min.js"></script>
@endsection
