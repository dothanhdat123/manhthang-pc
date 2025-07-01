<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerType;
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

class BannerController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.banners';

    protected $imagePath = 'banners';

    /**
     * @var Banner
     */
    protected $banner;

    /**
     * @var BannerType
     */
    protected $banner_type;

    /**
     * BannerController constructor.
     * @param Banner $banner
     * @param BannerType $type
     */
    public function __construct(Banner $banner, BannerType $type)
    {
        $this->banner = $banner;
        $this->banner_type = $type;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_banner')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->banner->orderBy('id', 'desc')->with(['type'])
            ->select(['id', 'title', 'image', 'url', 'sort', 'click', 'target', 'banner_type', 'active'])
            ->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_banner')) {
            abort(404);
        }

        $path = $this->viewPath;
        return view("{$path}.form")->with([
            'path' => $path,
            'types' => $this->banner_type->orderBy('id', 'asc')->select(['code', 'name'])->get(),
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
        if (!hwa_check_permission('add_banner')) {
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
                hwa_notify_success("Thêm banner thành công.");
                return redirect()->route("{$path}.index");
            } else {
                hwa_notify_error("Lỗi thêm banner.");
                return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_banner') || !$result = $this->banner->find($id)) {
            abort(404);
        } else {
            return view("{$path}.form")->with([
                'path' => $path,
                'types' => $this->banner_type->orderBy('id', 'asc')->select(['code', 'name'])->get(),
                'result' => $result
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_banner') || !$result = $this->banner->find($id)) {
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
                    hwa_notify_success("Cập nhật banner thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật banner.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_banner') || !$result = $this->banner->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                // delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $result['image']))) {
                    File::delete($path);
                }
                hwa_notify_success("Xóa banner thành công.");
            } else {
                hwa_notify_error("Lỗi xóa banner.");
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
            'title' => ['nullable', 'max:191'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'url' => ['nullable', 'url'],
            'banner_type' => ['required', Rule::in(array_values($this->banner_type->select(['code'])->get()->pluck('code')->toArray()))],
            'target' => ['nullable', Rule::in(['_self', '_blank'])],
            'active' => ['required', Rule::in(['0', '1'])],
        ];

        $messages = [
            'name.required' => 'Tiêu đề là trường bắt buộc.',
            'name.max' => 'Tiêu đề có tối đa 191 ký tự.',
            'image.required' => 'Hình ảnh là trường bắt buộc.',
            'image.image' => 'Hình ảnh là không hợp lệ.',
            'image.mimes' => 'Định dạng ảnh không hợp lệ.',
            'url.url' => 'URL không hợp lệ.',
            'banner_type.required' => 'Kiểu banner là trường bắt buộc.',
            'banner_type.in' => 'Kiểu banner không hợp lệ.',
            'target.in' => 'Target không hợp lệ.',
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
     * @param null $banner
     * @return bool
     */
    protected function updateOrCreate($request, $banner = null)
    {
        if ($request->has('image')) {
            $file = $request->file('image');
            $image = strtolower("hwa_" . md5(Str::random(16) . time() . Str::random(18)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->save(hwa_image_path($this->imagePath, $image));
        } else {
            $image = $banner['image'] ?? '';
        }

        // Get data
        $data = [
            'title' => $request['title'],
            'sub_title' => $request['sub_title'],
            'image' => $image,
            'url' => $request['url'],
            'target' => $request['target'] ?? "_self",
            'sort' => $request['sort'],
            'banner_type' => $request['banner_type'],
            'active' => $request['active'],
        ];

        if (!$banner) {
            // Create new
            if (!$this->banner->create($data)) {
                if (file_exists($path = hwa_image_path($this->imagePath, $image))) {
                    File::delete($path);
                }
                return false;
            }
            return true;
        } else {
            // Update
            $old_image = $banner['image'];
            if ($banner->fill($data)->save()) {
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
