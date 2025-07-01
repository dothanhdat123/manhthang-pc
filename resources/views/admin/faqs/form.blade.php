@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['questions'] ?? "Cập nhật faq") : "Thêm faq" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['questions'] ?? "Cập nhật faq") : "Thêm faq") }}"
          name="description"/>
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
                                <a href="{{ route("{$path}.index") }}">FAQs</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? "Cập nhật" : "Thêm mới" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal"
              action="{{ isset($result) ? route("{$path}.update", $result['id']) : route("{$path}.store") }}"
              method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($result))
                @method('PUT')
            @endif
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="questions">Câu hỏi: <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('questions') ? 'is-invalid' : '' }}"
                                           name="questions" id="questions" placeholder="Nhập câu hỏi"
                                           value="{{ old('questions', $result['questions'] ?? "") }}">
                                    @if($errors->has('questions'))
                                        <p class="text-danger mt-2">{{ $errors->first('questions') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="answers">Câu trả lời:</label>
                                    <textarea name="answers" id="answers" cols="30" rows="10"
                                              class="form-control {{ $errors->has('answers') ? 'is-invalid' : '' }}"
                                              placeholder="Nhập câu trả lời">{{ old('answers', $result['answers'] ?? "") }}</textarea>
                                    @if($errors->has('answers'))
                                        <p class="text-danger mt-2">{{ $errors->first('answers') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="type">Loại: <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1" {{ old('type', $result['type'] ?? '') == '1' ? 'selected' : '' }}>Phổ biến</option>
                                        <option value="0" {{ old('type', $result['type'] ?? '') == '0' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @if($errors->has('type'))
                                        <p class="text-danger mt-2">{{ $errors->first('type') }}</p>
                                    @endif
                                </div>
                                <!-- End name -->

                                <div class="mb-3">
                                    <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                    <select name="active" id="active" class="form-control">
                                        <option value="1" {{ old('active', $result['active'] ?? '') == '1' ? 'selected' : '' }}>Bật</option>
                                        <option value="0" {{ old('active', $result['active'] ?? '') == '0' ? 'selected' : '' }}>Tắt</option>
                                    </select>
                                    @if($errors->has('active'))
                                        <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                    @endif
                                </div>
                                <!-- End name -->

                                @include('admin.includes.form_button')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </form>
    </div>
@endsection
