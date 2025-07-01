<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewsletterExport;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NewsletterController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.newsletter';

    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * NewsletterController constructor.
     * @param Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_newsletter')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->newsletter->orderBy('id', 'desc')->select(['id', 'email', 'active'])->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse|BinaryFileResponse
     */
    public function create()
    {
        $newsletters = $this->newsletter->orderBy('id', 'asc')->select(['id', 'email', 'created_at'])->get();
        $file_name = strtolower("newsletters_" . date('d_m_y') . '_' . time() . '.xlsx');
        if (count($newsletters) > 0) {
            return Excel::download(new NewsletterExport($newsletters), $file_name);
        } else {
            hwa_notify_error("Không có newsletter.");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
        if (!hwa_check_permission('edit_newsletter') || !$result = $this->newsletter->select(['id', 'email', 'active'])->find($id)) {
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
        if (!hwa_check_permission('edit_newsletter') || !$result = $this->newsletter->select(['id', 'email', 'active'])->find($id)) {
            abort(404);
        } else {
            // Validate rule and message
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'max:191', 'email', 'unique:newsletters,email,' . $id],
                'active' => ['required', Rule::in(['0', '1'])],
            ], [
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email không đúng định dạng.',
                'email.exists' => 'Email đã tồn tại.',
                'active.required' => 'Trạng thái là trường bắt buộc.',
                'active.in' => 'Trạng thái không hợp lệ.',
            ]);

            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get data
                $data = [
                    'email' => strtolower(trim($request['email'])),
                    'active' => $request['active'],
                ];

                if ($result->fill($data)->save()) {
                    // Update success
                    hwa_notify_success("Cập nhật thành công newsletter.");
                    return redirect()->route("{$path}.index");
                } else {
                    // Error update
                    hwa_notify_error("Lỗi cập nhật newsletter.");
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
        if (!hwa_check_permission('delete_newsletter') || !$result = $this->newsletter->select(['id', 'email', 'active'])->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                hwa_notify_success("Xóa newsletter thành công.");
            } else {
                hwa_notify_error("Lỗi xóa newsletter.");
            }
            return redirect()->back();
        }
    }
}
