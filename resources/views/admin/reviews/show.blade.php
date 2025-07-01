@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['subject'] ?? "Chi tiết đánh giá" ) }}</title>
    <meta
        content="{{ hwa_page_title($result['subject'] ?? "Chi tiết đánh giá" ) }}" name="description"/>
@endsection
@section('admin_style')
    <!-- Bootstrap Rating css -->
    <link href="assets/libs/bootstrap-rating/bootstrap-rating.css" rel="stylesheet" type="text/css"/>
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
                                <a href="{{ route("{$path}.index") }}">Đánh giá</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết đánh giá</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin khách hàng</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">Khách hàng: <a
                                    href="{{ route('admin.customers.edit', $result['customer']['id']) }}">{{ $result['customer']['name'] }}</a>
                            </li>
                            <li class="list-group-item">Email: <a
                                    href="mailto:{{ $result['customer']['email'] }}">{{ $result['customer']['email'] }}</a>
                            </li>
                            <li class="list-group-item">Ngày
                                đăng: {{ Carbon\Carbon::parse($result['created_at'])->format('H:i:s d/m/Y') }}</li>
                            <li class="list-group-item">Trạng thái:
                                @if($result['active'] == 'published')
                                    <span class='badge badge-pill badge-soft-success font-size-11'
                                          style='line-height: unset!important;'>Chấp nhận</span>
                                @elseif($result['active'] == 'unpublished')
                                    <span class='badge badge-pill badge-soft-danger font-size-11'
                                          style='line-height: unset!important;'>Không xét duyệt</span>
                                @else
                                    <span class='badge badge-pill badge-soft-warning font-size-11'
                                          style='line-height: unset!important;'>Chờ duyệt</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                @can('edit_review')
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" action="{{ route("{$path}.update", $result['id']) }}"
                                  method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                    <select name="active" id="active" class="form-control">
                                        <option
                                            value="draft" {{ old('active', $result['active']) == 'draft' ? 'selected' : '' }}>
                                            Chờ duyệt
                                        </option>
                                        <option
                                            value="published" {{ old('active', $result['active']) == 'published' ? 'selected' : '' }}>
                                            Chấp nhận
                                        </option>
                                        <option
                                            value="unpublished" {{ old('active', $result['active']) == 'unpublished' ? 'selected' : '' }}>
                                            Không duyệt
                                        </option>
                                    </select>
                                    @if($errors->has('active'))
                                        <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3 text-center">
                                    <button type="submit"
                                            class="btn btn-success waves-effect waves-light me-3"><i
                                            class="bx bx-check-double font-size-16 align-middle me-2"></i> Lưu thông tin
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
            <!-- end col -->

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4>
                                <a href="{{ route('admin.products.edit', $result['product']['id']) }}">{{ $result['product']['name'] }}</a>
                            </h4>
                            <hr>
                            <div class="mb-3">
                                <div class="rating-star">
                                    <input type="hidden" class="rating" data-filled="mdi mdi-star text-primary"
                                           data-empty="mdi mdi-star-outline text-muted" data-readonly
                                           value="{{ $result['point'] ?? 0 }}"/>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p>
                                    {{ $result['comment'] ?? "" }}
                                </p>
                            </div>
                            <div class="text-end">
                                    <span>
                                        <i class="bx bx-time-five align-middle mr-1"></i> {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>
@endsection

@section('admin_script')
    <!-- Bootstrap rating js -->
    <script src="assets/libs/bootstrap-rating/bootstrap-rating.min.js"></script>

    <script src="assets/js/pages/rating-init.js"></script>
@endsection
