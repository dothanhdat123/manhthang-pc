<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
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

class BrandController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.brands';

    protected $imagePath = 'brands';

    /**
     * @var Brand
     */
    protected $brand;

    /**
     * BrandController constructor.
     * @param Brand $brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_brand')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->brand->orderBy('id', 'desc')->select(['id', 'name', 'images', 'active'])->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_brand')) {
            abort(404);
        }

        $path = $this->viewPath;
        return view("{$path}.form")->with([
            'path' => $path
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
        if (!hwa_check_permission('add_brand')) {
            abort(404);
        }

        $path = $this->viewPath;
        // Validate rule
        $validator = $this->validateData($request);
        if ($validator->fails()) {
            // Invalid data
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if ($this->updateOrCreate($request)) {
                // Add success
                hwa_notify_success("Thêm thương hiệu thành công.");
                return redirect()->route("{$path}.index");
            } else {
                hwa_notify_error("Lỗi thêm thương hiệu.");
                return redirect()->back()->withInput();
            }
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
        if (!hwa_check_permission('edit_brand') || !$result = $this->brand->select(['id', 'name', 'description', 'images', 'active'])->find($id)) {
            abort(404);
        } else {
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_brand') || !$result = $this->brand->find($id)) {
            abort(404);
        } else {
            // Validate rule
            $validator = $this->validateData($request, $id);
            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if ($this->updateOrCreate($request, $result)) {
                    // Add success
                    hwa_notify_success("Cập nhật thương hiệu thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật thương hiệu.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_brand') || !$result = $this->brand->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                // delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $result['images']))) {
                    File::delete($path);
                }
                hwa_notify_success("Xóa thương hiệu thành công.");
            } else {
                hwa_notify_error("Lỗi xóa thương hiệu.");
            }
            return redirect()->back();
        }
    }

    /**
     * Validate data
     *
     * @param $request
     * @param null $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateData($request, $id = null)
    {
        $rules = [
            'name' => ['required', 'max:191'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'active' => ['required', Rule::in(['0', '1'])],
        ];

        $messages = [
            'name.required' => 'Tên thương hiệu là trường bắt buộc.',
            'name.max' => 'Tên thương hiệu có tối đa 191 ký tự.',
            'image.required' => 'Hình ảnh là trường bắt buộc.',
            'image.image' => 'Hình ảnh là không hợp lệ.',
            'image.mimes' => 'Định dạng ảnh không hợp lệ.',
            'active.required' => 'Trạng thái là trường bắt buộc.',
            'active.in' => 'Trạng thái không hợp lệ.',
        ];

        if (!empty($id)) {
            unset($rules['image']);

            $rules = array_merge($rules, [
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ]);

            unset($messages['image.required']);
        }

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Save data
     *
     * @param $request
     * @param null $brand
     * @return bool
     */
    protected function updateOrCreate($request, $brand = null)
    {
        if ($request->has('image')) {
            $file = $request->file('image');
            $image = strtolower("hwa_" . md5(Str::random(16) . time() . Str::random(18)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->save(hwa_image_path($this->imagePath, $image));
        } else {
            $image = $brand['images'] ?? '';
        }

        // Get data
        $data = [
            'name' => $request['name'],
            'description' => $request['description'],
            'images' => $image,
            'active' => $request['active'],
        ];

        if (!$brand) {
            // Create new
            if (!$this->brand->create($data)) {
                if (file_exists($path = hwa_image_path($this->imagePath, $image))) {
                    File::delete($path);
                }
                return false;
            }
            return true;
        } else {
            // Update
            $old_image = $brand['images'];
            if ($brand->fill($data)->save()) {
                if ($request->has('image')) {
                    if (file_exists($path = hwa_image_path($this->imagePath, $old_image))) {
                        File::delete($path);
                    }
                }
                return true;
            } else {
                if ($request->hasFile('image')) {
                    if (file_exists($new_path = hwa_image_path($this->imagePath, $image))) {
                        File::delete($new_path);
                    }
                }
                return false;
            }
        }
    }
}
