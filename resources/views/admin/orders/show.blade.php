@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['subject'] ?? "Chi tiết đơn hàng" ) }}</title>
    <meta
        content="{{ hwa_page_title($result['subject'] ?? "Chi tiết đơn hàng" ) }}" name="description"/>
@endsection
@section('admin_style')

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
                                <a href="{{ route("{$path}.index") }}">Đơn hàng</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-16">Đơn hàng <span
                                    class="fw-bold">#{{ $result['id'] ?? "0" }}</span></h4>
                            <div class="mb-4">
                                <img src="assets/images/logo-light.png" alt="logo" height="20"/>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-nowrap">
                                <tbody>
                                @foreach($result['order_details'] as $detail)
                                    <tr>
                                        <td class="align-middle">
                                            <img
                                                src="{{ !empty($detail['product']['thumb']) ? hwa_image_url('products/thumbs', $detail['product']['thumb']) : '' }}"
                                                alt="" class="img-thumbnail" width="100">
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-uppercase fw-bold">{{ $detail['name'] }}</p>
                                            <p class="text-uppercase">SKU: {{ $detail['name'] }}</p>
                                        </td>
                                        <td class="text-end align-middle">{{ number_format($detail['price']) }} đ
                                            x {{ $detail['qty'] }}</td>
                                        <td class="text-end align-middle">{{ number_format($detail['total']) }} đ</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="border-0"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border-0">
                                        Hình thức: {!! hwa_order_payment_method($result['payment_method'] ?? "") !!}
                                    </td>
                                    <td class="border-0 text-end">Tạm tính</td>
                                    <td class="border-0 text-end">{{ number_format($result['subtotal']) ?? 0 }} đ</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border-0">
                                        Thanh toán: {!! hwa_order_payment_status($result['payment_status'] ?? "") !!}
                                    </td>
                                    <td class="border-0 text-end">Phí vận chuyển</td>
                                    <td class="text-end">{{ number_format($result['ship']) ?? 0 }} đ</td>
                                </tr>
                                <tr>
                                    <td class="border-0">
                                        Trạng thái: {!! hwa_order_active($result['active'] ?? "") !!}
                                    </td>
                                    <td colspan="2" class="border-0 text-end">
                                        <strong>Tổng tiền</strong></td>
                                    <td class="border-0 text-end"><h4
                                            class="m-0">{{ number_format($result['total']) ?? 0 }} đ</h4></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-center align-items-center">
                            <a class="btn btn-primary me-2"
                               href="{{ route("admin.export.orders.show", $result['id']) }}">
                                <i class="bx bx-export me-2"></i> Xuất Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Thông tin giao hàng</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">Khách hàng: <span
                                    class="text-uppercase fw-bold">{{ $result['customer_address']['name'] ?? "Customer name" }}</span>
                            </li>
                            <li class="list-group-item">
                                SĐT: <a
                                    href="{{ "tel:" . $result['customer_address']['phone'] ?? "javascript:void(0);" }}">{{ $result['customer_address']['phone'] ?? "phone" }}</a>
                            </li>
                            <li class="list-group-item">Địa
                                chỉ: {{ $result['customer_address']['address'] ?? "Customer address" }}</li>
                            <li class="list-group-item">Ngày
                                đặt: {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}</li>
                        </ul>
                    </div>
                </div>

                @if ($result['active'] != 'done' && $result['shipping_status'] != '2' && $result['payment_status'] != 1)
                    @can('edit_order')
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Cập nhật đơn hàng</h4>
                                <hr>
                                <form action="{{ route("{$path}.update", $result['id']) }}" method="post"
                                      class="form-horizontal">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="shipping_status">Trạng thái giao hàng</label>
                                        <select name="shipping_status" id="shipping_status" class="form-control">
                                            <option
                                                value="0" {{ old('shipping_status', ($result['shipping_status'] ?? '')) == '0' ? 'selected' : '' }}>
                                                Chưa giao
                                            </option>
                                            <option
                                                value="1" {{ old('shipping_status', ($result['shipping_status'] ?? '')) == '1' ? 'selected' : '' }}>
                                                Đang giao
                                            </option>
                                            <option
                                                value="2" {{ old('shipping_status', ($result['shipping_status'] ?? '')) == '2' ? 'selected' : '' }}>
                                                Đã giao
                                            </option>
                                        </select>
                                        @error('shipping_status')
                                        <p class="text-danger mt-2">{{ $errors->first('shipping_status') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_status">Trạng thái thanh toán</label>
                                        <select name="payment_status" id="payment_status" class="form-control">
                                            <option
                                                value="0" {{ old('payment_status', ($result['payment_status'] ?? '')) == '0' ? 'selected' : '' }}>
                                                Chưa thanh toán
                                            </option>
                                            <option
                                                value="1" {{ old('payment_status', ($result['payment_status'] ?? '')) == '1' ? 'selected' : '' }}>
                                                Đã thanh toán
                                            </option>
                                        </select>
                                        @error('payment_status')
                                        <p class="text-danger mt-2">{{ $errors->first('payment_status') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="active">Trạng thái</label>
                                        <select name="active" id="payment_status" class="form-control">
                                            @foreach(hwaCore()->getOrderStatus() as $status)
                                                <option
                                                    value="{{ $status['value'] }}" {{ old('active', ($result['active'] ?? '')) == $status['value'] ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('active')
                                        <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 mt-3 text-center justify-content-center">
                                        <button type="submit"
                                                class="btn btn-success waves-effect waves-light me-3"><i
                                                class="bx bx-check-double font-size-16 align-middle me-2"></i> Cập nhật
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan
                @endif

            </div>
            <!-- end col -->

        </div>
    </div>
@endsection

@section('admin_script')

@endsection
