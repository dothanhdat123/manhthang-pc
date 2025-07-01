@extends('admin.layouts.master')


@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật chức vụ") : "Thêm cửa hàng" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật cửa hàng") : "Thêm cửa hàng") }}"
          name="description"/>
@endsection

@section('admin_style')

@section('admin_content')
<div class="container">
    <h1>Sửa thông tin cửa hàng</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.stores.update', $store) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên cửa hàng</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $store->name) }}" required>
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $store->address) }}">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
        <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary mt-3">Hủy</a>
    </form>
</div>
@endsection
