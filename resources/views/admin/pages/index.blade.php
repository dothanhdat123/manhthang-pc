@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Trang tĩnh") }}</title>
    <meta content="{{ hwa_page_title("Trang tĩnh") }}" name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Trang tĩnh</li>
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
                                <h4 class="card-title">Trang tĩnh</h4>
                            </div>
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Tên</th>
                                <th class="text-center align-middle">Đường dẫn</th>
                                @can('update_page')
                                    <th class="text-center align-middle">Hành động</th>
                                @endcan
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="text-center align-middle">{{ $result['id'] ?? 0 }}</td>
                                    <td class="align-middle">{{ $result['name'] ?? "" }}</td>
                                    <td class="align-middle">
                                        <a href="{{ url("/{$result['slug']}") }}" target="_blank">
                                            {{ url("/{$result['slug']}") ?? "" }}
                                        </a>
                                    </td>
                                    @can('update_page')
                                        <td class="text-center align-middle">
                                            <a href="{{ route("{$path}.edit", $result['id']) }}"
                                               class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                    class="bx bx-pencil"></i></a>
                                        </td>
                                    @endcan
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
