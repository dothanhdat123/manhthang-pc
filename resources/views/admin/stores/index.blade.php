@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật cửa hàng") : "Thêm cửa hàng" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật cửa hàng") : "Thêm cửa hàng") }}"
          name="description"/>
@endsection

@section('admin_style')

@section('admin_content')
<div class="container">
    <h1>Danh sách cửa hàng </h1>
    <a href="{{ route('admin.stores.create') }}" class="btn btn-primary mb-3">Thêm cửa hàng</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores as $store)
            <tr>
                <td>{{ $store->id }}</td>
                <td>{{ $store->name }}</td>
                <td>{{ $store->address }}</td>
                <td>
                    <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this store?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
