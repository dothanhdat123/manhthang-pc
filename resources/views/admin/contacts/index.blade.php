@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Liên hệ") }}</title>
    <meta content="{{ hwa_page_title("Liên hệ") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.database.style')
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
                            <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
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
                                <h4 class="card-title">Liên hệ</h4>
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
                                <th class="text-center align-middle">Họ tên</th>
                                <th class="text-center align-middle">Email</th>
                                <th class="text-center align-middle">Số điện thoại</th>
                                <th class="text-center align-middle">Ngày gửi</th>
                                @canany(['edit_contact', 'delete_contact'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    @if($result['active'] == "read")
                                        <td class="text-center align-middle">{{ $result['id'] ?? 0 }}</td>
                                        <td class="align-middle">{{ $result['name'] ?? "" }}</td>
                                        <td class="align-middle">{{ $result['email'] ?? "" }}</td>
                                        <td class="align-middle">{{ $result['phone'] ?? "" }}</td>
                                        <td class="text-center align-middle">{{ Carbon\Carbon::parse($result['created_at'])->format("H:i:s d/m/Y") }}</td>
                                    @else
                                        <td class="text-center align-middle"><b>{{ $result['id'] ?? 0 }}</b></td>
                                        <td class="align-middle"><b>{{ $result['name'] ?? "" }}</b></td>
                                        <td class="align-middle"><b>{{ $result['email'] ?? "" }}</b></td>
                                        <td class="align-middle"><b>{{ $result['phone'] ?? "" }}</b></td>
                                        <td class="text-center align-middle">
                                            <b>{{ Carbon\Carbon::parse($result['created_at'])->format("H:i:s d/m/Y") }}</b>
                                        </td>
                                    @endif
                                    @canany(['edit_contact', 'delete_contact'])
                                        <td class="text-center align-middle">
                                            @can('edit_contact')
                                                <a href="{{ route("{$path}.show", $result['id']) }}"
                                                   class="btn btn-success me-3"><i
                                                        class="mdi mdi-eye-outline"></i></a>
                                            @endcan
                                            @can('delete_contact')
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
