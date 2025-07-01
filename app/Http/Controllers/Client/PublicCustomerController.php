<?php

namespace App\Http\Controllers\Client;

use App\Exports\OrderDetailExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicCustomerController extends Controller
{
    protected $viewPath = "client.customers";

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var CustomerAddress
     */
    protected $customerAddress;

    /**
     * @var Order
     */
    protected $order;

    public function __construct(Customer $customer, CustomerAddress $customerAddress, Order $order)
    {
        $this->customer = $customer;
        $this->customerAddress = $customerAddress;
        $this->order = $order;
    }

    /**
     * Overview
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $path = $this->viewPath;

        $card = [
            'pending' => $this->countOrder('pending'),
            'processing' => $this->countOrder('processing'),
            'cancel' => $this->countOrder('cancel'),
            'fail' => $this->countOrder('fail'),
            'done' => $this->countOrder('done'),
            'total' => $this->order->whereCustomerId(auth()->id())->wherePaymentStatus(1)->sum('total'),
        ];

        return view("{$path}.index")->with([
            'card' => $card,
        ]);
    }

    /**
     * Counting number order
     *
     * @param string $status
     * @return mixed
     */
    protected function countOrder($status = 'pending')
    {
        return $this->order->whereCustomerId(auth()->id())->whereActive($status)->get()->count();
    }

    /**
     * Orders
     *
     * @return Application|Factory|View
     */
    public function orders()
    {
        $path = $this->viewPath . '.orders';
        $results = $this->order->whereCustomerId(auth()->id())->orderBy('id', 'desc')->get();
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show order detail
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function show_order($id)
    {
        $path = $this->viewPath . '.orders';

        if (!$order = $this->order->with([
            'customer_address',
            'order_details'
        ])->find($id)) {
            abort(404);
        } else {
            return view("{$path}.show")->with([
                'path' => $path,
                'result' => $order
            ]);
        }
    }

    /**
     * Cancel order
     *
     * @param $id
     * @return RedirectResponse
     */
    public function cancel_order($id)
    {
        if (!$order = $this->order->find($id)) {
            abort(404);
        } else {
            if ($order['active'] == 'pending' || $order['active'] == 'processing') {
                $order->active = 'cancel';
                $order->save();
                hwa_notify_success("Hủy đơn hàng thành công.");
            } else {
                hwa_notify_error("Hủy đơn hàng thất bại.");
            }
            return redirect()->back();
        }
    }

    /**
     * Export order detail
     *
     * @param $id
     * @return BinaryFileResponse
     */
    public function export_detail($id)
    {
        if (!$result = $this->order->with([
            'customer',
            'customer_address',
            'order_details',
        ])->find($id)) {
            abort(404);
        } else {
            $file_name = strtolower("hoa_don_{$id}_" . Carbon::parse($result['created_at'])->format('d_m_Y') . '_' . time() . '.xlsx');
            return Excel::download(new OrderDetailExport($result), $file_name);
        }
    }

    /**
     * Address list
     *
     * @return Application|Factory|View
     */
    public function address()
    {
        $path = $this->viewPath;
        $customer = $this->customer->with(['customer_addresses'])->find(auth()->id());
        return view("{$path}.address.index")->with([
            'path' => $path . '.address',
            'address' => $customer['customer_addresses']
        ]);
    }

    /**
     * Add new address
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function add_address(Request $request)
    {
        $path = $this->viewPath . '.address';

        if ($request->getMethod() == 'GET') {
            // Return view add address
            return view("{$path}.form")->with([
                'path' => $path,
            ]);
        } else {
            return $this->saveAddress($request);
        }
    }

    /**
     * Update address
     *
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function update_address(Request $request, $id)
    {
        $path = $this->viewPath . '.address';
        if ($request->getMethod() == 'GET') {
            if (!$address = $this->customerAddress->find($id)) {
                abort(404);
            } else {
                // Return view add address
                return view("{$path}.form")->with([
                    'path' => $path,
                    'address' => $address,
                ]);
            }
        } else {
            if (!$address = $this->customerAddress->find($id)) {
                abort(404);
            } else {
                return $this->saveAddress($request, $address);
            }
        }
    }

    /**
     *
     *
     * @param $request
     * @param null $address
     * @return RedirectResponse
     */
    public function saveAddress($request, $address = null)
    {
        $path = $this->viewPath . '.address';

        // Validate rule and messages
        $validator = validator($request->all(), [
            'name' => ['required', 'max:191'],
            'phone' => ['required', 'max:20', 'min:10'],
            'address' => ['required'],
            'is_default' => ['nullable', Rule::in(['0', '1'])],
        ], [
            'name.required' => 'Họ tên là trường bắt buộc.',
            'name.max' => 'Họ tên có tối đa 191 ký tự.',
            'phone.required' => 'Tên tài khoản là trường bắt buộc.',
            'phone.max' => 'Tên tài khoản có tối đa 20 ký tự.',
            'phone.min' => 'Tên tài khoản có tối thiểu 10 ký tự.',
            'address.required' => 'Địa chỉ là trường bắt buộc.',
            'is_default.in' => 'Địa chỉ mặc định khộng hợp lệ.',
        ]);

        if ($validator->fails()) {
            // Validate and notice invalid input data
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            // Reset address default if set new
            if ($request->input('is_default')) {
                foreach ($this->customerAddress->whereCustomerId(auth()->id())->get() as $address_item) {
                    $address_item->is_default = 0;
                    $address_item->save();
                }
            }

            // Address data
            $data = [
                'customer_id' => auth()->id(),
                'name' => $request['name'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'is_default' => $request['is_default'] ?? 0,
            ];

            if (!$address) {
                // Create new address
                $this->customerAddress->create($data);
                hwa_notify_success("Thêm địa chỉ mới thành công.");
            } else {
                // Update address
                $address->fill($data)->save();
                hwa_notify_success("Cập nhật địa chỉ thành công.");
            }
            return redirect()->route("{$path}.index");
        }
    }

    /**
     * Delete customer address
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy_address($id)
    {
        if (!$address = $this->customerAddress->find($id)) {
            abort(404);
        } else {
            if ($address->is_default == 1) {
                hwa_notify_error("Không thể xóa địa chỉ mặc định.");
            } else {
                $address->delete();
                hwa_notify_success("Xóa địa chỉ thành công.");
            }
            return redirect()->back();
        }
    }

    /**
     * Update profile
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function profile(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.profile")->with([
                'path' => $path,
                'customer' => auth()->user()
            ]);
        } else {
            $customerId = auth()->id();
            $customer = $this->customer->find($customerId);
            $validator = validator($request->all(), [
                'name' => ['required', 'max:191'],
                'email' => ['required', 'max:191', 'email', 'unique:customers,email,' . $customerId],
                'username' => ['required', 'max:191', 'unique:customers,username,' . $customerId],
            ], [
                'name.required' => 'Họ tên là trường bắt buộc.',
                'name.max' => 'Họ tên có tối đa 191 ký tự.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email có định dạng không đúng.',
                'email.unique' => 'Email đã tồn tại.',
                'username.required' => 'Tên tài khoản là trường bắt buộc.',
                'username.max' => 'Tên tài khoản có tối đa 191 ký tự.',
                'username.unique' => 'Tên tài khoản đã tồn tại.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $customer->fill([
                    'name' => trim($request['name']),
                    'username' => strtolower(trim($request['username'])),
                    'email' => strtolower(trim($request['email'])),
                ])->save();
                hwa_notify_success("Cập nhật thành công!", ['title' => 'Thành công']);
                return redirect()->back();
            }
        }
    }

    /**
     * Change customer password
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.change_password")->with([
                'path' => $path,
            ]);
        } else {
            // Get current user
            $user = $this->customer->find(auth()->id());

            // Validate data
            $validator = Validator::make($request->all(), [
                'old_password' => ['required', 'min:6', 'max:32'],
                'password' => ['required', 'min:6', 'max:32'],
                'password_confirmation' => ['required', 'min:6', 'max:32', 'same:password'],
            ], [
                'old_password.required' => 'Mật khẩu hiện tại là trường bắt buộc.',
                'old_password.min' => 'Mật khẩu hiện tại có tối thiểu 6 ký tự.',
                'old_password.max' => 'Mật khẩu hiện tại có tối đa 191 ký tự.',
                'password.required' => 'Mật khẩu mới là trường bắt buộc.',
                'password.min' => 'Mật khẩu mới có tối thiểu 6 ký tự.',
                'password.max' => 'Mật khẩu mới có tối đa 191 ký tự.',
                'password_confirmation.required' => 'Mật khẩu nhập lại là trường bắt buộc.',
                'password_confirmation.min' => 'Mật khẩu nhập lại có tối thiểu 6 ký tự.',
                'password_confirmation.max' => 'Mật khẩu nhập lại có tối đa 191 ký tự.',
                'password_confirmation.same' => 'Mật khẩu nhập lại không khớp.',
            ]);

            if ($validator->fails()) {
                // Validate fail and notice error message
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (!password_verify($request['old_password'], $user['password'])) {
                    // Old password wrong
                    hwa_notify_error('Mật khẩu hiện tại không đúng.');
                    return redirect()->back()->withInput()->withErrors([
                        'old_password' => 'Mật khẩu hiện tại không đúng.'
                    ]);
                } else {
                    if (password_verify($request['password'], $user['password'])) {
                        // New password must be different old password
                        hwa_notify_error('Mật khẩu mới phải khác mật khẩu hiện tại.');
                        return redirect()->back()->withInput()->withErrors([
                            'password' => 'Mật khẩu mới phải khác mật khẩu hiện tại.'
                        ]);
                    } else {
                        if (!hwa_demo_env()) {
                            // Update new password
                            $user['password'] = bcrypt($request['password']);
                            $user->save();
                        }

                        // Notice and return users list
                        hwa_notify_success("Đổi mật khẩu thành công.");
                        return redirect()->back();
                    }
                }
            }
        }
    }
}
