@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['subject'] ?? "Chi tiết khách hàng" ) }}</title>
    <meta
        content="{{ hwa_page_title($result['subject'] ?? "Chi tiết khách hàng" ) }}" name="description"/>
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
                            <li class="breadcrumb-item">
                                <a href="{{ route("{$path}.index") }}">Khách hàng</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết khách hàng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin khách hàng</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">Khách hàng: <a
                                    href="{{ route('admin.customers.edit', $result['id']) }}">{{ $result['name'] }}</a>
                            </li>
                            <li class="list-group-item">Tên người dùng: {{ $result['username'] }}</li>
                            <li class="list-group-item">Email: <a
                                    href="mailto:{{ $result['email'] }}">{{ $result['email'] }}</a></li>
                            @if(isset($result['phone']) && !empty($result['phone']))
                                <li class="list-group-item">SĐT: <a
                                        href="tel:{{ $result['phone'] }}">{{ $result['phone'] }}</a></li>
                            @endif
                            @if(isset($result['gender']) && !empty($result['gender']))
                                <li class="list-group-item">
                                    @if($result['gender'] == 'male')
                                        Giới tính: Nam
                                    @else
                                        Giới tính: Nữ
                                    @endif
                                </li>
                            @endif
                            <li class="list-group-item">Ngày tham
                                gia: {{ Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}</li>
                            <li class="list-group-item">Trạng thái:
                                @if($result['active'] == '1')
                                    <span class='badge badge-pill badge-soft-success font-size-11'
                                          style='line-height: unset!important;'>Bình thường</span>
                                @else
                                    <span class='badge badge-pill badge-soft-danger font-size-11'
                                          style='line-height: unset!important;'>Bị khóa</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4>
                                Danh sách địa chỉ nhận hàng
                            </h4>
                            <hr>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-center align-middle">#</th>
                                    <th class="text-center align-middle">Họ tên</th>
                                    <th class="text-center align-middle">SĐT</th>
                                    <th class="text-center align-middle">Địa chỉ</th>
                                    <th class="text-center align-middle">Trạng thái</th>
                                    @can('delete_address_customer')
                                        <th class="text-center align-middle">Hành động</th>
                                    @endcan
                                </tr>
                                </thead>

                                <tbody>
                                @if(isset($result['customer_addresses']))
                                    @foreach($result['customer_addresses'] as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $item['id'] ?? "-" }}</td>
                                            <td class="align-middle">{{ $item['name'] ?? "" }}</td>
                                            <td class="text-center align-middle">{{ $item['phone'] ?? "" }}</td>
                                            <td class="align-middle">{{ $item['address'] ?? "" }}</td>
                                            <td class="text-center align-middle">
                                                @if($item['is_default'] == 1)
                                                    <span class='badge badge-pill badge-soft-success font-size-11'
                                                          style='line-height: unset!important;'>Mặc định</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @can('delete_address_customer')
                                                <td class="text-center align-middle">
                                                    <a href="javascript:void(0)" data-id="{{ $item['id'] }}"
                                                       data-message="Bạn có thực sự muốn xóa bản ghi này không?"
                                                       data-url="{{ route("{$path}.addresses.destroy", ['customer' => $result['id'], 'address' => $item['id']]) }}"
                                                       class="btn btn-danger delete" data-bs-toggle="modal"
                                                       data-bs-target=".deleteModal"><i class="bx bx-trash"></i></a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
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
