@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Tin tức") }}</title>
    <meta content="{{ hwa_page_title("Tin tức") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Tin tức</li>
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
                                <h4 class="card-title">Tin tức</h4>
                            </div>
                            @can('add_blog')
                                <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                    <i class="mdi mdi-plus me-2"></i> Thêm mới
                                </a>
                            @endcan
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">Tiêu đề</th>
                                <th class="text-center align-middle">Tác giả</th>
                                <th class="text-center align-middle">Chuyên mục</th>
                                <th class="text-center align-middle">Lượt xem</th>
                                <th class="text-center align-middle">Thời gian</th>
                                @canany(['edit_blog', 'delete_blog'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle">
                                        @if($result['active'] == 'draft')
                                            {{ $result['title'] . " -- (Bản nháp)" ?? "" }}
                                        @elseif($result['active'] == 'unpublished')
                                            {{ $result['title'] . " -- (Hủy đăng)" ?? "" }}
                                        @else
                                            {{ $result['title'] ?? "" }}
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">{{ $result['user']['username'] ?? "admin" }}</td>
                                    <td class="text-center align-middle">{{ $result['category']['name'] ?? "Khác" }}</td>
                                    <td class="text-center align-middle">{{ $result['views'] ?? 0 }}</td>
                                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($result['updated_at'])->format('H:i:s d/m/Y') }}</td>
                                    @canany(['edit_blog', 'delete_blog'])
                                        <td class="text-center align-middle">
                                            @can('edit_blog')
                                                <a href="{{ route("{$path}.edit", $result['id']) }}"
                                                   class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                        class="bx bx-pencil"></i></a>
                                            @endcan
                                            @can('delete_blog')
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
