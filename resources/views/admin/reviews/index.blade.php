@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Đánh giá sản phẩm") }}</title>
    <meta content="{{ hwa_page_title("Đánh giá sản phẩm") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.database.style')

    <!-- Bootstrap Rating css -->
    <link href="assets/libs/bootstrap-rating/bootstrap-rating.css" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        .custom-td {
            word-wrap: break-word;
            white-space: normal !important;
            text-align: left !important;
        }
    </style>
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
                            <li class="breadcrumb-item active" aria-current="page">Đánh giá sản phẩm</li>
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
                                <h4 class="card-title">Đánh giá</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                <i class="bx bx-export me-2"></i> Xuất Excel
                            </a>
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle" style="width: 20%;">Sản phẩm</th>
                                <th class="text-center align-middle" style="width: 35%;">Đánh giá</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                @canany(['view_review', 'delete_review'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="text-center align-middle">{{ $result['id'] ?? 0 }}</td>
                                    <td class="align-middle custom-td">
                                        <a href="{{ route("admin.products.edit", $result['product_id']) }}"
                                           class="text-dark fw-bold">
                                            {{ $result['product']['name'] ?? "" }}
                                        </a>
                                    </td>
                                    <td class="align-middle custom-td">
                                        <a href="{{ route("admin.customers.edit", $result['customer_id']) }}">
                                            <span class="text-dark text-uppercase fw-bold"><i
                                                    class="bx bx-user-circle"></i> {{ $result['customer']['name'] ?? "" }}</span>
                                        </a>
                                        <div class="rating-star">
                                            <input type="hidden" class="rating" data-filled="mdi mdi-star text-primary"
                                                   data-empty="mdi mdi-star-outline text-muted" data-readonly
                                                   value="{{ $result['point'] ?? 0 }}"/>
                                        </div>
                                        <hr>
                                        <p>
                                            {{ $result['comment'] ?? "" }}
                                        </p>
                                        <p class="chat-time text-muted mb-2 mt-2 text-end me-2"><i
                                                class="bx bx-time-five align-middle me-1"></i>
                                            {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}
                                        </p>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($result['active'] == 'published')
                                            <span class='badge badge-pill badge-soft-success font-size-11'
                                                  style='line-height: unset!important;'>Đã đăng</span>
                                        @elseif($result['active'] == 'draft')
                                            <span class='badge badge-pill badge-soft-warning font-size-11'
                                                  style='line-height: unset!important;'>Chờ duyệt
                                        @else
                                                    <span class='badge badge-pill badge-soft-danger font-size-11'
                                                          style='line-height: unset!important;'>Hủy đăng</span>
                                        @endif
                                    </td>
                                    @canany(['view_review', 'delete_review'])
                                        <td class="text-center align-middle">
                                            @can('view_review')
                                                <a href="{{ route("{$path}.show", $result['id']) }}"
                                                   class="btn btn-success me-3"><i
                                                        class="mdi mdi-eye-outline"></i></a>
                                            @endcan
                                            @can('delete_review')
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

    <!-- Bootstrap rating js -->
    <script src="assets/libs/bootstrap-rating/bootstrap-rating.min.js"></script>

    <script src="assets/js/pages/rating-init.js"></script>
@endsection
