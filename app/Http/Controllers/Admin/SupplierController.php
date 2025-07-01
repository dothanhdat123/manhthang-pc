<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
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

class SupplierController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.suppliers';

    protected $imagePath = 'suppliers';

    protected const ACTIVE_COLUMN = [
        'id',
        'name',
        'phone',
        'email',
        'website',
        'address',
        'description',
        'logo',
        'active',
    ];

    /**
     * @var Supplier
     */
    protected $supplier;

    /**
     * SupplierController constructor.
     * @param Supplier $supplier
     */
    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_supplier')) {
            abort(404);
        }

        $path = $this->viewPath;
        $suppliers = $this->supplier->select(self::ACTIVE_COLUMN)->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $suppliers ?? []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_supplier')) {
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
        if (!hwa_check_permission('add_supplier')) {
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
                hwa_notify_success("Thêm nhà cung cấp thành công.");
                return redirect()->route("{$path}.index");
            } else {
                hwa_notify_error("Lỗi thêm nhà cung cấp.");
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
        if (!hwa_check_permission('edit_supplier') || !$result = $this->supplier->select(self::ACTIVE_COLUMN)->find($id)) {
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
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_supplier') || !$result = $this->supplier->find($id)) {
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
                    hwa_notify_success("Cập nhật nhà cung cấp thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật nhà cung cấp.");
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
        if (!hwa_check_permission('delete_supplier') || !$result = $this->supplier->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                // delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $result['logo']))) {
                    File::delete($path);
                }
                hwa_notify_success("Xóa nhà cung cấp thành công.");
            } else {
                hwa_notify_error("Lỗi xóa nhà cung cấp.");
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
            'phone' => ['required', 'max:20'],
            'website' => ['nullable', 'url'],
            'email' => ['nullable', 'max:191', 'email'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            'active' => ['required', Rule::in(['0', '1'])],
        ];

        $messages = [
            'name.required' => 'Tên nhà cung cấp là trường bắt buộc.',
            'name.max' => 'Tên nhà cung cấp có tối đa 191 ký tự.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.max' => 'Số điện thoại có tối đa 20 ký tự.',
            'email.max' => 'Email có tối đa 191 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'website.url' => 'Website không hợp lệ.',
            'image.image' => 'Hình ảnh là không hợp lệ.',
            'image.mimes' => 'Định dạng ảnh không hợp lệ.',
            'active.required' => 'Trạng thái là trường bắt buộc.',
            'active.in' => 'Trạng thái không hợp lệ.',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Save data
     *
     * @param $request
     * @param null $supplier
     * @return bool
     */
    protected function updateOrCreate($request, $supplier = null)
    {
        if ($request->has('image')) {
            $file = $request->file('image');
            $image = strtolower("hwa_" . md5(Str::random(16) . time() . Str::random(18)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->resize(163, 85)->save(hwa_image_path($this->imagePath, $image));
        } else {
            $image = $supplier['logo'] ?? '';
        }

        // Get data
        $data = [
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => strtolower(trim($request['email'])),
            'website' => $request['website'],
            'address' => $request['address'],
            'description' => $request['description'],
            'logo' => $image,
            'active' => $request['active'],
        ];

        if (!$supplier) {
            // Create new
            if (!$this->supplier->create($data)) {
                if (file_exists($path = hwa_image_path($this->imagePath, $image))) {
                    File::delete($path);
                }
                return false;
            }
            return true;
        } else {
            // Update
            $old_image = $supplier['logo'];
            if ($supplier->fill($data)->save()) {
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
