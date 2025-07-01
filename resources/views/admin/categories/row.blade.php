<tr>
    <td class="text-center align-middle">{{ $result['id'] ?? 0 }}</td>
    <td class="align-middle">{{ $prefix ?? '' }}{{ $result['name'] ?? "" }}</td>
    <td class="text-center align-middle">
        <img src="{{ !empty($result['images']) ? hwa_image_url('categories', $result['images']) : '' }}" alt=""
             class="img-thumbnail" width="100">
    </td>
    <td class="align-middle">{{ $result['parentCategory']['name'] ?? "=== Root ===" }}</td>
    <td class="text-center align-middle">
        @if($result['active'] == 1)
            <span class='badge badge-pill badge-soft-success font-size-11'
                  style='line-height: unset!important;'>Bật</span>
        @else
            <span class='badge badge-pill badge-soft-danger font-size-11'
                  style='line-height: unset!important;'>Tắt</span>
        @endif
    </td>
    @canany(['edit_category', 'delete_category'])
        <td class="text-center align-middle">
            @can('edit_category')
                <a href="{{ route("{$path}.edit", $result['id']) }}"
                   class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                        class="bx bx-pencil"></i></a>
            @endcan
            @can('delete_category')
                <a href="javascript:void(0)" data-id="{{ $result['id'] }}"
                   data-message="Bạn có thực sự muốn xóa bản ghi này không?"
                   data-url="{{ route("{$path}.destroy", $result['id']) }}"
                   class="btn btn-danger delete" data-bs-toggle="modal"
                   data-bs-target=".deleteModal"><i class="bx bx-trash"></i></a>
            @endcan
        </td>
    @endcanany
</tr>
