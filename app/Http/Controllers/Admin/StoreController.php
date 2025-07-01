<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Hiển thị danh sách store
     */
    public function index()
    {
        $user = auth()->user();

        // Nếu là admin (role = 1) => xem tất cả
        if ($user->role == 1) {
            $stores = Store::all();
        } else {
            // Nếu không phải admin => chỉ xem store của chính họ
            $stores = Store::where('id', $user->store_id)->get();
        }

        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Form tạo mới store
     */
    public function create()
    {
        // Chỉ admin được phép tạo store
        if (auth()->user()->role != 1) {
            abort(403, 'Bạn không có quyền tạo cửa hàng.');
        }

        return view('admin.stores.create');
    }

    /**
     * Lưu store mới
     */
    public function store(Request $request)
    {
        // Chỉ admin được phép tạo store
        if (auth()->user()->role != 1) {
            abort(403, 'Bạn không có quyền tạo cửa hàng.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Store::create($request->only('name', 'address'));

        return redirect()->route('admin.stores.index')->with('success', 'Tạo cửa hàng thành công.');
    }

    /**
     * Form chỉnh sửa store
     */
    public function edit(Store $store)
    {
        $user = auth()->user();

        if ($user->role != 1 && $user->store_id != $store->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa cửa hàng này.');
        }

        return view('admin.stores.edit', compact('store'));
    }

    /**
     * Cập nhật store
     */
    public function update(Request $request, Store $store)
    {
        $user = auth()->user();

        if ($user->role != 1 && $user->store_id != $store->id) {
            abort(403, 'Bạn không có quyền cập nhật cửa hàng này.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $store->update($request->only('name', 'address'));

        return redirect()->route('admin.stores.index')->with('success', 'Cập nhật cửa hàng thành công.');
    }

    /**
     * Xoá store
     */
    public function destroy(Store $store)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Bạn không có quyền xóa cửa hàng này.');
        }

        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Xoá cửa hàng thành công.');
    }
}
