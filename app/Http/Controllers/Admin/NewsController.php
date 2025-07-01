<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
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

class NewsController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.news';

    protected $imagePath = 'news/thumb';

    /**
     * @var News
     */
    protected $news;

    /**
     * @var Category
     */
    protected $category;

    /**
     * ClientNewsController constructor.
     * @param News $news
     * @param Category $category
     */
    public function __construct(News $news, Category $category)
    {
        $this->category = $category;
        $this->news = $news;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_blog')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->news->orderBy('id', 'desc')->with(['category'])
            ->select(['id', 'title', 'views', 'category_id', 'user_id', 'active'])
            ->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_blog')) {
            abort(404);
        }

        $path = $this->viewPath;
        return view("{$path}.form")->with([
            'path' => $path,
            'categories' => $this->category->whereActive(1)->select(['id', 'name'])->get(),
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
        if (!hwa_check_permission('add_blog')) {
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
                hwa_notify_success("Thêm bài viết thành công.");
                return redirect()->route("{$path}.index");
            } else {
                hwa_notify_error("Lỗi thêm bài viết.");
                return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
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
        if (!hwa_check_permission('edit_blog') || !$result = $this->news->find($id)) {
            abort(404);
        } else {
            return view("{$path}.form")->with([
                'path' => $path,
                'categories' => $this->category->whereActive(1)->select(['id', 'name'])->get(),
                'result' => $result
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_blog') || !$result = $this->news->find($id)) {
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
                    hwa_notify_success("Cập nhật bài viết thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật bài viết.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_blog') || !$result = $this->news->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                // delete success
                if (file_exists($path = hwa_image_path($this->imagePath, $result['image']))) {
                    File::delete($path);
                }
                hwa_notify_success("Xóa bài viết thành công.");
            } else {
                hwa_notify_error("Lỗi xóa bài viết.");
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
            'category_id' => ['required', Rule::in(array_values($this->category->select(['id'])->get()->pluck('id')->toArray()))],
            'title' => ['required', 'max:191'],
            'description' => ['required', 'max:255'],
            'content' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'seo_title' => ['nullable', 'max:191'],
            'seo_description' => ['nullable', 'max:255'],
            'active' => ['required', Rule::in(['published', 'unpublished', 'draft'])],
        ];

        $messages = [
            'category_id.required' => 'Chuyên mục là trường bắt buộc.',
            'category_id.in' => 'Chuyên mục không hợp lệ.',
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.max' => 'Tiêu đề có tối đa 191 ký tự.',
            'description.required' => 'Mô tả là trường bắt buộc.',
            'description.max' => 'Mô tả có tối đa 255 ký tự.',
            'content.required' => 'Nội dung là trường bắt buộc.',
            'image.required' => 'Hình ảnh là trường bắt buộc.',
            'image.image' => 'Hình ảnh là không hợp lệ.',
            'image.mimes' => 'Định dạng ảnh không hợp lệ.',
            'seo_title.max' => 'Tiêu đề SEO có tối đa 191 ký tự.',
            'seo_description.max' => 'Mô tả SEO có tối đa 255 ký tự.',
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
     * @param null $news
     * @return bool
     */
    protected function updateOrCreate($request, $news = null)
    {
        if ($request->has('image')) {
            $file = $request->file('image');
            $image = strtolower("hwa_" . md5(Str::random(16) . time() . Str::random(18)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->resize(540, 360)->save(hwa_image_path($this->imagePath, $image));
        } else {
            $image = $news['image'] ?? '';
        }

        // Get data
        $data = [
            'category_id' => $request['category_id'],
            'user_id' => auth()->guard('admin')->id() ?? null,
            'title' => $request['title'],
            'description' => $request['description'],
            'content' => $request['content'],
            'image' => $image,
            'seo_title' => $request['seo_title'] ?? $request['title'],
            'seo_description' => $request['seo_description'] ?? $request['title'],
            'seo_keyword' => $request['seo_keyword'],
            'active' => $request['active'],
        ];

        if (!$news) {
            // Create new
            if (!$this->news->create($data)) {
                if (file_exists($path = hwa_image_path($this->imagePath, $image))) {
                    File::delete($path);
                }
                return false;
            }
            return true;
        } else {
            // Update
            $old_image = $news['image'];
            if ($news->fill($data)->save()) {
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
