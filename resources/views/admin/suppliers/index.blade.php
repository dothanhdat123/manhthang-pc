@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Nhà cung cấp") }}</title>
    <meta content="{{ hwa_page_title("Nhà cung cấp") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Nhà cung cấp</li>
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
                                <h4 class="card-title">Nhà cung cấp</h4>
                            </div>
                            @can('add_supplier')
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
                                <th class="text-center align-middle">SĐT</th>
                                <th class="text-center align-middle">Email</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                @canany(['edit_supplier', 'delete_supplier'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle text-center">
                                        <img
                                            src="{{ !empty($result['logo']) ? hwa_image_url('suppliers', $result['logo']) : '' }}"
                                            alt="" class="img-thumbnail" width="100">
                                    </td>
                                    <td class="align-middle">{{ $result['name'] ?? "" }}</td>
                                    <td class="text-center align-middle">{{ $result['phone'] ?? "" }}</td>
                                    <td class="align-middle">{{ $result['email'] ?? "" }}</td>
                                    <td class="text-center align-middle">
                                        @if($result['active'] == 1)
                                            <span class='badge badge-pill badge-soft-success font-size-11'
                                                  style='line-height: unset!important;'>Bật</span>
                                        @else
                                            <span class='badge badge-pill badge-soft-danger font-size-11'
                                                  style='line-height: unset!important;'>Tắt</span>
                                        @endif
                                    </td>
                                    @canany(['edit_supplier', 'delete_supplier'])
                                        <td class="text-center align-middle">
                                            @can('edit_supplier')
                                                <a href="{{ route("{$path}.edit", $result['id']) }}"
                                                   class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                        class="bx bx-pencil"></i></a>
                                            @endcan
                                            @can('delete_supplier')
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
