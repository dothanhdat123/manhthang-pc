<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Page;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PublicPageController extends Controller
{
    protected $viewPath = 'client.';

    /**
     * @var Contact
     */
    protected $contact;

    /**
     * @var Page
     */
    protected $page;

    /**
     * @var Faq
     */
    protected $faq;

    /**
     * PublicPageController constructor.
     * @param Contact $contact
     * @param Page $page
     * @param Faq $faq
     */
    public function __construct(Contact $contact, Page $page, Faq $faq)
    {
        $this->contact = $contact;
        $this->page = $page;
        $this->faq = $faq;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function contact(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.contact");
        } else {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'max:191'],
                'email' => ['required', 'email', 'max:191'],
                'phone' => ['nullable', 'max:20'],
                'subject' => ['nullable', 'max:255'],
                'message' => ['required'],
            ], [
                'name.required' => 'Tên là trường bắt buộc.',
                'name.max' => 'Tên có tối đa 191 ký tự.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email không đúng định dạng.',
                'phone.max' => 'SĐT có tối đa 20 ký tự.',
                'subject.max' => 'Tiêu đề có tối đa 255 ký tự.',
                'message.required' => 'Nội dung là trường bắt buộc.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $contact = $this->contact->create([
                    'name' => trim($request['name']),
                    'email' => strtolower(trim($request['email'])),
                    'phone' => trim($request['phone']),
                    'subject' => trim($request['subject']),
                    'message' => trim($request['message']),
                ]);
                $contact['subject'] = $contact['subject'] ?? "Liên hệ mới từ {$contact['email']}";

                try {
                    Mail::send('emails.contact', compact('contact'), function ($message) use ($contact) {
                        $message->to(hwa_setting('admin_email'))
                            ->subject(hwa_app_name() . " | Liên hệ mới từ {$contact['email']}");
                    });
                } catch (Exception $exception) {
                    Log::error($exception->getMessage());
                }

                hwa_notify_success("Gửi liên hệ thành công.");
                return redirect()->back();
            }
         }
    }

    /**
     * About page
     *
     * @return Application|Factory|View
     */
    public function about()
    {
        $slug = 'gioi-thieu';
        return $this->generatePage($slug);
    }

    /**
     * Term page
     *
     * @return Application|Factory|View
     */
    public function term()
    {
        $slug = 'chinh-sach-va-dieu-khoản';
        return $this->generatePage($slug);
    }

    /**
     * Delivery page
     *
     * @return Application|Factory|View
     */
    public function delivery()
    {
        $slug = 'chinh-sach-giao-hang';
        return $this->generatePage($slug);
    }

    /**
     * Returns page
     *
     * @return Application|Factory|View
     */
    public function returns()
    {
        $slug = 'chinh-sach-doi-tra';
        return $this->generatePage($slug);
    }

    /**
     * Generate page
     *
     * @param $slug
     * @return Application|Factory|View
     */
    protected function generatePage($slug)
    {
        $result = $this->page->whereSlug($slug)->whereActive(1)->first();
        if (!$result) {
            abort(404);
        } else {
            return view("client.page")->with([
                'result' => $result
            ]);
        }
    }

    /**
     * Faqs page
     *
     * @return Application|Factory|View
     */
    public function faqs()
    {
        $generals = $this->faq->whereActive(1)->whereType(1)->get();
        $others = $this->faq->whereActive(1)->whereType(0)->get();

        return view("client.faq")->with([
            'generals' => $generals,
            'others' => $others,
        ]);
    }
}
