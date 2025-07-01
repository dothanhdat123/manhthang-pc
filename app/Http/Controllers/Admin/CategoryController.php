<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.categories';

    protected $imagePath = 'categories';

    /**
     * @var Category
     */
    protected $category;

    /**
     * CategoryController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_category')) {
            abort(404);
        }

        $path = $this->viewPath;
        $storeId = auth()->user()->store_id ?? null; // Assuming user has store_id attribute
        $query = $this->category->whereNull('parent_id')
            ->with(['childCategories'])
            ->select(['id', 'name', 'images', 'active', 'parent_id']);
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        $categories = $query->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $categories ?? []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_category')) {
            abort(404);
        }

        $path = $this->viewPath;
        $categories = $this->category->whereNull('parent_id')
            ->with(['childCategories'])
            ->select(['id', 'name', 'parent_id'])
            ->get();
        return view("{$path}.form")->with([
            'path' => $path,
            'categories' => $categories ?? []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
{
    if (!hwa_check_permission('add_category')) {
        abort(404);
    }

    $path = $this->viewPath;

    // Lấy store_id của user hiện tại (nếu có)
    $storeId = auth()->user()->store_id ?? null;

    // Validate dữ liệu đầu vào
    $validator = $this->validateData($request);
    if ($validator->fails()) {
        hwa_notify_error($validator->getMessageBag()->first());
        return redirect()->back()->withInput()->withErrors($validator);
    }

    // Gán store_id vào request để dùng trong updateOrCreate
    $request->merge([
        'store_id' => $storeId
    ]);

    // Tạo hoặc cập nhật danh mục
    if ($this->updateOrCreate($request)) {
        hwa_notify_success("Thêm danh mục thành công.");
        return redirect()->route("{$path}.index");
    } else {
        hwa_notify_error("Lỗi thêm danh mục.");
        return redirect()->back()->withInput();
    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_category') || !$result = $this->category->select(['id', 'name', 'description', 'images', 'active', 'parent_id'])->find($id)) {
            abort(404);
        } else {
            $categories = $this->category->whereNull('parent_id')
                ->whereNotIn('id', [$id])
                ->with(['childCategories'])
                ->select(['id', 'name', 'parent_id'])
                ->get();
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result ?? [],
                'categories' => $categories ?? []
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_category') || !$result = $this->category->select(['id', 'name', 'description', 'images', 'active', 'parent_id'])->find($id)) {
            abort(404);
        } else {
            // Validate rule
            $validator = $this->validateData($request);
            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if ($this->updateOrCreate($request, $result)) {
                    // Add success
                    hwa_notify_success("Cập nhật danh mục thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật danh mục.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_category') || !$result = $this->category->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                // delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $result['images']))) {
                    File::delete($path);
                }
                hwa_notify_success("Xóa danh mục thành công.");
            } else {
                hwa_notify_error("Lỗi xóa danh mục.");
            }
            return redirect()->back();
        }
    }

    /**
     * Validate data
     *
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateData($request)
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'max:191'],
            'parent_id' => ['nullable', Rule::in(array_values($this->category->whereNull('parent_id')->get()->pluck('id')->toArray()))],
            'active' => ['required', Rule::in(['0', '1'])]
        ], [
            'name.required' => 'Tên danh mục là trường bắt buộc.',
            'name.max' => 'Tên danh mục có tối đa 191 ký tự.',
            'parent_id.in' => 'Danh mục cha không hợp lệ.',
            'active.required' => 'Trạng thái là trường bắt buộc.',
            'active.in' => 'Trạng thái không hợp lệ.',
        ]);
    }

    /**
     * Save data
     *
     * @param $request
     * @param null $category
     * @return bool
     */
   protected function updateOrCreate($request, $category = null)
{
    // Xử lý hình ảnh
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $image = strtolower("hwa_" . md5(Str::random(16) . time() . Str::random(18)) . '.' . $file->getClientOriginalExtension());
        Image::make($file->getRealPath())->save(hwa_image_path($this->imagePath, $image));
    } else {
        $image = $category['images'] ?? '';
    }

    // Chuẩn bị dữ liệu để tạo hoặc cập nhật
    $data = [
        'name'        => $request['name'],
        'description' => $request['description'],
        'parent_id'   => $request['parent_id'],
        'images'      => $image,
        'active'      => $request['active'],
        'store_id'    => $request['store_id'] ?? auth()->user()->store_id, // đảm bảo có store_id
    ];

    // Nếu chưa có $category → tạo mới
    if (!$category) {
        try {
            $this->category->create($data);
            return true;
        } catch (\Exception $e) {
            // Xoá ảnh nếu tạo thất bại
            if (!empty($image) && file_exists($path = hwa_image_path($this->imagePath, $image))) {
                File::delete($path);
            }
            return false;
        }
    }

    // Nếu là cập nhật
    $old_image = $category['images'] ?? null;

    try {
        $category->fill($data)->save();

        // Nếu có ảnh mới thì xoá ảnh cũ
        if ($request->hasFile('image') && $old_image && file_exists($old_path = hwa_image_path($this->imagePath, $old_image))) {
            File::delete($old_path);
        }

        return true;
    } catch (\Exception $e) {
        // Nếu có lỗi khi cập nhật, xoá ảnh mới nếu có
        if (!empty($image) && $request->hasFile('image') && file_exists($new_path = hwa_image_path($this->imagePath, $image))) {
            File::delete($new_path);
        }
        return false;
    }
}

}
