<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ContactExport;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Contact;
use App\Models\ReplyContact;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactController extends Controller
{
    /**
     * @var string Paths
     */
    protected $viewPath = 'admin.contacts';

    /**
     * @var Contact
     */
    protected $contact;

    /**
     * ContactController constructor.
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_contact')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->contact->orderBy('id', 'desc')->select(['id', 'name', 'phone', 'email', 'active', 'created_at'])->get();
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
        $contacts = $this->contact->orderBy('id', 'asc')
            ->select(['id', 'name', 'phone', 'email', 'subject', 'message', 'active', 'created_at'])
            ->get();
        $file_name = strtolower("lien_he_" . date('d_m_y') . '_' . time() . '.xlsx');
        if (count($contacts) > 0) {
            return Excel::download(new ContactExport($contacts), $file_name);
        } else {
            hwa_notify_error("Không có liên hệ.");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $path = $this->viewPath;
        if (!hwa_check_permission('edit_contact') || !$result = $this->contact->with(['contact_replies'])->select(['id', 'name', 'phone', 'email', 'subject', 'message', 'active', 'created_at'])->find($id)) {
            abort(404);
        } else {
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        abort(404);
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
        if (!hwa_check_permission('edit_contact') || !$result = $this->contact->select(['id', 'name', 'phone', 'email', 'subject', 'message', 'active', 'created_at'])->find($id)) {
            abort(404);
        } else {
            $validator = Validator::make($request->all(), [
                'active' => ['required', Rule::in(['read', 'unread'])],
            ], [
                'active.required' => 'Trạng thái là trường bắt buộc.',
                'active.in' => 'Trạng thái không hợp lệ.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if ($result->fill([
                    'active' => strtolower(trim($request['active']))
                ])->save()) {
                    hwa_notify_success("Cập nhật trạng thái liên hệ thành công.");
                    return redirect()->route("{$path}.index");
                } else {
                    hwa_notify_error("Lỗi cập nhật trạng thái liên hệ.");
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
        if (!hwa_check_permission('delete_contact') || !$result = $this->contact->select(['id', 'active'])->find($id)) {
            abort(404);
        } else {
            if ($result['active'] == 'unread') {
                hwa_notify_error("Không thể xóa liên hệ chưa đọc.");
            } else {
                if ($result->delete()) {
                    hwa_notify_success("Xóa liên hệ thành công.");
                } else {
                    hwa_notify_error("Lỗi xóa liên hệ.");
                }
            }
            return redirect()->back();
        }
    }

    /**
     * Replied contact
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function replyContact(Request $request, $id)
    {
        if (!hwa_check_permission('edit_contact') || !$contact = $this->contact->select(['id', 'subject', 'email'])->find($id)) {
            abort(404);
        } else {
            // Validate rule and message
            $validator = Validator::make($request->all(), [
                'message' => ['required']
            ], [
                'message.required' => 'Nội dung thư trả lời là trường bắt buộc.'
            ]);

            if ($validator->fails()) {
                // Invalid data
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (ReplyContact::create([
                    'contact_id' => $contact['id'],
                    'message' => $request['message'],
                ])) {
                    try {
                        $dataSend = [
                            'subject' => hwa_app_name() . " | " . $contact['subject'],
                            'message' => $request['message']
                        ];

                        Mail::to($contact['email'])->send(new ContactMail($dataSend));
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                    }

                    // Send success
                    hwa_notify_success("Gửi liên hệ thành công.");
                    return redirect()->back();
                } else {
                    // Error send contact
                    hwa_notify_error("Lỗi trả lời liên hệ.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }
}
