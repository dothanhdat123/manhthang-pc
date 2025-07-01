@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title($result['subject'] ?? "Chi tiết liên hệ" ) }}</title>
    <meta
        content="{{ hwa_page_title($result['subject'] ?? "Chi tiết liên hệ" ) }}" name="description"/>
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
                                <a href="{{ route("{$path}.index") }}">Liên hệ</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Chi tiết liên hệ</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin khách hàng</h4>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item">Họ tên: {{ $result['name'] }}</li>
                            <li class="list-group-item">Email: {{ $result['email'] }}</li>
                            <li class="list-group-item">SĐT: {{ $result['phone'] }}</li>
                            <li class="list-group-item">Ngày
                                gửi: {{ Carbon\Carbon::parse($result['created_at'])->format('H:i:s d/m/Y') }}</li>
                            <li class="list-group-item">Trạng thái:
                                @if($result['active'] == 'read')
                                    <span class='badge badge-pill badge-soft-success font-size-11'
                                          style='line-height: unset!important;'>Đã đọc</span>
                                @else
                                    <span class='badge badge-pill badge-soft-danger font-size-11'
                                          style='line-height: unset!important;'>Chưa đọc</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route("{$path}.update", $result['id']) }}"
                              method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                <select name="active" id="active" class="form-control">
                                    <option
                                        value="read" {{ old('active', $result['active']) == 'read' ? 'selected' : '' }}>
                                        Đã đọc
                                    </option>
                                    <option
                                        value="unread" {{ old('active', $result['active']) == 'unread' ? 'selected' : '' }}>
                                        Chưa đọc
                                    </option>
                                </select>
                                @if($errors->has('active'))
                                    <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                @endif
                            </div>

                            <div class="mb-3 text-center">
                                <button type="submit"
                                        class="btn btn-success waves-effect waves-light me-3"><i
                                        class="bx bx-check-double font-size-16 align-middle me-2"></i> Lưu thông tin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4>
                                <span>{{ $result['subject'] }}</span>
                            </h4>
                            <hr>
                            <div class="mb-3">
                                <label><span
                                        class="fw-bold">{{ $result['name'] }}</span>{{ "<" . $result['email'] . ">" }}
                                </label>
                            </div>

                            <div class="mb-3">
                                <p>
                                    {{ $result['message'] }}
                                </p>
                            </div>
                            <div class="text-end">
                                    <span>
                                        <i class="bx bx-time-five align-middle mr-1"></i> {{ ((Carbon\Carbon::parse($result['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($result['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($result['created_at'])->format('H:i:s d-m-Y') }}
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title text-uppercase fw-bold">Trả lời</h4>
                            <hr>
                            @if(isset($result['contact_replies']) && count($result['contact_replies']) > 0)
                                @foreach($result['contact_replies'] as $item)
                                    <div class="card task-box">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="mb-3"><span
                                                        class="fw-bold">{{ hwa_app_name() }}</span>{{ "<" . hwa_app_contact() . ">" }}
                                                </label>
                                                <p class="text-dark">
                                                    {!! $item['message'] ?? "" !!}
                                                </p>
                                                <p class="chat-time text-muted mb-2 mt-2 text-end me-2"><i
                                                        class="bx bx-time-five align-middle me-1"></i>
                                                    {{ ((Carbon\Carbon::parse($item['created_at'])->diffInDays(Carbon\Carbon::now())) < 3) ? Carbon\Carbon::parse($item['created_at'])->locale('vi')->diffForHumans() : Carbon\Carbon::parse($item['created_at'])->format('H:i:s d-m-Y') }}
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>Không có thư trả lời.</p>
                            @endif

                            <div class="mb-3 text-center">
                                <button class="btn btn-info waves-effect waves-light"
                                        onclick="myFunction()">Trả lời
                                </button>
                            </div>

                            <div id="myDIV">
                                <form action="{{ route("{$path}.reply", ['id' => $result['id']]) }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="message" cols="30" rows="10"
                                                  class="form-control">{{ old('message') }}</textarea>
                                        @if($errors->has('message'))
                                            <p class="text-danger mt-2">{{ $errors->first('message') }}</p>
                                        @endif
                                    </div>
                                    <!-- end row -->
                                    <div class="mb-3 d-flex justify-content-center align-items-center">
                                        <button class="btn btn-success waves-effect waves-light" type="submit">Gửi
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>
@endsection

@section('admin_script')
    <script>
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
@endsection
