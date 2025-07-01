<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    protected $viewPath = "admin.pages";

    /**
     * @var Page
     */
    protected $page;

    /**
     * PublicPageController constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_page')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->page->orderBy('id', 'asc')->select(['id', 'name', 'slug'])->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        if (!hwa_check_permission('update_page')) {
            abort(404);
        }

        $path = $this->viewPath;
        if (!$result = $this->page->find($id)) {
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
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('update_page') || !$result = $this->page->find($id)) {
            abort(404);
        } else {
            $rules = [
                'name' => ['required', 'max:191'],
                'content' => ['required'],
                'seo_title' => ['nullable', 'max:191'],
                'seo_description' => ['nullable', 'max:255'],
                'active' => ['required', Rule::in(['0', '1'])],
            ];

            $messages = [
                'name.required' => 'Tên là trường bắt buộc.',
                'name.max' => 'Tên có tối đa 191 ký tự.',
                'content.required' => 'Nội dung là trường bắt buộc.',
                'seo_title.max' => 'Tiêu đề SEO có tối đa 191 ký tự.',
                'seo_description.max' => 'Mô tả SEO có tối đa 255 ký tự.',
                'active.required' => 'Trạng thái là trường bắt buộc.',
                'active.in' => 'Trạng thái không hợp lệ.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $result->fill([
                    'name' => $request['name'],
                    'content' => $request['content'],
                    'seo_title' => $request['seo_title'] ?? $request['title'],
                    'seo_description' => $request['seo_description'] ?? $request['title'],
                    'seo_keyword' => $request['seo_keyword'],
                    'active' => $request['active'],
                ])->save();
                hwa_notify_success("Cập nhật trang thành công.");
                return redirect()->route("{$path}.index");
            }
        }
    }


}
