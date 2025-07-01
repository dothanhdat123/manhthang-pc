<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Store;
use App\Notifications\Client\RegisterUserRequest;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $customer;

    /**
     * @var string View path
     */
    protected $viewPath = 'client.auth';

    /**
     * AuthController constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Customer register
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
   public function register(Request $request)
{
    $path = $this->viewPath;

    // GET method - hiển thị form đăng ký
    if ($request->getMethod() === 'GET') {
        if (auth()->check()) {
            return redirect()->intended('/');
        }

        $stores = Store::all(); // Lấy danh sách cửa hàng để hiển thị
        return view("{$path}.register")->with([
            'path' => $path,
            'stores' => $stores,
        ]);
    }

    // POST method - xử lý đăng ký
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'max:191'],
        'username' => ['required', 'min:6', 'max:191', 'unique:customers,username'],
        'email' => ['required', 'max:191', 'email', 'unique:customers,email'],
        'password' => ['required', 'min:6', 'max:32'],
        'store_id' => ['required', 'exists:stores,id'], // Validate store_id
        'checkbox' => ['required']
    ], [
        'name.required' => 'Tên là trường bắt buộc.',
        'name.max' => 'Tên có tối đa 191 ký tự.',
        'username.required' => 'Tên tài khoản là trường bắt buộc.',
        'username.min' => 'Tên tài khoản có tối thiểu 6 ký tự.',
        'username.max' => 'Tên tài khoản có tối đa 191 ký tự.',
        'username.unique' => 'Tên tài khoản đã tồn tại.',
        'email.required' => 'Email là trường bắt buộc.',
        'email.max' => 'Email có tối đa 191 ký tự.',
        'email.email' => 'Email không đúng định dạng.',
        'email.unique' => 'Email đã tồn tại.',
        'password.required' => 'Mật khẩu là trường bắt buộc.',
        'password.min' => 'Mật khẩu có tối thiểu 6 ký tự.',
        'password.max' => 'Mật khẩu có tối đa 32 ký tự.',
        'store_id.required' => 'Bạn chưa chọn cửa hàng.',
        'store_id.exists' => 'Cửa hàng không hợp lệ.',
        'checkbox.required' => 'Bạn chưa đồng ý với các điều khoản.',
    ]);

    if ($validator->fails()) {
        hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Thất bại!']);
        return redirect()->back()->withInput()->withErrors($validator);
    }

    // Chuẩn bị dữ liệu tạo tài khoản
    $userData = [
        'name' => $request['name'],
        'username' => $request['username'],
        'email' => $request['email'],
        'password' => bcrypt($request['password']),
        'store_id' => $request['store_id'],
    ];

    if (!hwa_demo_env()) {
        if ($customer = $this->customer->create($userData)) {
            $dataSend = [
                'subject' => hwa_app_name() . " | Đăng ký thành công tài khoản mới",
                'first_name' => $customer->name,
                'email' => $customer->email,
                'password' => trim($request['password']),
            ];

            try {
                $customer->notify(new RegisterUserRequest($dataSend));
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }

            hwa_notify_success("Đăng ký thành công.");
            return redirect()->route("{$path}.login");
        } else {
            hwa_notify_error("Lỗi đăng ký.");
            return redirect()->back()->withInput();
        }
    }

    hwa_notify_success("Đăng ký thành công.");
    return redirect()->route("{$path}.login");
}

    /**
     * Customer login
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function login(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->check()) {
                // redirect to dashboard if logged
                return redirect()->intended('/');
            } else {
                // redirect login form
                return view("{$path}.login")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method
            if (filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                // Validate email
                $validator_arr = ['email' => ['required', 'max:191', 'email', 'exists:customers,email']];
                $msg_ar = [
                    'email.required' => 'Email là trường bắt buộc.',
                    'email.max' => 'Email có tối đa 191 ký tự.',
                    'email.email' => 'Email không đúng định dạng.',
                    'email.exists' => 'Email không tồn tại.',
                ];
                $credentials = ['email' => $request['email'], 'password' => $request['password']];

                // Check existed user using email
                $checkExisted = $this->customer->findByEmail($credentials['email']);
            } else {
                // Validate username
                $validator_arr = ['email' => ['required', 'max:191', 'exists:customers,username']];
                $msg_ar = [
                    'email.required' => 'Tên tài khoản là trường bắt buộc.',
                    'email.max' => 'Tên tài khoản có tối đa 191 ký tự.',
                    'email.exists' => 'Tên tài khoản không tồn tại.',
                ];
                $credentials = ['username' => $request['email'], 'password' => $request['password']];

                // Check existed user using username
                $checkExisted = $this->customer->findByUserName($credentials['username']);
            }

            // Validate
            $validator = Validator::make($request->all(), array_merge($validator_arr, [
                'password' => ['required', 'min:6', 'max:191'],
            ]), array_merge($msg_ar, [
                'password.required' => 'Mật khẩu là trường bắt buộc.',
                'password.min' => 'Mật khẩu có tối thiểu 6 ký tự.',
                'password.max' => 'Mật khẩu có tối đa 191 ký tự.',
            ]));

            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Thất bại!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get status remember
                if ($request->has('remember_me')) {
                    $remember = true;
                } else {
                    $remember = false;
                }

                if ($checkExisted && $checkExisted->active == 1) {
                    // User active
                    if (auth()->attempt($credentials, $remember)) {
                        // Login successfully.
                        hwa_notify_success("Đăng nhập thành công.");
                        return redirect()->intended('/');
                    } else {
                        // Wrong password
                        hwa_notify_error("Mật khẩu không đúng.");
                        return redirect()->back()->withInput()->withErrors([
                            'password' => 'Mật khẩu không đúng.',
                        ]);
                    }
                } else {
                    // User blocked
                    hwa_notify_error("Tài khoản bị khóa.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Customer logout
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        hwa_notify_success("Đăng xuất thành công.");
        return redirect()->intended('/');
    }
}
