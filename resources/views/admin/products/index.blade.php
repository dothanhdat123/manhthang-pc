@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Sản phẩm") }}</title>
    <meta content="{{ hwa_page_title("Sản phẩm") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
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
                                <h4 class="card-title">Sản phẩm</h4>
                            </div>
                            @can('add_product')
                                <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                    <i class="mdi mdi-plus me-2"></i> Thêm mới
                                </a>
                            @endcan
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">Hình ảnh</th>
                                <th class="text-center align-middle">Tên</th>
                                <th class="text-center align-middle">Giá</th>
                                <th class="text-center align-middle">Số lượng</th>
                                <th class="text-center align-middle">Hạn sử dụng</th>
                                <th class="text-center align-middle">Lượt xem</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                @canany(['edit_product', 'delete_product'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle text-center">
                                        <img
                                            src="{{ !empty($result['thumb']) ? hwa_image_url('products/thumbs', $result['thumb']) : '' }}"
                                            alt="" class="img-thumbnail" width="100">
                                    </td>
                                    <td class="align-middle">
                                        <p class="text-uppercase fw-bold">
                                            <a href="{{ route('client.product.show', $result['slug']) }}"
                                               target="_blank">{{ $result['name'] ?? "" }}</a>
                                        </p>
                                        <p><span class="text-uppercase fw-bold">- SKU:</span> {{ $result['sku'] ?? "" }}
                                        </p>
                                        <p><span
                                                class="fw-bold">- Danh mục:</span> {{ $result['category']['name'] ?? "" }}
                                        </p>
                                        <p><span class="fw-bold">- Nguồn:</span> {{ $result['supplier']['name'] ?? "" }}
                                        </p>
                                        <p class="chat-time text-muted mb-2 mt-2 text-end me-2"><i
                                                class="bx bx-time-five align-middle me-1"></i>
                                            {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        <p>Nhập: {{ number_format($result['unit_price']) ?? 0 }} vnđ</p>
                                        <p>Bán: {{ number_format($result['price']) ?? 0 }} vnđ</p>
                                    </td>
                                    <td class="align-middle">
                                        <p>Kho: {{ $result['quantity'] ?? 0 }}</p>
                                        <p>Bán: {{ $result['sale'] ?? 0 }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <p>
                                            NSX: {{ Carbon\Carbon::parse($result['start_at'])->format('d/m/Y') ?? "" }}</p>
                                        <p>
                                            HSD: {{ Carbon\Carbon::parse($result['expired_at'])->format('d/m/Y') ?? "" }}</p>
                                    </td>
                                    <td class="text-center align-middle">{{ $result['views'] ?? 0 }}</td>
                                    <td class="text-center align-middle">
                                        @if($result['active'] == 'published')
                                            <span class='badge badge-pill badge-soft-success font-size-11'
                                                  style='line-height: unset!important;'>Đang bán</span>
                                        @elseif($result['active'] == 'draft')
                                            <span class='badge badge-pill badge-soft-warning font-size-11'
                                                  style='line-height: unset!important;'>Bản nháp</span>
                                        @else
                                            <span class='badge badge-pill badge-soft-danger font-size-11'
                                                  style='line-height: unset!important;'>Ngừng bán</span>
                                        @endif
                                    </td>
                                    @canany(['edit_product', 'delete_product'])
                                        <td class="text-center align-middle">
                                            @can('edit_product')
                                                <a href="{{ route("{$path}.edit", $result['id']) }}"
                                                   class="btn btn-primary me-3"><i
                                                        class="bx bx-pencil"></i></a>
                                            @endcan
                                            @can('delete_product')
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
