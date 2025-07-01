@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật sản phẩm") : "Thêm sản phẩm" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật sản phẩm") : "Thêm sản phẩm") }}"
          name="description"/>
@endsection
@section('admin_style')
    @include('admin.includes.select2.style')
    <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">

    @include('admin.includes.dropify.style')

    <link href="assets\libs\dropzone\dropzone.min.css" rel="stylesheet" type="text/css">
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
                                <a href="{{ route("{$path}.index") }}">Sản phẩm</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? ($result['full_name'] ?? "Cập nhật") : "Thêm mới" }}
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
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Tên sản phẩm: <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           name="name" id="name"
                                           placeholder="Nhập tên sản phẩm"
                                           value="{{ old('name', ($result['name'] ?? '')) }}">
                                    @if($errors->has('name'))
                                        <p class="text-danger mt-2">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="sku">Mã sản phẩm: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control text-uppercase {{ $errors->has('sku') ? 'is-invalid' : '' }}"
                                               name="sku" id="sku"
                                               placeholder="Nhập mã sản phẩm"
                                               value="{{ old('sku') ?? (isset($result['sku']) ? $result['sku'] : '') }}">
                                        @if($errors->has('sku'))
                                            <p class="text-danger mt-2">{{ $errors->first('sku') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="quantity">Số lượng: <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}"
                                               name="quantity" id="quantity"
                                               placeholder="Nhập số lượng sản phẩm"
                                               value="{{ old('quantity', $result['quantity'] ?? 0) }}">
                                        @if($errors->has('quantity'))
                                            <p class="text-danger mt-2">{{ $errors->first('quantity') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="unit_price">Giá nhập: <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}"
                                               name="unit_price" id="unit_price"
                                               placeholder="Nhập giá nhập"
                                               value="{{ old('unit_price', $result['unit_price'] ?? 0) }}">
                                        @if($errors->has('unit_price'))
                                            <p class="text-danger mt-2">{{ $errors->first('unit_price') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="price">Giá bán: <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                               name="price" id="price"
                                               placeholder="Nhập giá sản phẩm"
                                               value="{{ old('price', ($result['price'] ?? 0)) }}">
                                        @if($errors->has('price'))
                                            <p class="text-danger mt-2">{{ $errors->first('price') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Ngày sản xuất: <span class="text-danger">*</span></label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text"
                                                   class="form-control {{ $errors->has('start_at') ? 'is-invalid' : '' }}"
                                                   name="start_at" placeholder="dd/mm/yyyy"
                                                   data-date-format="dd/mm/yyyy"
                                                   value="{{ old('start_at') ?? (isset($result['start_at']) ? Carbon\Carbon::parse($result['start_at'])->format('d/m/Y') : '') }}"
                                                   data-date-container='#datepicker1' data-date-start-date="0d"
                                                   data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                        @if($errors->has('start_at'))
                                            <p class="text-danger mt-2">{{ $errors->first('start_at') }}</p>
                                        @endif
                                    </div>
                                    <!-- End start at -->
                                </div>

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Hạn sử dụng: <span class="text-danger">*</span></label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text"
                                                   class="form-control {{ $errors->has('expired_at') ? 'is-invalid' : '' }}"
                                                   name="expired_at" placeholder="dd/mm/yyyy"
                                                   data-date-format="dd/mm/yyyy"
                                                   value="{{ old('expired_at') ?? (isset($result['expired_at']) ? Carbon\Carbon::parse($result['expired_at'])->format('d/m/Y') : '') }}"
                                                   data-date-container='#datepicker1' data-date-start-date="0d"
                                                   data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                        @if($errors->has('expired_at'))
                                            <p class="text-danger mt-2">{{ $errors->first('expired_at') }}</p>
                                        @endif
                                    </div>
                                    <!-- End expired at -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3">
                                    <label for="description">Mô tả: <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="4"
                                              class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                              placeholder="Nhập mô tả sản phẩm">{{ old('description', ($result['description'] ?? '')) }}</textarea>
                                    @if($errors->has('description'))
                                        <p class="text-danger mt-2">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="description">Chi tiết sản phẩm: <span
                                            class="text-danger">*</span></label>
                                    <textarea name="content" id="content" cols="30"
                                              rows="10">{!! old('content', $result['content'] ?? '') !!}</textarea>
                                    @if($errors->has('content'))
                                        <p class="text-danger mt-2">{{ $errors->first('content') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="images">Hình ảnh: @if(!isset($result))<span class="text-danger">*</span>@endif</label>
                                    <div class="input-images-1" id="dropzone"></div>
                                    @if($errors->has('images'))
                                        <p class="text-danger mt-2">{{ $errors->first('images') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id">Danh mục: <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control select2">
                                    <option value="">--- Chọn danh mục sản phẩm ---</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category['id'] }}" {{ old('category_id', ($result['category_id'] ?? '')) == $category['id'] ? "selected" : "" }}>{{ $category['name'] }}</option>
                                        @foreach($category->childCategories as $item)
                                            <option value="{{ $item['id'] }}" {{ old('category_id', ($result['category_id'] ?? '')) == $item['id'] ? "selected" : "" }}>-- {{ $item['name'] }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                @if($errors->has('category_id'))
                                    <p class="text-danger mt-2">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>
                            <!-- End category -->

                            <div class="mb-3">
                                <label for="brand_id">Thương hiệu:</label>
                                <select name="brand_id" id="brand_id" class="form-control select2">
                                    <option value="">--- Chọn thương hiệu ---</option>
                                    @foreach($brands as $brand)
                                        <option
                                            value="{{ $brand['id'] }}" {{ old('brand_id', ($result['brand_id'] ?? '')) == $brand['id'] ? "selected" : "" }}>{{ $brand['name'] }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('brand_id'))
                                    <p class="text-danger mt-2">{{ $errors->first('brand_id') }}</p>
                                @endif
                            </div>
                            <!-- End brand -->

                            <div class="mb-3">
                                <label for="supplier_id">Nhà cung cấp: <span class="text-danger">*</span></label>
                                <select name="supplier_id" id="supplier_id" class="form-control select2">
                                    <option value="">--- Chọn nhà cung cấp ---</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier['id'] }}" {{ old('supplier_id', ($result['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : '') }}>{{ $supplier['name'] }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('supplier_id'))
                                    <p class="text-danger mt-2">{{ $errors->first('supplier_id') }}</p>
                                @endif
                            </div>
                            <!-- End supplier -->
                        </div>
                    </div>
                    <!-- End card category and brand -->

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="thumb">Ảnh đại diện: @if(!isset($result))<span class="text-danger">*</span>@endif</label>
                                <input type="file" class="dropify"
                                       name="thumb" {{ (isset($result['thumb']) && !empty($result['thumb'])) ? 'data-default-file=' . hwa_image_url("products/thumbs", $result['thumb']) : "" }}>
                                @if($errors->has('thumb'))
                                    <p class="text-danger mt-2">{{ $errors->first('thumb') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End card thumb -->

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="seo_title">Tiêu đề SEO: </label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 191 ký tự</span>
                                </div>
                                <input type="text"
                                       class="form-control {{ $errors->has('seo_title') ? 'is-invalid' : '' }}"
                                       name="seo_title" id="seo_title"
                                       placeholder="Nhập tiêu đề SEO"
                                       value="{{ old('seo_title') ?? (isset($result['seo_title']) ? $result['seo_title'] : '') }}">
                                @if($errors->has('seo_title'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_title') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="seo_description">Mô tả SEO:</label>
                                <div class="float-end">
                                    <span><i class="bx bxs-info-circle"></i> Tối đa 255 ký tự</span>
                                </div>
                                <textarea name="seo_description" id="seo_description" cols="30" rows="8"
                                          class="form-control {{ $errors->has('seo_description') ? 'is-invalid' : '' }}"
                                          placeholder="Nhập mô tả SEO">{{ old('seo_description') ?? (isset($result['seo_description']) ? $result['seo_description'] : '') }}</textarea>
                                @if($errors->has('seo_description'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_description') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="seo_keyword">Từ khóa SEO:</label>
                                <textarea name="seo_keyword" id="seo_keyword" cols="30" rows="6"
                                          class="form-control {{ $errors->has('seo_keyword') ? 'is-invalid' : '' }}"
                                          placeholder="Nhập từ khóa SEO (cách nhau bởi dấu phẩy)">{{ old('seo_keyword') ?? (isset($result['seo_keyword']) ? $result['seo_keyword'] : '') }}</textarea>
                                @if($errors->has('seo_keyword'))
                                    <p class="text-danger mt-2">{{ $errors->first('seo_keyword') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End seo -->

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                <select name="active" id="active" class="form-control">
                                    <option
                                        value="draft" {{ old('active', ($result['active'] ?? '')) == 'draft' ? 'selected' : '' }}>
                                        Bản nháp
                                    </option>
                                    <option
                                        value="published" {{ old('active', ($result['active'] ?? '')) == 'published' ? 'selected' : '' }}>
                                        Đang bán
                                    </option>
                                    <option
                                        value="unpublished" {{ old('active', ($result['active'] ?? '')) == 'unpublished' ? 'selected' : '' }}>
                                        Ngừng bán
                                    </option>
                                </select>
                                @if($errors->has('active'))
                                    <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                @endif
                            </div>
                            <!-- End active -->
                        </div>
                    </div>
                    <!-- End card active -->
                </div>
                <!-- end col -->
            </div>
            <div class="row">
                @include('admin.includes.form_button')
            </div>
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')

    <script src={{ url('ckeditor/ckeditor.js') }}></script>
    <script>
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser', ['type' => 'Products']) }}',
        });
    </script>
    @include('ckfinder::setup')

    <script src="assets\libs\dropzone\dropzone.min.js"></script>

    @include('admin.includes.select2.script')
    <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    @if(!isset($result))
        <script>
            $('.input-images-1').imageUploader({
                imagesInputName: 'images',
                preloadedInputName: 'preloaded',
                label: "Kéo và thả tệp vào đây hoặc nhấp vào"
            });
        </script>
    @else
        <script>
            let preloaded = [
                @php $no = 0 @endphp
                @foreach (explode(',', $result['image']) as $img)
                {
                    id: {{ $no++ }},
                    src: "{{ hwa_image_url('products', $img) }}"
                },
                @endforeach
            ];
            $('.input-images-1').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'olderImage',
                label: "Kéo và thả hoặc nhấp để thay thế"
            });
        </script>
    @endif
@endsection
