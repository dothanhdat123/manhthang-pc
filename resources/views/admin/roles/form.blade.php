@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật chức vụ") : "Thêm chức vụ" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['title'] ?? "Cập nhật chức vụ") : "Thêm chức vụ") }}"
          name="description"/>
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
                                <a href="{{ route("{$path}.index") }}">Chức vụ</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? ($result['title'] ?? "Cập nhật") : "Thêm mới" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form action="{{ isset($result) ? route("{$path}.update", $result['id']) : route("{$path}.store") }}"
              method="post" class="form-horizontal">
            @csrf
            @if(isset($result))
                @method('PUT')
            @endif
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-center card-title fw-bold text-uppercase mb-3">Thông tin chức vụ</h6>
                            <div class="mb-3">
                                <label for="title" class="fw-bold">Tên chức vụ: <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                       value="{{ old('title', $result['title'] ?? "") }}"
                                       placeholder="Nhập tên chức vụ">
                                @error('title')
                                <p class="text-danger mt-2">{{ $errors->first('title') }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-md-3"><b>Quyền:</b></label>
                                    <div class="col-md-9">
                                        <div class="form-check form-check-primary mb-3">
                                            <input class="form-check-input" type="checkbox" {{ (isset($result['permissions']) && isset($maxPermission) && (count($result['permissions'] ?? 0) === $maxPermission)) ? 'checked' : '' }} id="allPermission">
                                            <label class="form-check-label" for="allPermission">
                                                Tất cả các quyền
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    @foreach(hwaCore()->configPermissionInput() as $item)
                                        @include("admin.roles.permission_input", ['item' => $item, 'result' => $result ?? []])
                                    @endforeach
                                </div>

                            </div>
                            @include('admin.includes.form_button')
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </form>
    </div>
@endsection

@section('admin_script')
    <script>
        function checkGroup(group, checkSingle) {
            $(group).change(function () {
                $(checkSingle).not(this).prop('checked', this.checked);
            });

            $(checkSingle).change(function () {
                if ($(this).is(":checked")) {
                    let isAllChecked = 0;

                    $(checkSingle).each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                    });

                    if (isAllChecked === 0) {
                        $(group).prop("checked", true);
                    }
                } else {
                    $(group).prop("checked", false);
                }
            });
        }

        checkGroup("#allPermission", '.checkPermission');
        checkGroup("#banner", '.checkBanner');
        checkGroup("#review", '.checkReview');
        checkGroup("#blog", '.checkBlog');
        checkGroup("#supplier", '.checkSupplier');
        checkGroup("#brand", '.checkBrand');
        checkGroup("#category", '.checkCategory');
        checkGroup("#product", '.checkProduct');
        checkGroup("#order", '.checkOrder');
        checkGroup("#customer", '.checkCustomer');
        checkGroup("#contact", '.checkContact');
        checkGroup("#newsletter", '.checkNewsletter');
        checkGroup("#user", '.checkUser');
        checkGroup("#role", '.checkRole');
        checkGroup("#faq", '.checkFaq');
        checkGroup("#page", '.checkPage');
        checkGroup("#system", '.checkSystem');
        checkGroup("#allPermission", '.checkPermission');
    </script>
@endsection
