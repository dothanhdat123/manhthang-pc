@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Danh mục sản phẩm") }}</title>
    <meta content="{{ hwa_page_title("Danh mục sản phẩm") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Danh mục sản phẩm</li>
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
                                <h4 class="card-title">Danh mục sản phẩm</h4>
                            </div>
                            @can('add_category')
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
                                <th class="text-center align-middle">Hình ảnh</th>
                                <th class="text-center align-middle">Danh mục cha</th>
                                <th class="text-center align-middle">Trạng thái</th>
                                @canany(['edit_category', 'delete_category'])
                                    <th class="text-center align-middle">Hành động</th>
                                @endcanany
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                @include("{$path}.row", compact('result'))
                                @foreach($result->childCategories as $childCategory)
                                    @include("{$path}.row", ['result' => $childCategory, 'prefix' => '--'])
                                @endforeach
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
