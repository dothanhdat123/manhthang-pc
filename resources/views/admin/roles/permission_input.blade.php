<div class="accordion-item">
    <h2 class="accordion-header" id="flush-heading-{{ $item['key'] }}">
        <button class="accordion-button fw-medium collapsed" type="button"
                data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $item['key'] }}"
                aria-expanded="false" aria-controls="flush-collapse-{{ $item['key'] }}">
            {{ $item['label'] ?? "" }}
        </button>
    </h2>
    <div id="flush-collapse-{{ $item['key'] }}" class="accordion-collapse collapse"
         aria-labelledby="flush-heading-{{ $item['key'] }}" data-bs-parent="#accordionFlushExample">
        <div class="card-body ms-2">
            <div class="col-sm-12">
                <div class="row">
                    <div class="form-check col-sm-2">
                        <input class="form-check-input checkPermission" type="checkbox"
                               {{ hwa_check_all_permission_line($result, $item) ? "checked" : "" }}
                               id="{{ $item['key'] }}">
                        <label class="form-check-label" for="{{ $item['key'] }}">
                            Tất cả
                        </label>
                    </div>

                    @foreach($item['permissions'] as $permission)
                        <div class="form-check col-sm-2">
                            <input class="form-check-input check{{ ucfirst($item['key']) }} checkPermission"
                                   {{ in_array("{$permission['key']}_{$item['key']}", old('permissions', hwa_result_permissions($result))) ? ' checked' : '' }}
                                   type="checkbox" id="{{ "{$permission['key']}_{$item['key']}" }}" name="permissions[]"
                                   value="{{ "{$permission['key']}_{$item['key']}" }}">
                            <label class="form-check-label"
                                   for="{{ "{$permission['key']}_{$item['key']}" }}">{{ $permission['label'] ?? "" }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
