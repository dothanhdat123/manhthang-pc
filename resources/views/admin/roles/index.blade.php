@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Chức vụ") }}</title>
    <meta content="{{ hwa_page_title("Chức vụ") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Chức vụ</li>
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
                                <h4 class="card-title">Chức vụ</h4>
                            </div>
                            @can('add_role')
                                <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                    <i class="mdi mdi-plus me-2"></i> Thêm mới
                                </a>
                            @endcan
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Tên</th>
                                @canany(['edit_role', 'delete_role'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="text-center align-middle">{{ $result['id'] ?? 0 }}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route("{$path}.edit", $result['id']) }}">
                                            {{ $result['title'] ?? "" }}
                                        </a>
                                    </td>
                                    @canany(['edit_role', 'delete_role'])
                                        <td class="text-center align-middle">
                                            @can('edit_role')
                                                <a href="{{ route("{$path}.edit", $result['id']) }}"
                                                   class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                        class="bx bx-pencil"></i></a>
                                            @endcan
                                            @can('delete_role')
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
