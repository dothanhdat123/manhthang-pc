@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Đơn hàng") }}</title>
    <meta content="{{ hwa_page_title("Đơn hàng") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Đơn hàng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title-right">
                                <h4 class="card-title">Đơn hàng</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route("admin.export.orders.index") }}">
                                <i class="bx bx-export me-2"></i> Xuất Excel
                            </a>
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Khách hàng</th>
                                <th class="text-center align-middle">Tổng tiền</th>
                                <th class="text-center align-middle">Thanh toán</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                <th class="text-center align-middle">Ngày tạo</th>
                                @canany(['view_order', 'delete_order'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle">{{ $result['id'] ?? "" }}</td>
                                    <td class="align-middle">{{ $result['customer_address']['name'] ?? "" }}</td>
                                    <td class="text-center align-middle">{{ number_format($result['total']) ?? 0 }}
                                        vnđ
                                    </td>
                                    <td class="text-center align-middle">
                                        {!! hwa_order_payment_method($result['payment_method'] ?? '') . " " . hwa_order_payment_status($result['payment_status'] ?? '') !!}
                                    </td>
                                    <td class="text-center align-middle">{!! hwa_order_active($result['active'] ?? '') !!}</td>
                                    <td class="text-center align-middle">
                                        {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}
                                    </td>
                                    @canany(['view_order', 'delete_order'])
                                        <td class="text-center align-middle">
                                            @can('view_order')
                                                <a href="{{ route("{$path}.show", $result['id']) }}"
                                                   class="btn btn-success me-3"><i
                                                        class="mdi mdi-eye-outline"></i></a>
                                            @endcan
                                            @can('delete_order')
                                                <a href="javascript:void(0)" data-id="{{ $result['id'] }}"
                                                   data-message="Bạn có thực sự muốn xóa bản ghi này không?"
                                                   data-url="{{ route("{$path}.destroy", $result['id']) }}"
                                                   class="btn btn-danger delete" data-bs-toggle="modal"
                                                   data-bs-target=".deleteModal"><i class="bx bx-trash"></i></a>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.database.script')
@endsection
