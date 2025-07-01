<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.faqs';

    /**
     * @var Faq
     */
    protected $faq;

    /**
     * FaqController constructor.
     * @param Faq $faq
     */
    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_faq')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->faq->orderBy('id', 'asc')->select(['id', 'questions', 'active'])->get();
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
        if (!hwa_check_permission('add_faq')) {
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
        if (!hwa_check_permission('add_faq')) {
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
                hwa_notify_success("Thêm faq thành công.");
                return redirect()->route("{$path}.index");
            } else {
                hwa_notify_error("Lỗi thêm faq.");
                return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return void
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
        if (!hwa_check_permission('edit_faq') || !$result = $this->faq->find($id)) {
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
        if (!hwa_check_permission('edit_faq') || !$result = $this->faq->find($id)) {
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
                    hwa_notify_success("Cập nhật faq thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật faq.");
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
        if (!hwa_check_permission('delete_faq') || !$result = $this->faq->select(['id'])->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                hwa_notify_success("Xóa faq thành công.");
            } else {
                hwa_notify_error("Lỗi xóa faq.");
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
            'questions' => ['required', 'max:191'],
            'answers' => ['required', 'max:500'],
            'type' => ['required', Rule::in(['0', '1'])],
            'active' => ['required', Rule::in(['0', '1'])],
        ];

        $messages = [
            'questions.required' => 'Câu hỏi là trường bắt buộc.',
            'questions.max' => 'Câu hỏi có tối đa 191 ký tự.',
            'answers.required' => 'Câu trả lời là trường bắt buộc.',
            'answers.max' => 'Câu trả lời có tối đa 500 ký tự.',
            'type.required' => 'Loại faq là trường bắt buộc.',
            'type.in' => 'Loại faq không hợp lệ.',
            'active.required' => 'Trạng thái là trường bắt buộc.',
            'active.in' => 'Trạng thái không hợp lệ.',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Save data
     *
     * @param $request
     * @param null $faq
     * @return bool
     */
    protected function updateOrCreate($request, $faq = null)
    {
        // Get data
        $data = [
            'questions' => $request['questions'],
            'answers' => $request['answers'],
            'type' => $request['type'],
            'active' => $request['active'],
        ];

        if (!$faq) {
            // Create new
            if (!$this->faq->create($data)) {
                return false;
            }
        } else {
            if (!$faq->fill($data)->save()) {
                return false;
            }
        }
        return true;
    }
}
