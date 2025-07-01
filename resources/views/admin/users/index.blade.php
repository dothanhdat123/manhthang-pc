@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Quản trị viên") }}</title>
    <meta content="{{ hwa_page_title("Quản trị viên") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Quản trị viên</li>
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
                                <h4 class="card-title">Quản trị viên</h4>
                            </div>
                            @can('add_user')
                                <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                    <i class="mdi mdi-plus me-2"></i> Thêm mới
                                </a>
                            @endcan
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">Họ tên</th>
                                <th class="text-center align-middle">Tên tài khoản</th>
                                <th class="text-center align-middle">Email</th>
                                <th class="text-center align-middle">Ngày gia nhập</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                @canany(['edit_user', 'delete_user'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle">{{ $result['full_name'] ?? "" }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route("{$path}.edit", $result['id']) }}">
                                            {{ $result['username'] }}
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $result['email'] }}</td>
                                    <td class="text-center align-middle">{{ Carbon\Carbon::parse($result['created_at'])->format('H:i:s m/d/Y') }}</td>
                                    <td class="text-center align-middle">
                                        @if($result['active'] == 1)
                                            <span class='badge badge-pill badge-soft-success font-size-11'
                                                  style='line-height: unset!important;'>Hoạt động</span>
                                        @else
                                            <span class='badge badge-pill badge-soft-danger font-size-11'
                                                  style='line-height: unset!important;'>Bị khóa</span>
                                        @endif
                                    </td>
                                    @canany(['edit_user', 'delete_user'])
                                        <td class="text-center align-middle">
                                            @can('edit_user')
                                                <a href="{{ route("{$path}.edit", $result['id']) }}"
                                                   class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                        class="bx bx-pencil"></i></a>
                                            @endcan
                                            @can('delete_user')
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
